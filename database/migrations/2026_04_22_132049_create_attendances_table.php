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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // ربط الدعوة (QR)
            $table->foreignId('invitation_id')
                ->constrained('invitations')
                ->onDelete('cascade');

            // وقت الدخول
            $table->dateTime('check_in_time');

            // حالة الدخول
            $table->enum('status', ['allowed', 'denied'])->default('allowed');

            // مكان/جهاز الدخول (مهم للتحليل)
            $table->string('device_info')->nullable();
            $table->string('ip_address')->nullable();

            // هل تم التحقق بنجاح؟
            $table->boolean('is_valid')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};