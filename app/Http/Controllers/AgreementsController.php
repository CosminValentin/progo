<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use Illuminate\Http\Request;

class AgreementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // CREAR
    public function store(Request $request)
    {
        if ($request->expectsJson() === false && $request->header('Accept') === 'application/json') {
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }

        $validated = $request->validate([
            'company_id'      => ['nullable','integer','exists:companies,id'],
            'fecha_firma'     => ['required','date'],
            'firmado_agencia' => ['nullable','boolean'],
            'firmado_empresa' => ['nullable','boolean'],
            'validez_desde'   => ['nullable','date'],
            'validez_hasta'   => ['nullable','date','after_or_equal:validez_desde'],
            'pdf_doc_id'      => ['nullable','integer','exists:documents,id'],
        ]);

        $validated['firmado_agencia'] = $request->boolean('firmado_agencia');
        $validated['firmado_empresa'] = $request->boolean('firmado_empresa');

        $agreement = Agreement::create($validated)->load('company');

        return response()->json([
            'id'              => $agreement->id,
            'company_id'      => $agreement->company_id,
            'company_nombre'  => optional($agreement->company)->nombre,
            'fecha_firma'     => optional($agreement->fecha_firma)->format('Y-m-d'),
            'fecha_firma_hum' => optional($agreement->fecha_firma)->format('d/m/Y'),
            'validez_desde'   => optional($agreement->validez_desde)->format('Y-m-d'),
            'validez_hasta'   => optional($agreement->validez_hasta)->format('Y-m-d'),
            'firmado_agencia' => $agreement->firmado_agencia,
            'firmado_empresa' => $agreement->firmado_empresa,
            'pdf_doc_id'      => $agreement->pdf_doc_id,
            'update_url'      => route('agreements.update', $agreement),
            'delete_url'      => route('agreements.destroy', $agreement),
        ], 201);
    }

    // ACTUALIZAR
    public function update(Request $request, Agreement $agreement)
    {
        if ($request->expectsJson() === false && $request->header('Accept') === 'application/json') {
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }

        $validated = $request->validate([
            'company_id'      => ['nullable','integer','exists:companies,id'],
            'fecha_firma'     => ['required','date'],
            'firmado_agencia' => ['nullable','boolean'],
            'firmado_empresa' => ['nullable','boolean'],
            'validez_desde'   => ['nullable','date'],
            'validez_hasta'   => ['nullable','date','after_or_equal:validez_desde'],
            'pdf_doc_id'      => ['nullable','integer','exists:documents,id'],
        ]);

        $validated['firmado_agencia'] = $request->boolean('firmado_agencia');
        $validated['firmado_empresa'] = $request->boolean('firmado_empresa');

        $agreement->update($validated);
        $agreement->load('company');

        return response()->json([
            'id'              => $agreement->id,
            'company_id'      => $agreement->company_id,
            'company_nombre'  => optional($agreement->company)->nombre,
            'fecha_firma'     => optional($agreement->fecha_firma)->format('Y-m-d'),
            'fecha_firma_hum' => optional($agreement->fecha_firma)->format('d/m/Y'),
            'validez_desde'   => optional($agreement->validez_desde)->format('Y-m-d'),
            'validez_hasta'   => optional($agreement->validez_hasta)->format('Y-m-d'),
            'firmado_agencia' => $agreement->firmado_agencia,
            'firmado_empresa' => $agreement->firmado_empresa,
            'pdf_doc_id'      => $agreement->pdf_doc_id,
            'update_url'      => route('agreements.update', $agreement),
            'delete_url'      => route('agreements.destroy', $agreement),
        ]);
    }

    // ELIMINAR
    public function destroy(Request $request, Agreement $agreement)
    {
        $agreement->delete();
        return response()->noContent();
    }
}
