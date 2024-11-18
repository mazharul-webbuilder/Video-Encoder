<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoAdded
{
    use Dispatchable, SerializesModels;

    public $videoUid;
    public $encodingResolution;
    public $destination;

    public function __construct($videoUid, array $encodingResolution, string $destination)
    {
        $this->videoUid = $videoUid;
        $this->encodingResolution = $encodingResolution;
        $this->destination = $destination;
    }
}
