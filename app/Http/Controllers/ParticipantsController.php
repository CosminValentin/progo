<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipantsController extends Controller
{
    public function __construct()
    {
        // Protege todas las rutas de este controlador
        $this->middleware('auth');
    }

    /**
     * LISTADO con búsqueda y paginación
     */
    public function participants(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $participants = Participant::when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('dni_nie', 'like', "%{$q}%")
                        ->orWhere('nombre', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('telefono', 'like', "%{$q}%")
                        ->orWhere('provincia', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        return view('participants.participants', compact('participants', 'q'));
    }

    /**
     * VER ficha
     */
    public function viewParticipant(Participant $participant)
    {
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
            // 'tutor_id'      => ['nullable','integer','exists:staff_users,id'], // activa si tienes la tabla
            // 'consent_rgpd'  => checkbox → se normaliza abajo
        ]);

        // Normaliza checkbox y valores por defecto
        $validated['consent_rgpd'] = $request->boolean('consent_rgpd'); // true/false
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
            // 'tutor_id'      => ['nullable','integer','exists:staff_users,id'],
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
            // Si en el futuro hay claves foráneas, capturamos el error y avisamos
            return redirect()->route('participants')
                ->with('success', null)
                ->withErrors('No se pudo eliminar el participante. Puede estar relacionado con otros registros.');
        }
    }
}
