<?php

namespace App\Http\Controllers;

use App\Models\InsertionCheck;
use App\Models\Participant;
use Illuminate\Http\Request;

class InsertionChecksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO con búsqueda cliente + paginación servidor
    public function index(Request $request)
    {
        $checks = InsertionCheck::with('participant')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10);

        $total     = InsertionCheck::count();
        $validos90 = InsertionCheck::where('valido_90_dias', 1)->count();

        return view('insertion_checks.index', compact('checks', 'total', 'validos90'));
    }

    // FORM NUEVO
    public function create()
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        return view('insertion_checks.create', compact('participants'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => ['nullable', 'exists:participants,id'],
            'fecha'          => ['nullable', 'date'],
            'periodo_valido' => ['nullable', 'boolean'],
            'dias_validos'   => ['nullable', 'integer', 'min:0'],
            'fuente'         => ['nullable', 'string', 'max:20'],
            'parcialidad'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'valido_90_dias' => ['nullable', 'boolean'],
            'observaciones'  => ['nullable', 'string'],
            'observaciones2' => ['nullable', 'string'],
        ]);

        // Defaults
        if (empty($validated['fecha'])) $validated['fecha'] = now();
        $validated['periodo_valido'] = (bool)($validated['periodo_valido'] ?? 0);
        $validated['valido_90_dias'] = (bool)($validated['valido_90_dias'] ?? 0);

        InsertionCheck::create($validated);

        return redirect()->route('insertion_checks.index')->with('success','Registro creado correctamente.');
    }

    // VER
    public function show(InsertionCheck $insertion_check)
    {
        $insertion_check->load('participant');
        return view('insertion_checks.view', ['check' => $insertion_check]);
    }

    // FORM EDITAR
    public function edit(InsertionCheck $insertion_check)
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        return view('insertion_checks.edit', [
            'check' => $insertion_check->load('participant'),
            'participants' => $participants,
        ]);
    }

    // ACTUALIZAR
    public function update(Request $request, InsertionCheck $insertion_check)
    {
        $validated = $request->validate([
            'participant_id' => ['nullable', 'exists:participants,id'],
            'fecha'          => ['nullable', 'date'],
            'periodo_valido' => ['nullable', 'boolean'],
            'dias_validos'   => ['nullable', 'integer', 'min:0'],
            'fuente'         => ['nullable', 'string', 'max:20'],
            'parcialidad'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'valido_90_dias' => ['nullable', 'boolean'],
            'observaciones'  => ['nullable', 'string'],
            'observaciones2' => ['nullable', 'string'],
        ]);

        if (empty($validated['fecha'])) $validated['fecha'] = $insertion_check->fecha ?? now();
        $validated['periodo_valido'] = (bool)($validated['periodo_valido'] ?? 0);
        $validated['valido_90_dias'] = (bool)($validated['valido_90_dias'] ?? 0);

        $insertion_check->update($validated);

        return redirect()->route('insertion_checks.index')->with('success','Registro actualizado.');
    }

    // ELIMINAR
    public function destroy(InsertionCheck $insertion_check)
    {
        try {
            $insertion_check->delete();
            return redirect()->route('insertion_checks.index')->with('success','Registro eliminado.');
        } catch (\Throwable $e) {
            return redirect()->route('insertion_checks.index')
                ->with('error','No se pudo eliminar el registro.');
        }
    }
}
