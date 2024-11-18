<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class EncodeVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:encode-video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowBitRate = (new X264)->setKiloBitrate(1000);

        $this->info('Convert video start');

        FFMpeg::fromDisk('uploads')
                ->open('video.mp4')
                ->exportForHLS()
                ->addFormat($lowBitRate)
                ->onProgress(function($progress) { 
                    $this->info("Progress :{$progress}%");
                })
                ->toDisk('outputs')
                ->save('video.m3u8');

        $this->info("Encoding Complete");
    }
}
