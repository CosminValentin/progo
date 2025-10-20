<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipantsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // LISTADO => resources/views/participants/participants.blade.php
    public function participants(Request $request)
    {
        $q = trim((string)$request->get('q'));

        $participants = Participant::when($q, function ($query) use ($q) {
                $query->where('dni_nie','like',"%{$q}%")
                      ->orWhere('nombre','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('participants.participants', compact('participants','q'));
    }

    // FORM NUEVO => resources/views/participants/addparticipant.blade.php
    public function addParticipant()
    {
        return view('participants.addparticipant');
    }

    // GUARDAR NUEVO
    public function saveParticipant(Request $request)
    {
        $data = $request->validate([
            'dni_nie'         => ['required','string','max:16','unique:participants,dni_nie'],
            'nombre'          => ['required','string','max:120'],
            'telefono'        => ['nullable','string','max:30'],
            'email'           => ['nullable','email','max:120'],
            'fecha_alta_prog' => ['required','date'],
            'provincia'       => ['nullable','string','max:40'],
            'tutor_id'        => ['nullable','integer'],
            'estado'          => ['nullable','string','max:20'],
            'consent_rgpd'    => ['nullable','boolean'],
            'notas'           => ['nullable','string'],
        ]);

        $data['consent_rgpd'] = $request->boolean('consent_rgpd');

        $p = Participant::create($data);

        return redirect()->route('viewparticipant', $p->id)
                         ->with('status','Participante creado correctamente.');
    }

    // VER => resources/views/participants/viewparticipant.blade.php
    public function viewParticipant(Participant $participant)
    {
        return view('participants.viewparticipant', compact('participant'));
    }

    // FORM EDITAR => resources/views/participants/editparticipant.blade.php
    public function editParticipant(Participant $participant)
    {
        return view('participants.editparticipant', compact('participant'));
    }

    // GUARDAR EDICIÓN
    public function updateParticipant(Request $request, Participant $participant)
    {
        $data = $request->validate([
            'dni_nie'         => ['required','string','max:16', Rule::unique('participants','dni_nie')->ignore($participant->id)],
            'nombre'          => ['required','string','max:120'],
            'telefono'        => ['nullable','string','max:30'],
            'email'           => ['nullable','email','max:120'],
            'fecha_alta_prog' => ['required','date'],
            'provincia'       => ['nullable','string','max:40'],
            'tutor_id'        => ['nullable','integer'],
            'estado'          => ['nullable','string','max:20'],
            'consent_rgpd'    => ['nullable','boolean'],
            'notas'           => ['nullable','string'],
        ]);

        $data['consent_rgpd'] = $request->boolean('consent_rgpd');

        $participant->update($data);

        return redirect()->route('viewparticipant', $participant->id)
                         ->with('status','Participante actualizado.');
    }

    // BORRAR
    public function deleteParticipant(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('participants')
                         ->with('status','Participante eliminado.');
    }
}
