<?php

namespace App\Http\Controllers;

use App\Models\NotaTrabajador;
use App\Models\Participant;
use Illuminate\Http\Request;

class NotaTrabajadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO con búsqueda y paginación
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $notas = NotaTrabajador::with(['usuario', 'participant'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('texto', 'like', "%{$q}%")
                      ->orWhereHas('participant', fn($p) => $p->where('nombre', 'like', "%{$q}%"))
                      ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', "%{$q}%"))
                      ->orWhere('estado', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('fecha_hora')
            ->paginate(10)
            ->withQueryString();

        // métricas para las cards
        $totalNotas = $notas->total();
        $usuariosConNotas = NotaTrabajador::distinct('id_usuario_trabajador')->count('id_usuario_trabajador');

        return view('notas_trabajador.index', compact('notas', 'q', 'totalNotas', 'usuariosConNotas'));
    }

    // VER ficha nota
    public function show(NotaTrabajador $nota)
    {
        $nota->load(['usuario', 'participant']);
        return view('notas_trabajador.view', compact('nota'));
    }

    // FORMULARIO NUEVO (opcionalmente ?participant=ID para preseleccionar)
    public function create(Request $request)
    {
        $participant = null;
        if ($request->filled('participant')) {
            $participant = Participant::find($request->integer('participant'));
        }
        $participants = Participant::orderBy('nombre')->get(['id', 'nombre']);

        return view('notas_trabajador.create', compact('participant', 'participants'));
    }

    public function store(Request $request)
    {
        // Acepta tanto id_participante (nuevo) como participant_id (legacy)
        if ($request->filled('participant_id') && !$request->filled('id_participante')) {
            $request->merge(['id_participante' => $request->input('participant_id')]);
        }

        $validated = $request->validate([
            'id_participante' => ['required', 'exists:participants,id'],
            'texto'           => ['required', 'string'],
            'fecha_hora'      => ['required', 'date'],
            'estado'          => ['nullable', 'string'],
        ]);

        // ✅ Asignación explícita (no dependemos de fill()/create())
        $nota = new NotaTrabajador();
        $nota->id_participante       = (int) $validated['id_participante'];
        $nota->id_usuario_trabajador = auth()->id();
        $nota->texto                 = $validated['texto'];
        $nota->fecha_hora            = $validated['fecha_hora'];
        $nota->estado                = $validated['estado'] ?? 'activo';
        $nota->save();

        return redirect()->route('notas.index')->with('success', 'Nota creada correctamente.');
    }


    // FORMULARIO EDITAR
    public function edit(NotaTrabajador $nota)
    {
        $nota->load(['usuario', 'participant']);
        $participants = Participant::orderBy('nombre')->get(['id', 'nombre']);
        return view('notas_trabajador.edit', compact('nota', 'participants'));
    }

    // ACTUALIZAR
    public function update(Request $request, NotaTrabajador $nota)
    {
        if ($request->filled('participant_id') && !$request->filled('id_participante')) {
            $request->merge(['id_participante' => $request->input('participant_id')]);
        }

        $validated = $request->validate([
            'id_participante' => ['required', 'exists:participants,id'],
            'texto'           => ['required', 'string'],
            'fecha_hora'      => ['required', 'date'],
            'estado'          => ['nullable', 'string'],
        ]);

        // ✅ Asignación explícita (evita que un mal fillable/guarded te lo deje a NULL)
        $nota->id_participante = (int) $validated['id_participante'];
        $nota->texto           = $validated['texto'];
        $nota->fecha_hora      = $validated['fecha_hora'];
        $nota->estado          = $validated['estado'] ?? $nota->estado;
        $nota->save();

        return redirect()->route('notas.index')->with('success', 'Nota actualizada correctamente.');
    }


    // ELIMINAR
    public function destroy(NotaTrabajador $nota)
    {
        try {
            $nota->delete();
            return redirect()->route('notas.index')->with('success', 'Nota eliminada correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('notas.index')
                ->with('error', 'No se pudo eliminar la nota. Puede estar relacionada con otros registros.');
        }
    }
}
