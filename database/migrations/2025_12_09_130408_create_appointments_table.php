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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('doctor_schedules')->onDelete('cascade');
            $table->date('appointment_date')->nullable();
            $table->integer('queue_number');
            $table->integer('alert_before')->default(3);
            $table->tinyInteger('alert_sent')->default(0);
            $table->string('appstatus')->default('pending');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
