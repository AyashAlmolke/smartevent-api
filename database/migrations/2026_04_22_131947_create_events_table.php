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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('event_name');
            $table->string('location');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->text('description')->nullable();

            // من أنشأ الفعالية
            $table->foreignId('admin_id')
                ->constrained('admins')
                ->onDelete('cascade');

            // حالة الفعالية
            $table->enum('status', ['draft', 'active', 'finished'])->default('draft');

            $table->integer('max_attendees')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};