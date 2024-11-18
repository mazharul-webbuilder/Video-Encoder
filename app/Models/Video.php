<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    public static string $VIDEO_STATUS_PENDING = 'pending';
    public static string $VIDEO_STATUS_ENCODING = 'encoding';
    public static string $VIDEO_STATUS_COMPLETED = 'completed';
    public static string $VIDEO_STATUS_FAILED = 'failed';

    protected $guarded = [];
}
