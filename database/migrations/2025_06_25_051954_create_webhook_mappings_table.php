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
        Schema::create('webhook_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do mapeamento
            $table->string('event_type'); // Tipo de evento (message, status, etc.)
            $table->string('source'); // Origem (evolution, external, etc.)
            $table->string('target_model'); // Model de destino (Contact, Deal, etc.)
            $table->string('target_field'); // Campo de destino
            $table->string('webhook_field'); // Campo do webhook
            $table->string('field_type')->default('string'); // Tipo do campo
            $table->json('conditions')->nullable(); // Condições para aplicar o mapeamento
            $table->json('transformations')->nullable(); // Transformações a aplicar
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['event_type']);
            $table->index(['source']);
            $table->index(['target_model']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_mappings');
    }
};

