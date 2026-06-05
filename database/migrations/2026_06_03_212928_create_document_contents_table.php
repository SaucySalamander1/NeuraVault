<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->integer('page_number');
            $table->longText('raw_text');
            $table->integer('word_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_contents');
    }
};