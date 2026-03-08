<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable(); // nullable for social login
            $table->enum('role', ['admin', 'office_user', 'citizen'])->default('citizen');
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();
            $table->string('id_document')->nullable(); // uploaded ID path
            $table->boolean('is_active')->default(true);
            $table->string('two_factor_secret')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('social_provider')->nullable(); // google, facebook, etc.
            $table->string('social_id')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
