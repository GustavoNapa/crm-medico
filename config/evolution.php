<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Evolution API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com Evolution API WhatsApp
    |
    */

    'base_url' => env('EVOLUTION_API_BASE_URL', 'http://localhost:8080'),
    'api_key' => env('EVOLUTION_API_KEY', ''),
    'instance_name' => env('EVOLUTION_INSTANCE_NAME', 'crm-medico'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | URL para receber webhooks da Evolution API
    |
    */
    'webhook_url' => env('EVOLUTION_WEBHOOK_URL', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Message Settings
    |--------------------------------------------------------------------------
    |
    | Configurações para envio de mensagens
    |
    */
    'delay_message' => env('EVOLUTION_DELAY_MESSAGE', 1000),
    'retry_count' => env('EVOLUTION_RETRY_COUNT', 3),
    'mark_messages_read' => env('EVOLUTION_MARK_READ', true),
    'always_online' => env('EVOLUTION_ALWAYS_ONLINE', true),
    'presence' => env('EVOLUTION_PRESENCE', 'available'),
];

