<?php

namespace App\Http\Controllers;

use App\Models\SSRecord;
use App\Models\Participant;
use Illuminate\Http\Request;

class SSRecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO + búsqueda + paginación
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $records = SSRecord::with(['participant'])
            ->when($q, function ($query) use ($q) {
                $query->where('regimen', 'like', "%{$q}%")
                      ->orWhere('observaciones', 'like', "%{$q}%")
                      ->orWhereHas('participant', fn($p) =>
                            $p->where('nombre', 'like', "%{$q}%")
                              ->orWhere('dni_nie', 'like', "%{$q}%")
                        );
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Cards
        $totalRegistros  = SSRecord::count();
        $conRegimenAlta  = SSRecord::where('regimen', 'alta')->count();
        $sumDiasAlta     = (int) SSRecord::sum('dias_alta');
        $sumJornadas     = (int) SSRecord::sum('jornadas_reales');

        return view('ss_records.index', compact('records', 'q', 'totalRegistros', 'conRegimenAlta', 'sumDiasAlta', 'sumJornadas'));
    }

    // NUEVO
    public function create()
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre','dni_nie']);
        return view('ss_records.create', compact('participants'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id'   => ['nullable','integer','exists:participants,id'],
            'regimen'          => ['required','string','max:20'],
            'dias_alta'        => ['nullable','integer','min:0'],
            'jornadas_reales'  => ['nullable','integer','min:0'],
            'coef_aplicado'    => ['nullable','numeric','between:0,999.9999'],
            'dias_equivalentes'=> ['nullable','integer','min:0'],
            'observaciones'    => ['nullable','string'],
        ]);

        SSRecord::create($validated);

        return redirect()->route('ss.index')->with('success','Registro SS creado correctamente.');
    }

    // VER
    public function show(SSRecord $ss)
    {
        $ss->load('participant');
        return view('ss_records.view', compact('ss'));
    }

    // EDITAR
    public function edit(SSRecord $ss)
    {
        $ss->load('participant');
        $participants = Participant::orderBy('nombre')->get(['id','nombre','dni_nie']);
        return view('ss_records.edit', compact('ss','participants'));
    }

    // ACTUALIZAR
    public function update(Request $request, SSRecord $ss)
    {
        $validated = $request->validate([
            'participant_id'   => ['nullable','integer','exists:participants,id'],
            'regimen'          => ['required','string','max:20'],
            'dias_alta'        => ['nullable','integer','min:0'],
            'jornadas_reales'  => ['nullable','integer','min:0'],
            'coef_aplicado'    => ['nullable','numeric','between:0,999.9999'],
            'dias_equivalentes'=> ['nullable','integer','min:0'],
            'observaciones'    => ['nullable','string'],
        ]);

        $ss->update($validated);

        return redirect()->route('ss.index')->with('success','Registro SS actualizado correctamente.');
    }

    // ELIMINAR
    public function destroy(SSRecord $ss)
    {
        try {
            $ss->delete();
            return redirect()->route('ss.index')->with('success','Registro SS eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('ss.index')
                ->with('error','No se pudo eliminar el registro SS. Puede estar relacionado con otros registros.');
        }
    }
}
