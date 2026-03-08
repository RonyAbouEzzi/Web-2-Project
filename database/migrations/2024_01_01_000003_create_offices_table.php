<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('working_hours')->nullable(); // {"mon": "08:00-16:00", ...}
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // pivot: office_user (office_users → offices)
        Schema::create('office_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('staff'); // manager, staff
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_users');
        Schema::dropIfExists('offices');
    }
};
