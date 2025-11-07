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

    // LISTAR
    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q'));
        $total   = Agreement::count();
        $hoy     = now()->startOfDay();
        $vigentes = Agreement::query()
            ->where(fn($qb) => $qb->whereNull('validez_desde')->orWhereDate('validez_desde', '<=', $hoy))
            ->where(fn($qb) => $qb->whereNull('validez_hasta')->orWhereDate('validez_hasta', '>=', $hoy))
            ->count();

        $agreements = Agreement::query()
            ->with(['company','pdf'])
            ->when($q, fn($qb) => $qb->whereHas('company', fn($c) => $c->where('nombre','like',"%{$q}%")))
            ->orderByDesc('fecha_firma')->orderByDesc('id')
            ->paginate(15)->withQueryString();

        return view('agreements.index', compact('agreements','total','vigentes'));
    }

    // FORM CREAR
    public function create()
    {
        $agreement = new Agreement();
        $companies = Company::orderBy('nombre')->get();
        $documents = Document::orderByDesc('fecha')->get();

        return view('agreements.create', compact('agreement','companies','documents'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'company_id'      => ['required','integer','exists:companies,id'],
        'fecha_firma'     => ['required','date'],
        'firmado_agencia' => ['nullable','boolean'],
        'firmado_empresa' => ['nullable','boolean'],
        'validez_desde'   => ['nullable','date'],
        'validez_hasta'   => ['nullable','date','after_or_equal:validez_desde'],
        'pdf_doc_id'      => ['nullable','integer','exists:documents,id'],
    ]);

    $validated['firmado_agencia'] = $request->boolean('firmado_agencia');
    $validated['firmado_empresa'] = $request->boolean('firmado_empresa');

    $agreement = \App\Models\Agreement::create($validated);

    // ✅ Redirige a la página principal (index) con mensaje de éxito
    return redirect()
        ->route('agreements.index')
        ->with('success', 'Convenio creado correctamente.');
}
    // VER (usa view.blade.php)
    public function view(Agreement $agreement)
    {
        $agreement->load(['company','pdf']);
        return view('agreements.view', compact('agreement'));
    }

    // FORM EDITAR
    public function edit(Agreement $agreement)
    {
        $agreement->load(['company','pdf']);
        $companies = Company::orderBy('nombre')->get();
        $documents = Document::orderByDesc('fecha')->get();

        return view('agreements.edit', compact('agreement','companies','documents'));
    }

    // ACTUALIZAR
    public function update(Request $request, Agreement $agreement)
    {
        $validated = $request->validate([
            'company_id'      => ['required','integer','exists:companies,id'],
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

        return redirect()->route('agreements.edit', $agreement)
            ->with('success', 'Convenio actualizado correctamente.');
    }

    // ELIMINAR
    public function destroy(Agreement $agreement)
    {
        $agreement->delete();
        return redirect()->route('agreements.index')->with('success', 'Convenio eliminado.');
    }
}
