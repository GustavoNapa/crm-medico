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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // Origem do webhook
            $table->string('event_type'); // Tipo de evento
            $table->string('webhook_id')->nullable(); // ID do webhook se disponível
            $table->json('payload'); // Dados recebidos
            $table->json('headers')->nullable(); // Headers da requisição
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->json('processed_data')->nullable(); // Dados após processamento
            $table->json('applied_mappings')->nullable(); // Mapeamentos aplicados
            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamps();
            
            $table->index(['source']);
            $table->index(['event_type']);
            $table->index(['status']);
            $table->index(['received_at']);
            $table->index(['webhook_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};

