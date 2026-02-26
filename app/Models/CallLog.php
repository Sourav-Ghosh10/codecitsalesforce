<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    protected $fillable = [
        'call_record_number',
        'client_id',
        'customer_name',
        'phone_number',
        'staff_member_id',
        'dialer_platform',
        'call_direction',
        'call_start_time',
        'call_end_time',
        'call_duration_seconds',
        'call_result',
        'notes',
        'next_follow_up_date',
        'created_by',
        'updated_by',
        'admin_edit_reason',
    ];

    /**
     * Cast dates to Carbon instances
     */
    protected function casts(): array
    {
        return [
            'call_start_time' => 'datetime',
            'call_end_time' => 'datetime',
            'next_follow_up_date' => 'date',
        ];
    }

    /**
     * Call direction constants
     */
    public const DIRECTION_INCOMING = 'Incoming';
    public const DIRECTION_OUTGOING = 'Outgoing';

    /**
     * Call result constants
     */
    public const RESULT_CONNECTED = 'Connected';
    public const RESULT_NO_ANSWER = 'No Answer';
    public const RESULT_BUSY = 'Busy';
    public const RESULT_VOICEMAIL = 'Voicemail';
    public const RESULT_WRONG_NUMBER = 'Wrong Number';
    public const RESULT_FOLLOW_UP_NEEDED = 'Follow-up Needed';
    public const RESULT_NOT_INTERESTED = 'Not Interested';
    public const RESULT_CALLBACK_REQUESTED = 'Callback Requested';
    public const RESULT_DISCONNECTED = 'Disconnected';

    /**
     * Available call directions
     */
    public static function getCallDirections(): array
    {
        return [
            self::DIRECTION_INCOMING => 'Incoming',
            self::DIRECTION_OUTGOING => 'Outgoing',
        ];
    }

    /**
     * Available call results
     */
    public static function getCallResults(): array
    {
        return [
            self::RESULT_CONNECTED => 'Connected',
            self::RESULT_NO_ANSWER => 'No Answer',
            self::RESULT_BUSY => 'Busy',
            self::RESULT_VOICEMAIL => 'Voicemail',
            self::RESULT_WRONG_NUMBER => 'Wrong Number',
            self::RESULT_FOLLOW_UP_NEEDED => 'Follow-up Needed',
            self::RESULT_NOT_INTERESTED => 'Not Interested',
            self::RESULT_CALLBACK_REQUESTED => 'Callback Requested',
            self::RESULT_DISCONNECTED => 'Disconnected',
        ];
    }

    /**
     * Available dialer platforms
     */
    public static function getDialerPlatforms(): array
    {
        return [
            'Five9' => 'Five9',
            'Vicidial' => 'Vicidial',
            'Asterisk' => 'Asterisk',
            'Twilio' => 'Twilio',
            'Dialpad' => 'Dialpad',
            'RingCentral' => 'RingCentral',
            'GoToConnect' => 'GoToConnect',
            ' NICE' => 'NICE',
            'Genesys' => 'Genesys',
            'Other' => 'Other',
        ];
    }

    /**
     * Get formatted call duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->call_duration_seconds / 3600);
        $minutes = floor(($this->call_duration_seconds % 3600) / 60);
        $seconds = $this->call_duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get result color class
     */
    public function getResultColorAttribute(): string
    {
        return match($this->call_result) {
            self::RESULT_CONNECTED => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            self::RESULT_NO_ANSWER => 'bg-gray-100 text-gray-700 dark:bg-gray-700/30 dark:text-gray-400',
            self::RESULT_BUSY => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            self::RESULT_VOICEMAIL => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            self::RESULT_WRONG_NUMBER => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            self::RESULT_FOLLOW_UP_NEEDED => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            self::RESULT_NOT_INTERESTED => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
            self::RESULT_CALLBACK_REQUESTED => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            self::RESULT_DISCONNECTED => 'bg-gray-100 text-gray-700 dark:bg-gray-700/30 dark:text-gray-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get direction color class
     */
    public function getDirectionColorAttribute(): string
    {
        return match($this->call_direction) {
            self::DIRECTION_INCOMING => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            self::DIRECTION_OUTGOING => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Generate unique call record number
     */
    public static function generateCallRecordNumber(): string
    {
        $prefix = 'CALL';
        $date = now()->format('Ymd');
        $random = strtoupper(uniqid());
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Get the client associated with this call log
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the staff member who made/received the call
     */
    public function staffMember()
    {
        return $this->belongsTo(User::class, 'staff_member_id');
    }

    /**
     * Get the user who created this record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
