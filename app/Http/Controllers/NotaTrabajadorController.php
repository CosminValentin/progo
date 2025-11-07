<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

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

// app/Http/Controllers/NotaTrabajadorController.php
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'id_participante' => ['required','integer','exists:participants,id'],
            'texto'           => ['required','string'],
            'fecha_hora'      => ['nullable','date'],
            'estado'          => ['nullable','in:activo,seguimiento,cerrado'],
        ]);

        $fecha = $data['fecha_hora'] ?? now();
        if (is_string($fecha)) {
            try { $fecha = Carbon::parse($fecha); } catch (\Throwable $e) { $fecha = now(); }
        }

        $nota = NotaTrabajador::create([
            'id_participante'       => $data['id_participante'],
            'id_usuario_trabajador' => $user->id,
            'texto'                 => $data['texto'],
            'fecha_hora'            => $fecha,
            'estado'                => $data['estado'] ?? null,
        ]);

        if ($request->expectsJson()) {
            $nota->loadMissing(['usuario']);
            return response()->json([
                'id'               => $nota->id,
                'texto'            => $nota->texto,
                'estado'           => (string)$nota->estado,
                'fecha_hora_iso'   => optional($nota->fecha_hora)->toIso8601String(),
                'fecha_hora_local' => optional($nota->fecha_hora)->format('Y-m-d\TH:i'),
                'fecha_hora_hum'   => optional($nota->fecha_hora)->format('d/m/Y H:i'),
                'usuario'          => ['name' => optional($nota->usuario)->name],
                'usuario_name'     => optional($nota->usuario)->name,
                'update_url'       => route('notas.update', $nota),
                'delete_url'       => route('notas.destroy', $nota),
            ]);
        }

        return redirect()
            ->route('viewparticipant', ['participant' => $data['id_participante']])
            ->with('success', 'Nota creada correctamente.');
    }

    public function update(Request $request, NotaTrabajador $nota)
    {
        $user = auth()->user();

        $data = $request->validate([
            'id_participante' => ['nullable','integer','exists:participants,id'],
            'texto'           => ['required','string'],
            'fecha_hora'      => ['nullable','date'],
            'estado'          => ['nullable','in:activo,seguimiento,cerrado'],
        ]);

        $participantId = $data['id_participante'] ?? $nota->id_participante;

        $fecha = $data['fecha_hora'] ?? $nota->fecha_hora;
        if (is_string($fecha)) {
            try { $fecha = Carbon::parse($fecha); } catch (\Throwable $e) { $fecha = $nota->fecha_hora; }
        }

        $nota->update([
            'texto'                 => $data['texto'],
            'fecha_hora'            => $fecha,
            'estado'                => $data['estado'] ?? null,
            'id_usuario_trabajador' => $user->id,
        ]);

        if ($request->expectsJson()) {
            $nota->loadMissing(['usuario']);
            return response()->json([
                'id'               => $nota->id,
                'texto'            => $nota->texto,
                'estado'           => (string)$nota->estado,
                'fecha_hora_iso'   => optional($nota->fecha_hora)->toIso8601String(),
                'fecha_hora_local' => optional($nota->fecha_hora)->format('Y-m-d\TH:i'),
                'fecha_hora_hum'   => optional($nota->fecha_hora)->format('d/m/Y H:i'),
                'usuario'          => ['name' => optional($nota->usuario)->name],
                'usuario_name'     => optional($nota->usuario)->name,
                'update_url'       => route('notas.update', $nota),
                'delete_url'       => route('notas.destroy', $nota),
            ]);
        }

        return redirect()
            ->route('viewparticipant', ['participant' => $participantId])
            ->with('success', 'Nota actualizada.');
    }

    public function destroy(Request $request, NotaTrabajador $nota)
    {
        $participantId = $request->input('id_participante') ?: $nota->id_participante;

        $nota->delete();

        if ($request->expectsJson()) {
            return response()->noContent(); // 204
        }

        return redirect()
            ->route('viewparticipant', ['participant' => $participantId])
            ->with('success', 'Nota eliminada.');
    }
}
