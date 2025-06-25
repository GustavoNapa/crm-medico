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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // call, email, whatsapp, meeting, note, deal_created, deal_moved, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->morphs('subject'); // Pode ser Deal, Contact, etc.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('deal_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('metadata')->nullable(); // Dados adicionais específicos do tipo
            $table->timestamp('activity_date');
            $table->integer('duration')->nullable(); // Duração em minutos
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->text('outcome')->nullable(); // Resultado da atividade
            $table->timestamps();
            
            $table->index(['contact_id']);
            $table->index(['deal_id']);
            $table->index(['user_id']);
            $table->index(['type']);
            $table->index(['activity_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

