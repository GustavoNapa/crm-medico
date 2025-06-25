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
        Schema::create('whats_app_messages', function (Blueprint $table) {
            $table->id();
            $table->string('instance_name');
            $table->string('message_id')->unique();
            $table->string('from'); // Número do remetente
            $table->string('to')->nullable(); // Número do destinatário
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('direction', ['inbound', 'outbound']); // Recebida ou enviada
            $table->enum('message_type', ['text', 'image', 'audio', 'video', 'document', 'location', 'contact', 'sticker']);
            $table->text('content')->nullable(); // Conteúdo da mensagem
            $table->string('media_url')->nullable(); // URL da mídia
            $table->string('media_type')->nullable(); // Tipo MIME da mídia
            $table->string('caption')->nullable(); // Legenda da mídia
            $table->json('metadata')->nullable(); // Dados adicionais da mensagem
            $table->timestamp('message_timestamp');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->boolean('is_sent')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['contact_id']);
            $table->index(['from']);
            $table->index(['direction']);
            $table->index(['message_type']);
            $table->index(['message_timestamp']);
            $table->index(['instance_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_messages');
    }
};

