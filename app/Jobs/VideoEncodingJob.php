<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoEncodingJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $videoId;
    protected $encodingResolution;
    protected $destination;

    public function __construct($videoId, $encodingResolution, $destination)
    {
        $this->videoId = $videoId;
        $this->encodingResolution = $encodingResolution;
        $this->destination = $destination;
    }

    public function handle()
    {
        $video = Video::find($this->videoId);

        if (!$video) {
            return;
        }

        try {
            $video->update(['status' => 'encoding']);

            if (in_array('360p', $this->encodingResolution)) {
                $this->encodeVideo($video->file_name, '360p', count($this->encodingResolution));
            }

            if (in_array('720p', $this->encodingResolution)) {
                $this->encodeVideo($video->file_name, '720p', count($this->encodingResolution));
            }

            if (in_array('1080p', $this->encodingResolution)) {
                $this->encodeVideo($video->file_name, '1080p', count($this->encodingResolution));
            }

            if (in_array('1440p', $this->encodingResolution)) {
                $this->encodeVideo($video->file_name, '1440p', count($this->encodingResolution));
            }

            $video->update(['status' => 'completed']);

        } catch (\Exception $exception){
            $video->update(['status' => 'failed']);
        }
    }

    protected function encodeVideo($videoFileName, $resolution, $countResolution)
    {
        switch ($resolution) {
            case '360p':
                $bitRatelimit = 5000;
                break;
            case '720p':
                $bitRatelimit = 7500;
                break;
            case '1080p':
                $bitRatelimit = 9000;
                break;
            case '1440p':
                $bitRatelimit = 13000;
                break;
            default:
                $bitRatelimit = 1000;
        }

        $bitRate = (new X264)->setKiloBitrate($bitRatelimit);
        FFMpeg::fromDisk('uploads')
            ->open($videoFileName)
            ->exportForHLS()
            ->addFormat($bitRate)
            ->onProgress(function ($progress, $countResolution) {
                $progressPercentage = ceil($progress);
                $this->updateProgress($progressPercentage);
            })
            ->toDisk('outputs')
            ->save($this->destination.'/'.$videoFileName.'/'."{$resolution}_{$videoFileName}.m3u8");
    }


    protected function updateProgress($percentage)
    {
        $video = Video::find($this->videoId);
//        Log::debug('video_data', [$video]);
        if ($video) {
            // if ($percentage != 100) {
                Cache::put($video->video_id, $percentage, 86400);
            // }
            // Log::debug('video_data_log', ['progress' => [$percentage]]);
            $video->update(['progress' => $percentage]);
        }
    }
}
