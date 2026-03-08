<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // SRQ-2024-00001
            $table->foreignId('citizen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'in_review',
                'missing_documents',
                'approved',
                'rejected',
                'completed'
            ])->default('pending');
            $table->text('notes')->nullable();           // citizen notes
            $table->text('office_notes')->nullable();    // internal office notes
            $table->string('qr_code')->nullable();       // path to QR code image
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('payment_method')->nullable(); // card, crypto
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, refunded
            $table->string('transaction_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('document_type')->nullable(); // e.g. "national_id", "proof_of_residence"
            $table->enum('uploaded_by', ['citizen', 'office'])->default('citizen');
            $table->timestamps();
        });

        Schema::create('request_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('changed_by')->constrained('users');
            $table->string('from_status');
            $table->string('to_status');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_status_logs');
        Schema::dropIfExists('request_documents');
        Schema::dropIfExists('service_requests');
    }
};
