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
        Schema::create('behavior_data', function (Blueprint $table) {
            $table->id();

            // ربط المستخدم
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // ربط الفعالية
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // عدد المحاولات
            $table->integer('attempts_count')->default(0);

            // توقيتات الدخول (تحليل السلوك)
            $table->text('time_patterns')->nullable();

            // متوسط وقت الوصول
            $table->integer('avg_arrival_time')->nullable();

            // مستوى الخطورة (AI)
            $table->float('risk_score')->default(0);

            // نوع السلوك
            $table->enum('behavior_type', [
                'normal',
                'suspicious',
                'abnormal'
            ])->default('normal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('behavior_data');
    }
};