<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Participant;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Document;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $contracts = Contract::with(['participant','company','offer'])
            ->when($q, function($query) use ($q) {
                $query->where(function($w) use ($q){
                    $w->whereHas('participant', fn($p)=>$p->where('nombre','like',"%{$q}%"))
                      ->orWhereHas('company', fn($c)=>$c->where('nombre','like',"%{$q}%"))
                      ->orWhereHas('offer', fn($o)=>$o->where('puesto','like',"%{$q}%"))
                      ->orWhere('tipo_contrato','like',"%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $total    = Contract::count();
        // Vigentes: usando las columnas reales (fecha_fin_prevista)
        $vigentes = Contract::where(function ($q) {
                $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('fecha_fin_prevista')->orWhere('fecha_fin_prevista', '>=', now());
            })
            ->count();

        return view('contracts.index', compact('contracts','total','vigentes'));
    }

    // NUEVO
    public function create()
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        $companies    = Company::orderBy('nombre')->get(['id','nombre']);
        $offers       = Offer::orderByDesc('id')->limit(200)->get(['id','puesto']);
        $documents    = Document::orderByDesc('fecha')->limit(200)->get(['id','nombre_archivo','tipo']);

        return view('contracts.create', compact('participants','companies','offers','documents'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        // Normaliza nombres legacy → columnas reales
        $map = [
            'tipo'       => 'tipo_contrato',
            'jornada'    => 'jornada_pct',
            'fecha_fin'  => 'fecha_fin_prevista',
            'pdf_doc_id' => 'contrata_doc_id',
        ];
        foreach ($map as $old => $new) {
            if ($request->filled($old) && !$request->filled($new)) {
                $request->merge([$new => $request->input($old)]);
            }
        }

        $validated = $request->validate([
            'participant_id'     => ['nullable','exists:participants,id'],
            'company_id'         => ['nullable','exists:companies,id'],
            'offer_id'           => ['nullable','exists:offers,id'],
            'fecha_inicio'       => ['required','date'],
            'fecha_fin_prevista' => ['nullable','date','after_or_equal:fecha_inicio'],
            'tipo_contrato'      => ['nullable','string','max:60'],
            'jornada_pct'        => ['nullable','integer','min:1','max:100'],
            'registrado_contrata'=> ['sometimes','boolean'],
            'contrata_doc_id'    => ['nullable','exists:documents,id'],
            'alta_ss_doc_id'     => ['nullable','exists:documents,id'],
        ]);

        $validated['registrado_contrata'] = $request->boolean('registrado_contrata');

        Contract::create($validated);

        return redirect()->route('contracts.index')->with('success','Contrato creado.');
    }

    // EDITAR
    public function edit(Contract $contract)
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        $companies    = Company::orderBy('nombre')->get(['id','nombre']);
        $offers       = Offer::orderByDesc('id')->limit(200)->get(['id','puesto']);
        $documents    = Document::orderByDesc('fecha')->limit(200)->get(['id','nombre_archivo','tipo']);

        return view('contracts.edit', compact('contract','participants','companies','offers','documents'));
    }

    // UPDATE (solo **una** versión)
    public function update(Request $request, Contract $contract)
    {
        // Normaliza nombres legacy → columnas reales
        $map = [
            'tipo'       => 'tipo_contrato',
            'jornada'    => 'jornada_pct',
            'fecha_fin'  => 'fecha_fin_prevista',
            'pdf_doc_id' => 'contrata_doc_id',
        ];
        foreach ($map as $old => $new) {
            if ($request->filled($old) && !$request->filled($new)) {
                $request->merge([$new => $request->input($old)]);
            }
        }

        $validated = $request->validate([
            'participant_id'     => ['nullable','exists:participants,id'],
            'company_id'         => ['nullable','exists:companies,id'],
            'offer_id'           => ['nullable','exists:offers,id'],
            'fecha_inicio'       => ['required','date'],
            'fecha_fin_prevista' => ['nullable','date','after_or_equal:fecha_inicio'],
            'tipo_contrato'      => ['nullable','string','max:60'],
            'jornada_pct'        => ['nullable','integer','min:1','max:100'],
            'registrado_contrata'=> ['sometimes','boolean'],
            'contrata_doc_id'    => ['nullable','exists:documents,id'],
            'alta_ss_doc_id'     => ['nullable','exists:documents,id'],
        ]);

        $validated['registrado_contrata'] = $request->boolean('registrado_contrata');

        $contract->update($validated);

        return redirect()->route('contracts.index')->with('success','Contrato actualizado.');
    }

    // BORRAR
    public function destroy(Contract $contract)
    {
        try {
            $contract->delete();
            return redirect()->route('contracts.index')->with('success','Contrato eliminado.');
        } catch (\Throwable $e) {
            return redirect()->route('contracts.index')->with('error','No se pudo eliminar el contrato. Puede estar relacionado con otros datos.');
        }
    }
}
