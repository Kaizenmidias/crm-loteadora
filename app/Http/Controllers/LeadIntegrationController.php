<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadIntegrationController extends Controller
{
    public function store(Request $request)
    {
        $token = (string) config('services.crm.site_token');
        $provided = (string) $request->header('X-CRM-TOKEN');
        abort_unless($token !== '' && $provided !== '' && hash_equals($token, $provided), 401);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'], 'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'], 'development' => ['nullable', 'string', 'max:150'],
            'source' => ['nullable', 'string', 'max:50'], 'source_url' => ['nullable', 'url', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:100'], 'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'], 'utm_content' => ['nullable', 'string', 'max:100'], 'utm_term' => ['nullable', 'string', 'max:100'],
        ]);
        $lead = Lead::create(collect($data)->except('development')->all());
        return response()->json($lead, 201);
    }
}
