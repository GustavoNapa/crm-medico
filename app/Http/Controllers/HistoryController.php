<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Obter histórico completo de um contato
     */
    public function getContactHistory($contactId)
    {
        $contact = Contact::with(['deals.pipeline', 'deals.user'])->findOrFail($contactId);
        
        // Atividades do contato
        $activities = Activity::forContact($contactId)
            ->with(['user', 'deal'])
            ->ordered()
            ->get();

        // Mensagens WhatsApp
        $whatsappMessages = WhatsAppMessage::forContact($contactId)
            ->ordered()
            ->get();

        // Deals do contato
        $deals = $contact->deals()->with(['pipeline', 'user'])->get();

        // Combinar e ordenar timeline
        $timeline = collect();

        // Adicionar atividades
        foreach ($activities as $activity) {
            $timeline->push([
                'type' => 'activity',
                'date' => $activity->activity_date,
                'data' => $activity,
                'icon' => $activity->icon,
                'color' => $activity->color,
                'title' => $activity->title,
                'description' => $activity->description,
                'user' => $activity->user->name ?? 'Sistema'
            ]);
        }

        // Adicionar mensagens WhatsApp
        foreach ($whatsappMessages as $message) {
            $timeline->push([
                'type' => 'whatsapp',
                'date' => $message->message_timestamp,
                'data' => $message,
                'icon' => 'fab fa-whatsapp',
                'color' => 'success',
                'title' => $message->isInbound() ? 'Mensagem recebida' : 'Mensagem enviada',
                'description' => $message->content,
                'user' => $message->isInbound() ? $contact->name : 'Atendente'
            ]);
        }

        // Adicionar marcos dos deals
        foreach ($deals as $deal) {
            $timeline->push([
                'type' => 'deal_created',
                'date' => $deal->created_at,
                'data' => $deal,
                'icon' => 'fas fa-plus-circle',
                'color' => 'primary',
                'title' => 'Negócio criado: ' . $deal->title,
                'description' => 'Valor: ' . $deal->formatted_value,
                'user' => $deal->user->name ?? 'Sistema'
            ]);

            if ($deal->status === 'won') {
                $timeline->push([
                    'type' => 'deal_won',
                    'date' => $deal->actual_close_date,
                    'data' => $deal,
                    'icon' => 'fas fa-trophy',
                    'color' => 'success',
                    'title' => 'Negócio ganho: ' . $deal->title,
                    'description' => 'Valor: ' . $deal->formatted_value,
                    'user' => $deal->user->name ?? 'Sistema'
                ]);
            } elseif ($deal->status === 'lost') {
                $timeline->push([
                    'type' => 'deal_lost',
                    'date' => $deal->actual_close_date,
                    'data' => $deal,
                    'icon' => 'fas fa-times-circle',
                    'color' => 'danger',
                    'title' => 'Negócio perdido: ' . $deal->title,
                    'description' => $deal->lost_reason ?? 'Sem motivo especificado',
                    'user' => $deal->user->name ?? 'Sistema'
                ]);
            }
        }

        // Ordenar timeline por data (mais recente primeiro)
        $timeline = $timeline->sortByDesc('date')->values();

        return response()->json([
            'success' => true,
            'contact' => $contact,
            'timeline' => $timeline,
            'stats' => [
                'total_activities' => $activities->count(),
                'total_messages' => $whatsappMessages->count(),
                'total_deals' => $deals->count(),
                'won_deals' => $deals->where('status', 'won')->count(),
                'lost_deals' => $deals->where('status', 'lost')->count(),
                'total_value' => $deals->sum('value'),
                'won_value' => $deals->where('status', 'won')->sum('value')
            ]
        ]);
    }

    /**
     * Obter jornada de um deal específico
     */
    public function getDealJourney($dealId)
    {
        $deal = Deal::with(['contact', 'pipeline', 'user'])->findOrFail($dealId);
        
        // Atividades do deal
        $activities = Activity::forDeal($dealId)
            ->with(['user'])
            ->ordered()
            ->get();

        // Mensagens relacionadas ao contato do deal
        $whatsappMessages = WhatsAppMessage::forContact($deal->contact_id)
            ->where('message_timestamp', '>=', $deal->created_at)
            ->ordered()
            ->get();

        // Construir jornada
        $journey = collect();

        // Criação do deal
        $journey->push([
            'type' => 'deal_created',
            'date' => $deal->created_at,
            'icon' => 'fas fa-plus-circle',
            'color' => 'primary',
            'title' => 'Negócio criado',
            'description' => 'Pipeline: ' . $deal->pipeline->name . ' | Valor: ' . $deal->formatted_value,
            'user' => $deal->user->name
        ]);

        // Adicionar atividades
        foreach ($activities as $activity) {
            $journey->push([
                'type' => 'activity',
                'date' => $activity->activity_date,
                'icon' => $activity->icon,
                'color' => $activity->color,
                'title' => $activity->title,
                'description' => $activity->description,
                'user' => $activity->user->name ?? 'Sistema'
            ]);
        }

        // Adicionar mensagens WhatsApp relevantes
        foreach ($whatsappMessages as $message) {
            $journey->push([
                'type' => 'whatsapp',
                'date' => $message->message_timestamp,
                'icon' => 'fab fa-whatsapp',
                'color' => 'success',
                'title' => $message->isInbound() ? 'Mensagem recebida' : 'Mensagem enviada',
                'description' => $message->content,
                'user' => $message->isInbound() ? $deal->contact->name : 'Atendente'
            ]);
        }

        // Status final do deal
        if ($deal->status === 'won') {
            $journey->push([
                'type' => 'deal_won',
                'date' => $deal->actual_close_date,
                'icon' => 'fas fa-trophy',
                'color' => 'success',
                'title' => 'Negócio ganho',
                'description' => 'Valor final: ' . $deal->formatted_value,
                'user' => $deal->user->name
            ]);
        } elseif ($deal->status === 'lost') {
            $journey->push([
                'type' => 'deal_lost',
                'date' => $deal->actual_close_date,
                'icon' => 'fas fa-times-circle',
                'color' => 'danger',
                'title' => 'Negócio perdido',
                'description' => $deal->lost_reason ?? 'Sem motivo especificado',
                'user' => $deal->user->name
            ]);
        }

        // Ordenar jornada por data
        $journey = $journey->sortBy('date')->values();

        return response()->json([
            'success' => true,
            'deal' => $deal,
            'journey' => $journey,
            'stats' => [
                'duration_days' => $deal->created_at->diffInDays($deal->actual_close_date ?? now()),
                'total_activities' => $activities->count(),
                'total_messages' => $whatsappMessages->count(),
                'pipeline_changes' => $activities->where('type', 'deal_moved')->count()
            ]
        ]);
    }

    /**
     * Criar nova atividade
     */
    public function createActivity(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
            'activity_date' => 'nullable|date',
            'duration' => 'nullable|integer|min:0',
            'outcome' => 'nullable|string'
        ]);

        try {
            $activity = Activity::create([
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'contact_id' => $request->contact_id,
                'deal_id' => $request->deal_id,
                'user_id' => Auth::id(),
                'activity_date' => $request->activity_date ?? now(),
                'duration' => $request->duration,
                'outcome' => $request->outcome
            ]);

            // Atualizar último contato se for atividade de contato
            if ($request->contact_id) {
                Contact::find($request->contact_id)->update([
                    'last_contact' => $activity->activity_date
                ]);
            }

            // Atualizar última atividade do deal
            if ($request->deal_id) {
                Deal::find($request->deal_id)->update([
                    'last_activity' => $activity->activity_date
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Atividade criada com sucesso',
                'activity' => $activity->load(['user', 'contact', 'deal'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar atividade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter estatísticas de atividades
     */
    public function getActivityStats(Request $request)
    {
        $period = $request->input('period', 30); // dias
        $startDate = now()->subDays($period);

        $stats = [
            'total_activities' => Activity::where('activity_date', '>=', $startDate)->count(),
            'activities_by_type' => Activity::where('activity_date', '>=', $startDate)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
            'activities_by_user' => Activity::where('activity_date', '>=', $startDate)
                ->with('user')
                ->get()
                ->groupBy('user.name')
                ->map(function ($activities) {
                    return $activities->count();
                }),
            'daily_activities' => Activity::where('activity_date', '>=', $startDate)
                ->selectRaw('DATE(activity_date) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date'),
            'avg_duration' => Activity::where('activity_date', '>=', $startDate)
                ->whereNotNull('duration')
                ->avg('duration'),
            'total_duration' => Activity::where('activity_date', '>=', $startDate)
                ->sum('duration')
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'period' => $period
        ]);
    }

    /**
     * Obter atividades recentes
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->input('limit', 20);
        $contactId = $request->input('contact_id');
        $dealId = $request->input('deal_id');

        $query = Activity::with(['user', 'contact', 'deal'])->ordered();

        if ($contactId) {
            $query->forContact($contactId);
        }

        if ($dealId) {
            $query->forDeal($dealId);
        }

        $activities = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }

    /**
     * Exportar histórico de contato
     */
    public function exportContactHistory($contactId, Request $request)
    {
        $format = $request->input('format', 'json'); // json, csv, pdf
        
        $contact = Contact::findOrFail($contactId);
        $historyData = $this->getContactHistory($contactId);

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($contact, $historyData->getData());
            case 'pdf':
                return $this->exportToPdf($contact, $historyData->getData());
            default:
                return $historyData;
        }
    }

    /**
     * Exportar para CSV
     */
    private function exportToCsv($contact, $data)
    {
        $filename = 'historico_' . $contact->name . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, ['Data', 'Tipo', 'Título', 'Descrição', 'Usuário']);
            
            // Dados
            foreach ($data->timeline as $item) {
                fputcsv($file, [
                    $item['date'],
                    $item['type'],
                    $item['title'],
                    $item['description'],
                    $item['user']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar para PDF
     */
    private function exportToPdf($contact, $data)
    {
        // Implementação básica - pode ser expandida com uma biblioteca de PDF
        return response()->json([
            'success' => false,
            'message' => 'Exportação para PDF não implementada ainda'
        ]);
    }
}

