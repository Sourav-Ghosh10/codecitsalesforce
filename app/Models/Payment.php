<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'codec_payments';

    protected $fillable = [
        'project_id',
        'invoice_id',
        'amount',
        'date',
        'inv_date',
        'mode',
        'tax_number',
        'status',
        'base_amount',
        'gst',
    ];

    public $timestamps = false; // Using custom created_at

    protected $casts = [
        'date' => 'date',
        'inv_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function getRefAttribute()
    {
        return 'PAY-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
}
