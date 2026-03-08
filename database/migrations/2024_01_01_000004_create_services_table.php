<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->integer('estimated_duration_days')->default(1);
            $table->json('required_documents')->nullable(); // ["ID copy", "Proof of residence", ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
