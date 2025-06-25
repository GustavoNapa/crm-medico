<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\Deal;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    /**
     * Exibir o Kanban
     */
    public function index()
    {
        $pipelines = Pipeline::active()
            ->ordered()
            ->with(['deals' => function($query) {
                $query->open()->ordered()->with(['contact', 'user']);
            }])
            ->get();

        $totalValue = Deal::open()->sum('value');
        $totalDeals = Deal::open()->count();
        $wonDeals = Deal::won()->whereMonth('actual_close_date', now()->month)->count();
        $lostDeals = Deal::lost()->whereMonth('actual_close_date', now()->month)->count();

        return view('crm.kanban', compact('pipelines', 'totalValue', 'totalDeals', 'wonDeals', 'lostDeals'));
    }

    /**
     * Obter dados do Kanban via AJAX
     */
    public function getData()
    {
        $pipelines = Pipeline::active()
            ->ordered()
            ->with(['deals' => function($query) {
                $query->open()->ordered()->with(['contact', 'user']);
            }])
            ->get();

        return response()->json([
            'success' => true,
            'pipelines' => $pipelines->map(function($pipeline) {
                return [
                    'id' => $pipeline->id,
                    'name' => $pipeline->name,
                    'color' => $pipeline->color,
                    'total_value' => $pipeline->total_value,
                    'deals_count' => $pipeline->deals_count,
                    'deals' => $pipeline->deals->map(function($deal) {
                        return [
                            'id' => $deal->id,
                            'title' => $deal->title,
                            'value' => $deal->formatted_value,
                            'contact' => [
                                'id' => $deal->contact->id,
                                'name' => $deal->contact->name,
                                'initials' => $deal->contact->initials,
                                'phone' => $deal->contact->formatted_phone,
                                'whatsapp' => $deal->contact->formatted_whatsapp,
                            ],
                            'user' => [
                                'id' => $deal->user->id,
                                'name' => $deal->user->name,
                            ],
                            'expected_close_date' => $deal->expected_close_date?->format('d/m/Y'),
                            'is_overdue' => $deal->is_overdue,
                            'days_to_close' => $deal->days_to_close,
                            'last_activity' => $deal->last_activity_formatted,
                        ];
                    })
                ];
            })
        ]);
    }

    /**
     * Criar novo deal
     */
    public function createDeal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'contact_id' => 'required|exists:contacts,id',
            'pipeline_id' => 'required|exists:pipelines,id',
            'value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Obter próxima posição no pipeline
            $maxPosition = Deal::where('pipeline_id', $request->pipeline_id)
                ->where('status', 'open')
                ->max('position');

            $deal = Deal::create([
                'title' => $request->title,
                'description' => $request->description,
                'contact_id' => $request->contact_id,
                'pipeline_id' => $request->pipeline_id,
                'user_id' => Auth::id(),
                'value' => $request->value,
                'expected_close_date' => $request->expected_close_date,
                'position' => ($maxPosition ?? 0) + 1,
                'last_activity' => now()
            ]);

            // Atualizar último contato
            $deal->contact->update(['last_contact' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Negócio criado com sucesso',
                'deal' => $deal->load(['contact', 'user', 'pipeline'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar negócio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mover deal entre pipelines
     */
    public function moveDeal(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'pipeline_id' => 'required|exists:pipelines,id',
            'position' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $deal = Deal::findOrFail($request->deal_id);
            $oldPipelineId = $deal->pipeline_id;
            $oldPosition = $deal->position;

            // Se mudou de pipeline
            if ($oldPipelineId != $request->pipeline_id) {
                // Reorganizar posições no pipeline antigo
                Deal::where('pipeline_id', $oldPipelineId)
                    ->where('position', '>', $oldPosition)
                    ->where('status', 'open')
                    ->decrement('position');

                // Reorganizar posições no novo pipeline
                Deal::where('pipeline_id', $request->pipeline_id)
                    ->where('position', '>=', $request->position)
                    ->where('status', 'open')
                    ->increment('position');
            } else {
                // Mesmo pipeline, apenas reordenar
                if ($request->position < $oldPosition) {
                    // Movendo para cima
                    Deal::where('pipeline_id', $request->pipeline_id)
                        ->where('position', '>=', $request->position)
                        ->where('position', '<', $oldPosition)
                        ->where('status', 'open')
                        ->increment('position');
                } else {
                    // Movendo para baixo
                    Deal::where('pipeline_id', $request->pipeline_id)
                        ->where('position', '>', $oldPosition)
                        ->where('position', '<=', $request->position)
                        ->where('status', 'open')
                        ->decrement('position');
                }
            }

            // Atualizar o deal
            $deal->update([
                'pipeline_id' => $request->pipeline_id,
                'position' => $request->position,
                'last_activity' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Negócio movido com sucesso'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao mover negócio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar deal
     */
    public function updateDeal(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string'
        ]);

        try {
            $deal = Deal::findOrFail($id);
            
            $deal->update([
                'title' => $request->title,
                'description' => $request->description,
                'value' => $request->value,
                'expected_close_date' => $request->expected_close_date,
                'last_activity' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Negócio atualizado com sucesso',
                'deal' => $deal->load(['contact', 'user', 'pipeline'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar negócio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar deal como ganho
     */
    public function winDeal(Request $request, $id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->markAsWon();

            return response()->json([
                'success' => true,
                'message' => 'Negócio marcado como ganho!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar negócio como ganho: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar deal como perdido
     */
    public function loseDeal(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $deal = Deal::findOrFail($id);
            $deal->markAsLost($request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Negócio marcado como perdido'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar negócio como perdido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Excluir deal
     */
    public function deleteDeal($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Negócio excluído com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir negócio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter estatísticas do funil
     */
    public function getStats()
    {
        $stats = [
            'total_value' => Deal::open()->sum('value'),
            'total_deals' => Deal::open()->count(),
            'won_deals_month' => Deal::won()->whereMonth('actual_close_date', now()->month)->count(),
            'lost_deals_month' => Deal::lost()->whereMonth('actual_close_date', now()->month)->count(),
            'conversion_rate' => 0,
            'average_deal_value' => 0,
            'pipeline_stats' => []
        ];

        // Taxa de conversão
        $totalClosedThisMonth = $stats['won_deals_month'] + $stats['lost_deals_month'];
        if ($totalClosedThisMonth > 0) {
            $stats['conversion_rate'] = round(($stats['won_deals_month'] / $totalClosedThisMonth) * 100, 2);
        }

        // Valor médio dos deals
        if ($stats['total_deals'] > 0) {
            $stats['average_deal_value'] = $stats['total_value'] / $stats['total_deals'];
        }

        // Estatísticas por pipeline
        $pipelines = Pipeline::active()->with(['deals' => function($query) {
            $query->open();
        }])->get();

        foreach ($pipelines as $pipeline) {
            $stats['pipeline_stats'][] = [
                'name' => $pipeline->name,
                'color' => $pipeline->color,
                'deals_count' => $pipeline->deals->count(),
                'total_value' => $pipeline->deals->sum('value')
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}

