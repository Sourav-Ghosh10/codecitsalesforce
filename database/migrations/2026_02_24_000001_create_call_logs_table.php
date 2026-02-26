<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_record_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('phone_number');
            $table->foreignId('staff_member_id')->constrained('users')->onDelete('cascade');
            $table->string('dialer_platform');
            $table->enum('call_direction', ['Incoming', 'Outgoing']);
            $table->dateTime('call_start_time');
            $table->dateTime('call_end_time');
            $table->integer('call_duration_seconds');
            $table->string('call_result');
            $table->text('notes')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->text('admin_edit_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
