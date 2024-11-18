<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\View\View;
use \DataTables;
use Illuminate\Support\Facades\File;


class EncoderController extends Controller
{

    /**
     * Fetch encoder videos
    */
    public function fetchVideos(): View
    {
        $videos = Video::where('status', Video::$VIDEO_STATUS_ENCODING)
                        ->orWhere('status', Video::$VIDEO_STATUS_FAILED)
                            ->orderBy('id', 'DESC')->select(['id', 'file_name', 'video_id', 'progress', 'status', 'created_at'])->get();

        return view('pages.encoder.fetch_video', compact('videos'));
    }

    /**
     * Response all Encoding videos
    */
    public function getAllEncodingVideos(): JsonResponse
    {
        $videos = Video::where('status', Video::$VIDEO_STATUS_ENCODING)
            ->orWhere('status', Video::$VIDEO_STATUS_FAILED)
            ->orderBy('id', 'DESC')
            ->select(['id', 'file_name', 'status', 'video_id', 'progress', 'created_at'])
            ->get()
            ->map(function ($video) {
                // Format the created_at attribute
                $video->fetched_at = $video->created_at->format('d-m-Y H:i:A');
                return $video;
            });

        return response()->json($videos);
    }


    /**
     * Fetch all videos
    */
    public function fetchAllVideos(): View
    {
        $videos = Video::withTrashed()->orderBy('id', 'DESC')
                        ->simplePaginate(20);

        return view('pages.encoder.all-videos', compact('videos'));
    }

    /**
     * Encoding video List
    */
    public function encodingList(): View
    {
        return view('pages.encoder.encoding_list');
    }

    /**
     * Get getEncodingList
    */
    public function getEncodingList(Request $request)
    {
        // Store all videos to from public/uploads to database videos table
        Artisan::call('app:fetch-and-store-videos');

        // Get only Pending videos
        $videos = Video::query()
            ->where('status', Video::$VIDEO_STATUS_PENDING)
            ->orderBy('id', 'DESC');

        // Yajra datatable call
        return DataTables::eloquent($videos)
            ->editColumn('file_name', function ($video){
                $fileName = $video->getAttribute('file_name');
                return getHumanReadableFilename($fileName);
            })
            ->addColumn('action', function ($video) {
                return '
                <p class="text-center">
                    <input type="checkbox" class="btn btn-primary encodingCheckbox" data-id="' . $video->id . '" />
                </p>
                ';
            })->rawColumns(['action'])->make(true);
    }

    /**
     * Encoded Videos view
    */
    public function encodedVideos(): View
    {
        $videos = Video::where('status', Video::$VIDEO_STATUS_COMPLETED)->
            orderBy('id', 'DESC')->simplePaginate(20);

        return view('pages.encoder.encoded_videos', compact('videos'));
    }

    /**
     * Soft delete video
     */
    public function softDelete(int $videoId): JsonResponse
    {
        try {
            $video = Video::findOrFail($videoId);

            $backupDestinationPath = public_path('backup_outputs');
            $backupRawVideoDestinationPath = public_path('backup_raw_video');

            if ($video->status == 'encoding') {
                throw new \Exception('You can\'t delete a video with status encoding');
            } elseif ($video->status == 'completed') {
                // Ensure backup directory exists
                if (!File::exists($backupDestinationPath)) {
                    File::makeDirectory($backupDestinationPath, 0777, true, true);
                }
                // Ensure backup directory exists for raw video
                if (!File::exists($backupRawVideoDestinationPath)) {
                    File::makeDirectory($backupRawVideoDestinationPath, 0777, true, true);
                }

                // Path to the video file
                $videoCurrentFilePath = public_path('outputs/' . $video->destination_path . '/' . $video->file_name);
                $rawVideoCurrentFilePath = public_path('uploads/' . $video->file_name);

                if (File::exists($videoCurrentFilePath)) {
                    // Move the video file to the backup directory
                    $backupFilePath = $backupDestinationPath . '/' . $video->file_name;
                    File::move($videoCurrentFilePath, $backupFilePath);
                } else {
                    throw new \Exception('Video not found in the outputs folder');
                }

                if (File::exists($rawVideoCurrentFilePath)) {
                    // Move the raw video file to the backup directory
                    $rawBackupFilePath = $backupRawVideoDestinationPath . '/' . $video->file_name;
                    File::move($rawVideoCurrentFilePath, $rawBackupFilePath);
                } else {
                    throw new \Exception('Raw video not found in the uploads folder');
                }
            }

            // Soft delete the video record
            $video->delete();

            return response()->json(['message' => 'Video soft deleted successfully'], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_OK);
        }
    }

    /**
     * Restore Soft Delete Video
     * @todo (backup_raw_video <-> uploads) file from a directory to another directory
     * @todo (backup_outputs <-> outputs/destination_folder) directory moves on to another
    */
    public function restore(int $id): JsonResponse
    {
        try {
            $video = Video::withTrashed()->findOrFail($id);

            $fileName = $video->getAttribute('file_name');

            // restore raw video file to uploads folder
            $backupRawFilePath = public_path('backup_raw_video') . '/' . $fileName;
            $destinationPath = public_path('uploads') . '/' . $fileName;

            if (File::exists($backupRawFilePath)) {
                File::move($backupRawFilePath, $destinationPath);
            }

            // restore video folder to output folder into destination
            // Path to the video file
            $backupOutputFolderPath = public_path('backup_outputs/' . $fileName);
            $destinationOutputFolderPath = public_path('outputs/' . $video->getAttribute('destination_path') . '/' . $fileName);

            if (File::exists($backupOutputFolderPath)) {
                File::move($backupOutputFolderPath, $destinationOutputFolderPath);
            }

            $video->restore();

            return response()->json(['message' => 'Video restored successfully'], 200);

        } catch (Exception $exception) {
            return response()->json(['error' => 'No data found with this id'], Response::HTTP_OK);
        }
    }

    /**
     * Delete data permanently
    */
    public function forceDelete(int $id)
    {
        try {
            $video = Video::findOrFail($id);

            $fileNameAsFolderName = $video->getAttribute(key: 'file_name');
            $destinationFolder = $video->getAttribute(key: 'destination_path');

            // Directory Path that has to be deleted
            $dataToDeletePath = public_path('outputs') . '/' . $destinationFolder . '/' .  $fileNameAsFolderName;

            // Check if  path exists
            if (File::exists($dataToDeletePath)){
                // Delete video from output folder
                File::deleteDirectory($dataToDeletePath);
                // Delete data from DB
                $video->forceDelete();
            }else{
                return response()->json(['error' => 'Directory not exist. Something went wrong!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Video Deleted Permanently.'], Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json(['error' => 'No data found with this id'], Response::HTTP_OK);
        }
    }


}
