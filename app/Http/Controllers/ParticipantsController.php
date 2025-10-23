<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTADO con búsqueda y paginación
     */
public function participants(Request $request)
{
    $q = trim((string) $request->get('q'));

    // Consulta base con relaciones
    $participants = Participant::with(['tutor', 'cv', 'notaTrabajador'])
        ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('dni_nie', 'like', "%{$q}%")
                    ->orWhere('nombre', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('provincia', 'like', "%{$q}%")
                    ->orWhere('estado', 'like', "%{$q}%");
            });
        })
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    // 🔹 Cálculos para los cards
    $totalParticipants    = Participant::count();
    $activeParticipants   = Participant::where('estado', 'activo')->count();
    $pendingParticipants  = Participant::where('estado', 'pendiente')->count();
    $inactiveParticipants = Participant::where('estado', 'inactivo')->count();

    // 🔹 Enviamos todo al Blade
    return view('participants.participants', compact(
        'participants',
        'q',
        'totalParticipants',
        'activeParticipants',
        'pendingParticipants',
        'inactiveParticipants'
    ));
}

    /**
     * VER ficha
     */
    public function viewParticipant(Participant $participant)
    {
        $participant->load(['tutor', 'cv', 'notaTrabajador']);
        return view('participants.viewparticipant', compact('participant'));
    }

    /**
     * FORMULARIO NUEVO
     */
    public function addParticipant()
    {
        return view('participants.addparticipant');
    }

    /**
     * GUARDAR NUEVO
     */
    public function saveParticipant(Request $request)
    {
        $validated = $request->validate([
            'dni_nie'          => ['required', 'max:16', 'string', 'alpha_num', Rule::unique('participants', 'dni_nie')],
            'nombre'           => ['required', 'max:120', 'string'],
            'email'            => ['nullable', 'email', 'max:120'],
            'telefono'         => ['nullable', 'max:30'],
            'fecha_alta_prog'  => ['required', 'date'],
            'provincia'        => ['nullable', 'max:40'],
            'estado'           => ['nullable', 'max:20'],
            'notas'            => ['nullable', 'string'],
            'tutor_id'         => ['nullable', 'integer'],
            'id_cv'            => ['nullable', 'integer'],
            'id_notas_trabajador' => ['required', 'integer'],
        ]);

        $validated['consent_rgpd'] = $request->boolean('consent_rgpd');
        if (empty($validated['estado'])) {
            $validated['estado'] = 'activo';
        }

        Participant::create($validated);

        return redirect()->route('participants')->with('success', 'Participante creado correctamente.');
    }

    /**
     * FORMULARIO EDITAR
     */
    public function editParticipant(Participant $participant)
    {
        return view('participants.editparticipant', compact('participant'));
    }

    /**
     * ACTUALIZAR
     */
    public function updateParticipant(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'dni_nie'          => [
                'required', 'max:16', 'string', 'alpha_num',
                Rule::unique('participants', 'dni_nie')->ignore($participant->id),
            ],
            'nombre'           => ['required', 'max:120', 'string'],
            'email'            => ['nullable', 'email', 'max:120'],
            'telefono'         => ['nullable', 'max:30'],
            'fecha_alta_prog'  => ['required', 'date'],
            'provincia'        => ['nullable', 'max:40'],
            'estado'           => ['nullable', 'max:20'],
            'notas'            => ['nullable', 'string'],
            'tutor_id'         => ['nullable', 'integer'],
            'id_cv'            => ['nullable', 'integer'],
            'id_notas_trabajador' => ['required', 'integer'],
        ]);

        $validated['consent_rgpd'] = $request->boolean('consent_rgpd');
        if (empty($validated['estado'])) {
            $validated['estado'] = 'activo';
        }

        $participant->update($validated);

        return redirect()->route('participants')->with('success', 'Participante actualizado correctamente.');
    }
    

    /**
     * ELIMINAR
     */
    public function deleteParticipant(Participant $participant)
    {
        try {
            $participant->delete();
            return redirect()->route('participants')->with('success', 'Participante eliminado.');
        } catch (\Throwable $e) {
            return redirect()->route('participants')
                ->with('success', null)
                ->withErrors('No se pudo eliminar el participante. Puede estar relacionado con otros registros.');
        }
    }

    
}


