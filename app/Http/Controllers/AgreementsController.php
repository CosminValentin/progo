<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;

class AgreementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO
    public function index(Request $request)
    {
        $agreements = Agreement::with(['company', 'pdf'])
            ->orderByDesc('fecha_firma')
            ->orderByDesc('id')
            ->paginate(10);

        $total = Agreement::count();
        // vigentes: filtro por fechas
        $vigentes = Agreement::query()
            ->where(function ($q) {
                $q->whereNull('validez_desde')->orWhere('validez_desde', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('validez_hasta')->orWhere('validez_hasta', '>=', now());
            })
            ->count();

        return view('agreements.index', compact('agreements', 'total', 'vigentes'));
    }

    // NUEVO
    public function create()
    {
        $companies = Company::orderBy('nombre')->get(['id','nombre']);
        // documentos opcionales (podrías filtrar por tipo 'acuerdo' si lo usas)
        $documents = Document::orderByDesc('fecha')->limit(200)->get(['id','nombre_archivo','tipo']);
        return view('agreements.create', compact('companies','documents'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'      => ['required', 'exists:companies,id'],
            'fecha_firma'     => ['required', 'date'],
            'firmado_agencia' => ['nullable', 'boolean'],
            'firmado_empresa' => ['nullable', 'boolean'],
            'validez_desde'   => ['nullable', 'date'],
            'validez_hasta'   => ['nullable', 'date', 'after_or_equal:validez_desde'],
            'pdf_doc_id'      => ['nullable', 'exists:documents,id'],
        ]);

        $validated['firmado_agencia'] = (bool)($validated['firmado_agencia'] ?? 0);
        $validated['firmado_empresa'] = (bool)($validated['firmado_empresa'] ?? 0);

        Agreement::create($validated);

        return redirect()->route('agreements.index')->with('success', 'Convenio creado correctamente.');
    }

    // VER
    public function show(Agreement $agreement)
    {
        $agreement->load(['company','pdf']);
        return view('agreements.view', compact('agreement'));
    }

    // EDITAR
    public function edit(Agreement $agreement)
    {
        $agreement->load(['company','pdf']);
        $companies = Company::orderBy('nombre')->get(['id','nombre']);
        $documents = Document::orderByDesc('fecha')->limit(200)->get(['id','nombre_archivo','tipo']);
        return view('agreements.edit', compact('agreement','companies','documents'));
    }

    // ACTUALIZAR
    public function update(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'company_id'      => ['required', 'exists:companies,id'],
            'fecha_firma'     => ['required', 'date'],
            'firmado_agencia' => ['nullable', 'boolean'],
            'firmado_empresa' => ['nullable', 'boolean'],
            'validez_desde'   => ['nullable', 'date'],
            'validez_hasta'   => ['nullable', 'date', 'after_or_equal:validez_desde'],
            'pdf_doc_id'      => ['nullable', 'exists:documents,id'],
        ]);

        $validated['firmado_agencia'] = (bool)($validated['firmado_agencia'] ?? 0);
        $validated['firmado_empresa'] = (bool)($validated['firmado_empresa'] ?? 0);

        $agreement->update($validated);

        return redirect()->route('agreements.index')->with('success', 'Convenio actualizado correctamente.');
    }

    // BORRAR
    public function destroy(Agreement $agreement)
    {
        try {
            $agreement->delete();
            return redirect()->route('agreements.index')->with('success', 'Convenio eliminado.');
        } catch (\Throwable $e) {
            return redirect()->route('agreements.index')->with('error', 'No se pudo eliminar el convenio.');
        }
    }
}
