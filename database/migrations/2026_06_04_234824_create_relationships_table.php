<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('entity_a_id')
                  ->constrained('entities')
                  ->cascadeOnDelete();
            $table->foreignId('entity_b_id')
                  ->constrained('entities')
                  ->cascadeOnDelete();
            $table->string('relationship_type');
            $table->integer('strength')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};