<?php

namespace App\Http\Controllers;

use App\Events\VideoAdded;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class VideoEncodeController extends Controller
{
    /**
     * Common Method for single and bulk encoding request
     *  Filter the request either single or bulk and
     *  Calling their respected method
    */
    public function encodeVideo(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'encoding_resolution' => 'required',
            'destination_path' => 'required'
        ]);
        try {
            $videoIds = explode(',', $request->input('video_ids'));

            if (count($videoIds) == 1) {
                // call single encoding
                $request->merge([
                    'video_id' => $videoIds[0]
                ]);
                // Single Video Encode
                $response = $this->startVideoEncoding($request);
            } else {
                // call bulk method
                $request->merge([
                    'video_id_array' => $videoIds,
                ]);
                // Bulk encode
                $response = $this->startBulkVideoEncoding($request);
            }
            if ($response) {
                return response()->json(['response' => Response::HTTP_OK, 'message' => 'Encoding started sucessfully']);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function startVideoEncoding(Request $request)
    {
        $this->validate($request, [
            'video_id' => 'required|exists:videos,id',
        ]);

        $videoUid = $request->input('video_id');
        $video = Video::find($videoUid);
        // Update meta data and make video status encoding
        $this->updateMetaData($video, $request);

        if (isset($video) && $video->status == 'completed') {
            return response()->json(['message' => 'Video already encoded in server.', 'video' => $video], 200);
        }
        $encodingResolution = $request->encoding_resolution;
        $destination = $request->destination_path;

        event(new VideoAdded($videoUid, $encodingResolution, $destination));

        return true;
//        return response()->json(['message' => 'Video encoding started.'], 200);
    }

    public function startBulkVideoEncoding(Request $request)
    {
        $this->validate($request, [
            'video_id_array' => 'required|array',
        ]);

        foreach ($request->input('video_id_array') as $videoUid) {
            $video = Video::find($videoUid);
            // Update meta data and make video status encoding
            $this->updateMetaData($video, $request);

            $encodingVideoIds = [];

            if (isset($video) && $video->status != 'completed') {
                $encodingResolution = $request->encoding_resolution;
                $destination = $request->destination_path;

                event(new VideoAdded($videoUid, $encodingResolution, $destination));
                array_push($encodingVideoIds, $videoUid);
            }
        }
        return true;
//        return response()->json(['message' => 'Video encoding started for bellow Ids.', 'video_ids' => $encodingVideoIds], 200);
    }

    public function fetchVideos(){
        $videos = Video::get();

        return response()->json(['msg' => "Video fetched Successfully", 'videos' => $videos], 200);

    }

    public function encodingVideosList(){
        $videos = Video::where('status', 'encoding')->get();

        return response()->json(['msg' => "Video fetched Successfully", 'videos' => $videos], 200);

    }

    public function encodingVideoStatus($videoId) {
        $video = Video::find($videoId);
        $fetch_status = Cache::get($video->video_id);

        if ($fetch_status == null) {
            $fetch_status = $video->progress;
        }

        return response()->json(['encode_status' => $fetch_status], 200);
    }

    /**
     * Update video meta data
    */
    public function updateMetaData($video, $request): void
    {
        $video->update([
            'status' => 'encoding',
            'video_formats' => json_encode($request->input('encoding_resolution')),
            'destination_path' => $request->input('destination_path')
        ]);
    }


    /**
     * Retry Failed Video
    */
    public function retryFailedVideo(int $id): JsonResponse
    {
        try {
            $video = Video::findOrFail($id);

            $video->update(['status' => 'pending']);
            // Delete existing files from outputs folder
            File::deleteDirectory(public_path('outputs/' . $video->destination_path . '/' . $video->file_name ));
            // Add data to request
            $request = new Request();

            $request->merge([
                'video_ids' => $video->id,
                'video_id' => $video->id,
                'encoding_resolution' => json_decode($video->video_formats),
                'destination_path' => $video->destination_path,
            ]);

            $this->startVideoEncoding($request);

            return response()->json(['message' => 'Video retried'], 200);

        } catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

}
