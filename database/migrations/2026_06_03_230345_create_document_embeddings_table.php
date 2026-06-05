<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('chunk_id')
                  ->constrained('document_chunks')
                  ->cascadeOnDelete();
            $table->json('embedding');
            $table->string('model')->default('sentence-transformers/all-MiniLM-L6-v2');
            $table->integer('dimensions')->default(384);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_embeddings');
    }
};