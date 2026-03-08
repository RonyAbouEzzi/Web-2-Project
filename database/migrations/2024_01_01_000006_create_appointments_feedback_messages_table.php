<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_request_id')->nullable()->constrained()->onDelete('set null');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['scheduled', 'confirmed', 'cancelled', 'completed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Feedback / Reviews
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_request_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->text('office_reply')->nullable();
            $table->boolean('reply_is_public')->default(true);
            $table->timestamps();
        });

        // In-app messages / chat
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users');
            $table->text('body');
            $table->string('attachment')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('appointments');
    }
};
