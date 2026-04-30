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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            // نوع العملية
            $table->enum('action_type', [
                'scan',
                'success',
                'fail',
                'blacklisted',
                'duplicate_attempt'
            ]);

            // ربط بالدعوة (QR)
            $table->foreignId('invitation_id')
                ->nullable()
                ->constrained('invitations')
                ->onDelete('set null');

            // ربط بالمستخدم (اختياري)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // معلومات الجهاز
            $table->string('device_info')->nullable();
            $table->string('ip_address')->nullable();

            // الرسالة التفصيلية
            $table->text('message')->nullable();

            // مستوى الخطورة (AI)
            $table->integer('risk_score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};