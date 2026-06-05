<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['person', 'company', 'location', 'concept', 'other']);
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'name', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};