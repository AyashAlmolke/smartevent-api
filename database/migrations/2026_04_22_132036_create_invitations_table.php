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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();

            // ربط المستخدم
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // ربط الفعالية
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // QR Code - تم التعديل من string إلى text
            $table->text('qr_code');  // ← هذا هو التعديل

            // حالة الدعوة
            $table->enum('status', ['active', 'used', 'expired'])->default('active');

            // وقت استخدام QR
            $table->timestamp('used_at')->nullable();

            // عدد مرات المحاولة (مهم للأمان)
            $table->integer('scan_attempts')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};