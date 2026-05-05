<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAmount extends Model
{
    use HasFactory;

    protected $table = 'codec_project_amount';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'project_id',
        'project_amount',
        'project_gst',
        'total_amount',
        'description',
    ];

    public $timestamps = false;
}
