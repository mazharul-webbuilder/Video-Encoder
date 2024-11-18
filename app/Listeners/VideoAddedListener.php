<?php

namespace App\Listeners;

use App\Events\VideoAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\VideoEncodingJob;

class VideoAddedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(VideoAdded $event)
    {
        // Dispatch the VideoEncodingJob for the newly added video
        VideoEncodingJob::dispatch($event->videoUid, $event->encodingResolution, $event->destination);
    }
}

