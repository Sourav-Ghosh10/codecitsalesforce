<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'codec_settings';

    protected $fillable = [
        'company_name',
        'company_address',
        'company_gst',
        'company_mobile',
        'company_email',
        'company_website',
        'upi_id',
        'bank_name',
        'bank_account',
        'bank_ifsc',
        'bank_branch',
        'available_currencies',
        'available_taxes',
        'currency_conversion_rates',
        'default_notes',
        'default_terms',
    ];

    protected $casts = [
        'available_currencies'      => 'array',
        'available_taxes'           => 'array',
        'currency_conversion_rates' => 'array',
    ];

    /**
     * Retrieve the single settings row, or return defaults if none exists.
     */
    public static function getConfig(): self
    {
        $setting = self::first();

        if (!$setting) {
            // Return default values as a new (unsaved) instance
            $setting = new self([
                'company_name'         => 'Codec IT',
                'company_address'      => 'Mani Casadona, Plot No. IIF/04, Newtown, Kolkata, West Bengal 700156',
                'company_gst'          => '19AAGCN5427M1ZY',
                'company_mobile'       => '022-46635616',
                'company_email'        => 'info@codecit.com',
                'company_website'      => 'www.codecit.com',
                'upi_id'               => 'codecit@upi',
                'bank_name'            => 'HDFC Bank',
                'bank_account'         => '50200012345678',
                'bank_ifsc'            => 'HDFC0001234',
                'bank_branch'          => 'Newtown Branch',
                'available_currencies' => [
                    ['symbol' => '₹', 'name' => 'INR'],
                    ['symbol' => '$', 'name' => 'USD'],
                    ['symbol' => '€', 'name' => 'EUR'],
                ],
                'available_taxes' => [
                    ['name' => 'GST', 'rate' => 18],
                    ['name' => 'VAT', 'rate' => 5],
                ],
                'currency_conversion_rates' => [
                    ['currency' => 'USD', 'rate' => 83.50],
                    ['currency' => 'EUR', 'rate' => 90.00],
                    ['currency' => 'GBP', 'rate' => 105.00],
                ],
                'default_notes' => 'Thank you for the Business!',
                'default_terms' => "1. All invoices are payable within 15 days from the date of invoice.\n2. Please include invoice number in your payment reference.",
            ]);
        }

        return $setting;
    }
}
