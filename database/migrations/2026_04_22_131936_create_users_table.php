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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();

            // بيانات إضافية
            $table->string('national_id')->nullable();
            $table->string('organization')->nullable();

            // حالة المستخدم
            $table->boolean('is_blacklisted')->default(false);
            $table->boolean('is_verified')->default(true);

            // AI / Tracking
            $table->timestamp('last_seen_at')->nullable();
            $table->integer('risk_level')->default(0); // مهم للـ AI

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};