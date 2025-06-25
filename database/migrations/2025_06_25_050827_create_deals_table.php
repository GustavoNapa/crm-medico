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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('pipeline_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Responsável
            $table->decimal('value', 10, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            $table->enum('status', ['open', 'won', 'lost'])->default('open');
            $table->text('lost_reason')->nullable();
            $table->integer('position')->default(0); // Posição no pipeline
            $table->json('custom_fields')->nullable(); // Campos customizados
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
            
            $table->index(['pipeline_id', 'position']);
            $table->index(['contact_id']);
            $table->index(['user_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};

