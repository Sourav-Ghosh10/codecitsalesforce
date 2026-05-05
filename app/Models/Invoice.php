<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'codec_invoices';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'client_name',
        'total_amount',
        'tax_amount',
        'base_amount',
        'invoice_type',
        'status',
        'data',
    ];

    public $timestamps = false;

    protected $casts = [
        'invoice_date' => 'date',
        'data' => 'array',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
