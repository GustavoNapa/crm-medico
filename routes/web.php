<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // WhatsApp Routes
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [WhatsAppController::class, 'index'])->name('index');
        Route::post('/create-instance', [WhatsAppController::class, 'createInstance'])->name('create-instance');
        Route::get('/qr-code', [WhatsAppController::class, 'getQrCode'])->name('qr-code');
        Route::get('/status', [WhatsAppController::class, 'getStatus'])->name('status');
        Route::post('/send-message', [WhatsAppController::class, 'sendMessage'])->name('send-message');
        Route::post('/send-media', [WhatsAppController::class, 'sendMedia'])->name('send-media');
        Route::get('/messages', [WhatsAppController::class, 'getMessages'])->name('messages');
        Route::post('/set-webhook', [WhatsAppController::class, 'setWebhook'])->name('set-webhook');
        Route::post('/check-whatsapp', [WhatsAppController::class, 'checkWhatsApp'])->name('check-whatsapp');
    });
    
    // CRM Routes
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/dashboard', function () {
            return view('crm.dashboard');
        })->name('dashboard');
        
        // Kanban Routes
        Route::get('/kanban', [App\Http\Controllers\KanbanController::class, 'index'])->name('kanban');
        Route::get('/kanban/data', [App\Http\Controllers\KanbanController::class, 'getData'])->name('kanban.data');
        Route::get('/kanban/stats', [App\Http\Controllers\KanbanController::class, 'getStats'])->name('kanban.stats');
        Route::post('/kanban/deals', [App\Http\Controllers\KanbanController::class, 'createDeal'])->name('kanban.deals.create');
        Route::put('/kanban/deals/{id}', [App\Http\Controllers\KanbanController::class, 'updateDeal'])->name('kanban.deals.update');
        Route::delete('/kanban/deals/{id}', [App\Http\Controllers\KanbanController::class, 'deleteDeal'])->name('kanban.deals.delete');
        Route::post('/kanban/move-deal', [App\Http\Controllers\KanbanController::class, 'moveDeal'])->name('kanban.move-deal');
        Route::post('/kanban/deals/{id}/win', [App\Http\Controllers\KanbanController::class, 'winDeal'])->name('kanban.deals.win');
        Route::post('/kanban/deals/{id}/lose', [App\Http\Controllers\KanbanController::class, 'loseDeal'])->name('kanban.deals.lose');
        
        Route::get('/contacts', function () {
            return view('crm.contacts');
        })->name('contacts');
        
        Route::get('/chat', function () {
            return view('crm.chat');
        })->name('chat');
        
        // History Routes
        Route::get('/history/contact/{id}', [App\Http\Controllers\HistoryController::class, 'getContactHistory'])->name('history.contact');
        Route::get('/history/deal/{id}', [App\Http\Controllers\HistoryController::class, 'getDealJourney'])->name('history.deal');
        Route::get('/history/contact/{id}/export', [App\Http\Controllers\HistoryController::class, 'exportContactHistory'])->name('history.export');
        Route::post('/history/activity', [App\Http\Controllers\HistoryController::class, 'createActivity'])->name('history.activity.create');
        Route::get('/history/stats', [App\Http\Controllers\HistoryController::class, 'getActivityStats'])->name('history.stats');
        Route::get('/history/recent', [App\Http\Controllers\HistoryController::class, 'getRecentActivities'])->name('history.recent');
        Route::get('/history/view/{contactId}', function ($contactId) {
            return view('crm.history', compact('contactId'));
        })->name('history.view');
        
        // Webhook Management Routes
        Route::get('/webhooks/mappings', [App\Http\Controllers\WebhookController::class, 'getMappings'])->name('webhooks.mappings');
        Route::post('/webhooks/mappings', [App\Http\Controllers\WebhookController::class, 'createMapping'])->name('webhooks.mappings.create');
        Route::put('/webhooks/mappings/{id}', [App\Http\Controllers\WebhookController::class, 'updateMapping'])->name('webhooks.mappings.update');
        Route::delete('/webhooks/mappings/{id}', [App\Http\Controllers\WebhookController::class, 'deleteMapping'])->name('webhooks.mappings.delete');
        Route::get('/webhooks/logs', [App\Http\Controllers\WebhookController::class, 'getLogs'])->name('webhooks.logs');
        Route::post('/webhooks/logs/{id}/reprocess', [App\Http\Controllers\WebhookController::class, 'reprocessWebhook'])->name('webhooks.logs.reprocess');
        Route::post('/webhooks/test', [App\Http\Controllers\WebhookController::class, 'testMapping'])->name('webhooks.test');
        Route::get('/webhooks/view', function () {
            return view('crm.webhooks');
        })->name('webhooks.view');
    });
});

// Webhook público (sem autenticação)
Route::post('/webhook/evolution', [App\Http\Controllers\WebhookController::class, 'receiveWebhook'])->defaults('source', 'evolution')->name('webhook.evolution');
Route::post('/webhook/{source}', [App\Http\Controllers\WebhookController::class, 'receiveWebhook'])->name('webhook.generic');
