<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ParticipantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTADO de participantes con búsqueda y métricas.
     * Carga: tutor, último CV (documents), última nota de trabajador (con usuario), CV (tabla cv).
     */
    public function participants(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $participants = Participant::query()
            ->with([
                'tutor',

                // Último CV subido en documents (owner_type='participants', tipo='cv')
                'cvsDocuments' => fn ($q2) => $q2->orderByDesc('fecha')->limit(1),

                // Cargar UNA nota (la última) + su usuario
                'notasTrabajador' => function ($q2) {
                    $q2->orderByDesc('fecha_hora')
                       ->limit(1)
                       ->with('usuario');
                },

                // CV en tabla cv (id_cv -> cv.id)
                'cvFile',
            ])
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

        // Relación virtual 'ultimaNota' para usar $p->ultimaNota en la vista de listado
        $participants->getCollection()->transform(function ($p) {
            $p->setRelation('ultimaNota', optional($p->getRelation('notasTrabajador'))->first());
            return $p;
        });

        $totalParticipants    = Participant::count();
        $activeParticipants   = Participant::where('estado', 'activo')->count();
        $pendingParticipants  = Participant::where('estado', 'pendiente')->count();
        $inactiveParticipants = Participant::where('estado', 'inactivo')->count();

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
     * FICHA del participante.
     * Cargamos todas las relaciones necesarias para la vista y las tablas (notas, docs, contratos, etc.).
     * Esta es la vista que estás usando: participants.viewparticipant
     */
    public function viewParticipant(Participant $participant)
    {
        $participant->load([
            'tutor',

            // Notas del trabajador (todas) + usuario, en orden descendente por fecha
            'notasTrabajador' => fn ($q) => $q->with('usuario')->orderByDesc('fecha_hora'),

            // Documents (todos) y sólo CVs por si tu vista los necesita
            'documents'    => fn ($q) => $q->orderByDesc('fecha'),
            'cvsDocuments' => fn ($q) => $q->where('tipo', 'cv')->orderByDesc('fecha'),
            'cvFile',

            // Resto de relaciones comunes en la ficha
            'applications'           => fn ($q) => $q->orderByDesc('fecha'),
            'applications.offer.company',
            'contracts'              => fn ($q) => $q->orderByDesc('fecha_inicio'),
            'contracts.company',
            'contracts.offer.company',
            'ssRecords',
            'insertionChecks',
        ]);

        // Si tu Blade usa 'timelineEvents' (preview), lo preparamos. Si no, puedes omitirlo.
        $events = collect();

        // Alta en programa
        if ($participant->fecha_alta_prog) {
            $events->push([
                'date'  => optional($participant->fecha_alta_prog)->startOfDay(),
                'title' => 'Alta en programa',
                'desc'  => 'Alta del participante en el programa.',
                'type'  => 'participant',
                'icon'  => 'fa-user',
                'color' => 'indigo',
            ]);
        }

        // Notas
        foreach ($participant->notasTrabajador as $n) {
            $events->push([
                'date'  => optional($n->fecha_hora),
                'title' => 'Nota de trabajador',
                'desc'  => Str::limit($n->texto, 300),
                'type'  => 'note',
                'icon'  => 'fa-note-sticky',
                'color' => 'amber',
            ]);
        }

        // CV - documents (tipo cv)
        foreach ($participant->cvsDocuments as $cvd) {
            $events->push([
                'date'  => optional($cvd->fecha),
                'title' => 'CV subido (Documents)',
                'desc'  => $cvd->nombre_archivo,
                'type'  => 'cv',
                'icon'  => 'fa-file-lines',
                'color' => 'emerald',
            ]);
        }

        // CV - tabla cv (si existe)
        if ($participant->cvFile) {
            $events->push([
                'date'  => optional($participant->cvFile->fecha_subida),
                'title' => 'CV vinculado (tabla cv)',
                'desc'  => $participant->cvFile->ruta_archivo,
                'type'  => 'cv',
                'icon'  => 'fa-file-lines',
                'color' => 'emerald',
            ]);
        }

        // Documentos no-cv (ya vienen cargados en 'documents')
        foreach ($participant->documents->filter(fn ($d) => ($d->tipo ?? null) !== 'cv') as $doc) {
            $events->push([
                'date'  => optional($doc->fecha),
                'title' => 'Documento',
                'desc'  => $doc->nombre_archivo,
                'type'  => 'document',
                'icon'  => 'fa-file',
                'color' => 'slate',
            ]);
        }

        // Contratos
        foreach ($participant->contracts as $c) {
            $events->push([
                'date'  => optional($c->fecha_inicio),
                'title' => 'Contrato',
                'desc'  => 'Tipo: ' . ($c->tipo_contrato ?? '—'),
                'type'  => 'contract',
                'icon'  => 'fa-file-signature',
                'color' => 'purple',
            ]);
        }

        // Candidaturas
        foreach ($participant->applications as $ap) {
            $events->push([
                'date'  => optional($ap->fecha ?? $ap->created_at),
                'title' => 'Candidatura',
                'desc'  => $ap->offer ? ('Oferta #'.$ap->offer->id.' '.$ap->offer->puesto) : 'Candidatura',
                'type'  => 'application',
                'icon'  => 'fa-user-check',
                'color' => 'sky',
            ]);
        }

        // Registros SS
        foreach ($participant->ssRecords as $ss) {
            $events->push([
                'date'  => optional($ss->created_at),
                'title' => 'Registro Seguridad Social',
                'desc'  => $ss->observaciones ?? ('Régimen: '.$ss->regimen),
                'type'  => 'ss',
                'icon'  => 'fa-shield-halved',
                'color' => 'rose',
            ]);
        }

        // Insertion Checks
        foreach ($participant->insertionChecks as $ic) {
            $events->push([
                'date'  => optional($ic->fecha ?? $ic->created_at),
                'title' => 'Validación de inserción',
                'desc'  => $ic->observaciones ?? 'Validación',
                'type'  => 'insertion',
                'icon'  => 'fa-circle-check',
                'color' => 'emerald',
            ]);
        }

        $timelineEvents = $events
            ->filter(fn ($e) => !empty($e['date']))
            ->sortByDesc('date')
            ->values()
            ->take(15);

        return view('participants.viewparticipant', [
            'participant'    => $participant,
            'timelineEvents' => $timelineEvents,
        ]);
    }

    /**
     * NUEVO (form)
     */
    public function addParticipant()
    {
        return view('participants.addparticipant');
    }

    /**
     * GUARDAR (POST)
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
            'observaciones2'   => ['nullable', 'string'],
            'tutor_id'         => ['nullable', 'integer', 'exists:users,id'],
            'id_cv'            => ['nullable', 'integer', 'exists:cv,id'],
        ]);

        $validated['consent_rgpd'] = $request->boolean('consent_rgpd');
        if (empty($validated['estado'])) {
            $validated['estado'] = 'activo';
        }

        Participant::create($validated);

        return redirect()->route('participants')->with('success', 'Participante creado correctamente.');
    }

    /**
     * EDITAR (form)
     */
    public function editParticipant(Participant $participant)
    {
        return view('participants.editparticipant', compact('participant'));
    }

    /**
     * ACTUALIZAR (POST)
     */
    public function updateParticipant(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'dni_nie'          => ['required', 'max:16', 'string', 'alpha_num', Rule::unique('participants', 'dni_nie')->ignore($participant->id)],
            'nombre'           => ['required', 'max:120', 'string'],
            'email'            => ['nullable', 'email', 'max:120'],
            'telefono'         => ['nullable', 'max:30'],
            'fecha_alta_prog'  => ['required', 'date'],
            'provincia'        => ['nullable', 'max:40'],
            'estado'           => ['nullable', 'max:20'],
            'notas'            => ['nullable', 'string'],
            'observaciones2'   => ['nullable', 'string'],
            'tutor_id'         => ['nullable', 'integer', 'exists:users,id'],
            'id_cv'            => ['nullable', 'integer', 'exists:cv,id'],
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
                ->withErrors('No se pudo eliminar el participante. Puede estar relacionado con otros registros.');
        }
    }

    /**
     * TIMELINE completo (si usas una vista dedicada)
     */
    public function timeline(Participant $participant)
    {
        $participant->load([
            'tutor',
            'cvsDocuments',       // documents(tipo=cv)
            'cvFile',             // fila de tabla cv (si la usas)
            'documents',          // otros docs
            'notasTrabajador.usuario',
            'contracts.company', 'contracts.offer',
            'applications.offer',
            'ssRecords',
            'insertionChecks',
        ]);

        $events = collect();

        // Alta en programa
        if ($participant->fecha_alta_prog) {
            $events->push([
                'date'  => optional($participant->fecha_alta_prog)->startOfDay(),
                'title' => 'Alta en programa',
                'desc'  => 'Alta del participante en el programa.',
                'type'  => 'participant',
                'icon'  => 'fa-user',
                'color' => 'indigo',
                'meta'  => [
                    'Estado'     => ucfirst($participant->estado ?? '—'),
                    'Provincia'  => $participant->provincia ?? '—',
                    'Tutor'      => $participant->tutor->name ?? '—',
                    'Obs. (trab)'=> Str::limit($participant->observaciones2 ?? '—', 120),
                ],
            ]);
        }

        // Notas
        foreach ($participant->notasTrabajador as $n) {
            $events->push([
                'date'  => optional($n->fecha_hora),
                'title' => 'Nota de trabajador',
                'desc'  => Str::limit($n->texto, 300),
                'type'  => 'note',
                'icon'  => 'fa-note-sticky',
                'color' => 'amber',
                'meta'  => [
                    'Usuario' => $n->usuario->name ?? '—',
                    'Estado'  => $n->estado ?? '—',
                ],
            ]);
        }

        // CV - documents (tipo=cv)
        foreach ($participant->cvsDocuments as $cvd) {
            $events->push([
                'date'  => optional($cvd->fecha),
                'title' => 'CV subido (Documents)',
                'desc'  => $cvd->nombre_archivo,
                'type'  => 'cv',
                'icon'  => 'fa-file-lines',
                'color' => 'emerald',
            ]);
        }

        // CV - tabla cv (si existe)
        if ($participant->cvFile) {
            $events->push([
                'date'  => optional($participant->cvFile->fecha_subida),
                'title' => 'CV vinculado (tabla cv)',
                'desc'  => $participant->cvFile->ruta_archivo,
                'type'  => 'cv',
                'icon'  => 'fa-file-lines',
                'color' => 'emerald',
            ]);
        }

        // Documentos no-cv
        $docs = $participant->documents()
            ->where(function ($q) {
                $q->whereNull('tipo')->orWhere('tipo', '!=', 'cv');
            })
            ->orderByDesc('fecha')
            ->get();

        foreach ($docs as $doc) {
            $events->push([
                'date'  => optional($doc->fecha),
                'title' => 'Documento',
                'desc'  => $doc->nombre_archivo,
                'type'  => 'document',
                'icon'  => 'fa-file',
                'color' => 'slate',
                'meta'  => [
                    'Tipo'      => $doc->tipo ?? '—',
                    'Protegido' => $doc->protegido ? 'Sí' : 'No',
                ],
            ]);
        }

        // Contratos
        foreach ($participant->contracts as $c) {
            $events->push([
                'date'  => optional($c->fecha_inicio),
                'title' => 'Contrato',
                'desc'  => 'Tipo: ' . ($c->tipo_contrato ?? '—'),
                'type'  => 'contract',
                'icon'  => 'fa-file-signature',
                'color' => 'purple',
                'meta'  => [
                    'Empresa'      => $c->company->nombre ?? '—',
                    'Oferta'       => $c->offer ? ('#' . $c->offer->id . ' ' . $c->offer->puesto) : '—',
                    'Inicio'       => optional($c->fecha_inicio)?->format('d/m/Y') ?? '—',
                    'Fin prevista' => optional($c->fecha_fin_prevista)?->format('d/m/Y') ?? '—',
                    'Jornada %'    => $c->jornada_pct ? $c->jornada_pct . '%' : '—',
                    'Contrata'     => $c->registrado_contrata ? 'Registrado' : 'Pendiente',
                ],
            ]);
        }

        // Candidaturas
        foreach ($participant->applications as $ap) {
            $events->push([
                'date'  => optional($ap->fecha ?? $ap->created_at),
                'title' => 'Candidatura',
                'desc'  => $ap->offer ? ('Oferta #' . $ap->offer->id . ' ' . $ap->offer->puesto) : 'Candidatura',
                'type'  => 'application',
                'icon'  => 'fa-user-check',
                'color' => 'sky',
                'meta'  => [
                    'Estado' => ucfirst($ap->estado ?? '—'),
                ],
            ]);
        }

        // Registros SS
        foreach ($participant->ssRecords as $ss) {
            $events->push([
                'date'  => optional($ss->created_at),
                'title' => 'Registro Seguridad Social',
                'desc'  => $ss->observaciones ?? ('Régimen: ' . $ss->regimen),
                'type'  => 'ss',
                'icon'  => 'fa-shield-halved',
                'color' => 'rose',
                'meta'  => [
                    'Régimen' => $ss->regimen ?? '—',
                ],
            ]);
        }

        // Insertion Checks
        foreach ($participant->insertionChecks as $ic) {
            $events->push([
                'date'  => optional($ic->fecha ?? $ic->created_at),
                'title' => 'Validación de inserción',
                'desc'  => $ic->observaciones ?? 'Validación',
                'type'  => 'insertion',
                'icon'  => 'fa-circle-check',
                'color' => 'emerald',
                'meta'  => [
                    'Periodo válido' => is_null($ic->periodo_valido) ? '—' : ($ic->periodo_valido ? 'Sí' : 'No'),
                    'Días'           => $ic->dias_validos ?? '—',
                ],
            ]);
        }

        // Orden final por fecha (desc)
        $events = $events->filter(fn ($e) => !empty($e['date']))->sortByDesc('date')->values();

        return view('participants.timeline', compact('participant', 'events'));
    }
}
