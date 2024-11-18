<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FetchAndStoreVideos extends Command
{
    protected $signature = 'app:fetch-and-store-videos';
    protected $description = 'Fetch video names from the "uploads" folder and store them in the database';

    public function handle()
    {
        $uploadsPath = public_path('uploads');

        $videoFiles = File::files($uploadsPath);

        foreach ($videoFiles as $videoFile) {
            $fileName = $videoFile->getFilename();

            if (!Video::withTrashed()->where('file_name', $fileName)->exists()) {
                Video::create([
                    'file_name' => $fileName,
                    'video_id' => uniqid(),
                    'status' => 'pending',
                ]);
            }
        }

        $this->info('Video names fetched and stored in the database.');
    }
}
