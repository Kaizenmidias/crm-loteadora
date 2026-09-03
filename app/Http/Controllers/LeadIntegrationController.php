<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadIntegrationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Lead::query()->with('broker:id,name')->latest()->get()->map(fn (Lead $lead) => $this->present($lead))->values()
        );
    }

    public function store(Request $request)
    {
        $token = (string) config('services.crm.site_token');
        $provided = (string) $request->header('X-CRM-TOKEN');
        if ($token !== '' && $provided !== '' && hash_equals($token, $provided)) {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:150'],
                'phone' => ['nullable', 'string', 'max:25'],
                'email' => ['nullable', 'email', 'max:150'],
                'development' => ['nullable', 'string', 'max:150'],
                'source' => ['nullable', 'string', 'max:50'],
                'source_url' => ['nullable', 'url', 'max:500'],
                'utm_source' => ['nullable', 'string', 'max:100'],
                'utm_medium' => ['nullable', 'string', 'max:100'],
                'utm_campaign' => ['nullable', 'string', 'max:100'],
                'utm_content' => ['nullable', 'string', 'max:100'],
                'utm_term' => ['nullable', 'string', 'max:100'],
                'stage' => ['nullable', 'in:new,in_service,interested,visit,reservation,sale,lost'],
                'notes' => ['nullable', 'string'],
            ]);

            $lead = Lead::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'source' => $data['source'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'utm_source' => $data['utm_source'] ?? null,
                'utm_medium' => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
                'utm_content' => $data['utm_content'] ?? null,
                'utm_term' => $data['utm_term'] ?? null,
                'stage' => $data['stage'] ?? 'new',
                'notes' => $data['notes'] ?? ($data['development'] ?? null),
            ]);

            return response()->json($this->present($lead), 201);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'], 'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'], 'development' => ['nullable', 'string', 'max:150'],
            'source' => ['nullable', 'string', 'max:50'], 'source_url' => ['nullable', 'url', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:100'], 'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'], 'utm_content' => ['nullable', 'string', 'max:100'], 'utm_term' => ['nullable', 'string', 'max:100'],
        ]);
        $lead = Lead::create(collect($data)->except('development')->all());
        return response()->json($this->present($lead), 201);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'],
            'source' => ['nullable', 'string', 'max:50'],
            'stage' => ['required', 'in:new,in_service,interested,visit,reservation,sale,lost'],
            'notes' => ['nullable', 'string'],
            'broker_id' => ['nullable', 'integer', 'exists:brokers,id'],
        ]);

        $lead->fill($data);
        $lead->save();

        return response()->json($this->present($lead->fresh()));
    }

    private function present(Lead $lead): array
    {
        $stageLabel = [
            'new' => 'Novo Lead',
            'in_service' => 'Em Atendimento',
            'interested' => 'Interessado',
            'visit' => 'Visita Agendada',
            'reservation' => 'Reserva',
            'sale' => 'Venda',
            'lost' => 'Perdido',
        ][$lead->stage] ?? $lead->stage;

        return [
            'id' => $lead->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'contact' => $lead->phone,
            'email' => $lead->email,
            'source' => $lead->source,
            'source_url' => $lead->source_url,
            'utm_source' => $lead->utm_source,
            'utm_medium' => $lead->utm_medium,
            'utm_campaign' => $lead->utm_campaign,
            'utm_content' => $lead->utm_content,
            'utm_term' => $lead->utm_term,
            'stage' => $lead->stage,
            'stage_label' => $stageLabel,
            'date' => $lead->created_at?->format('d/m H:i') ?? now()->format('d/m H:i'),
            'notes' => $lead->notes,
            'broker_id' => $lead->broker_id,
            'broker' => $lead->broker?->name,
        ];
    }
}
