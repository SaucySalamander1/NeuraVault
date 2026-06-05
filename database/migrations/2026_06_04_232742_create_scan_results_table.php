<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->enum('finding_type', [
                'email',
                'phone',
                'ssn',
                'credit_card',
                'api_key',
                'password',
                'url',
                'other'
            ]);
            $table->text('value_masked');
            $table->string('location')->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_results');
    }
};