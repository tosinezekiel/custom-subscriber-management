<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_id')->constrained()->onDelete('cascade');
            $table->text('value');
            $table->timestamps();

            $table->unique(['subscriber_id', 'field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_values');
    }
};
