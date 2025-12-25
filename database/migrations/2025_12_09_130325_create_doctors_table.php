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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialization');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('license')->unique();
            $table->string('room')->nullable();
            $table->text('bio')->nullable();
            $table->string('degree')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave', 'retired'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
