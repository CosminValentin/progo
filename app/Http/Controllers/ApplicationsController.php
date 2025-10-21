<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Participant;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $query = Application::query()
            ->with(['participant', 'offer'])
            ->when($q, function ($qbuilder) use ($q) {
                $qbuilder->where(function ($w) use ($q) {
                    $w->whereHas('participant', function ($p) use ($q) {
                        $p->where('nombre', 'like', "%{$q}%")
                          ->orWhere('dni_nie', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('offer', function ($o) use ($q) {
                        // intentamos varios nombres habituales
                        $o->where('titulo', 'like', "%{$q}%")
                          ->orWhere('nombre', 'like', "%{$q}%")
                          ->orWhere('referencia', 'like', "%{$q}%");
                    })
                    ->orWhere('estado', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('fecha')
            ->orderByDesc('id');

        $applications = $query->paginate(12)->withQueryString();

        return view('applications.applications', [
            'applications' => $applications,
            'q' => $q,
        ]);
    }

    public function create()
    {
        $participants = Participant::orderBy('nombre')->get(['id', 'nombre', 'dni_nie']);
        $offers = Offer::orderBy('id', 'desc')->get(['id', 'titulo', 'nombre']); // cogemos ambos posibles
        $estados = ['pendiente','en_proceso','aceptada','rechazada'];

        return view('applications.addapplication', compact('participants', 'offers', 'estados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => ['required','integer','exists:participants,id'],
            'offer_id'       => ['required','integer','exists:offers,id'],
            'estado'         => ['required', Rule::in(['pendiente','en_proceso','aceptada','rechazada'])],
            'fecha'          => ['required','date'],
        ]);

        $app = Application::create($validated);

        return redirect()->route('applications')->with('success', 'Candidatura creada correctamente.');
    }

    public function show(Application $application)
    {
        $application->load(['participant', 'offer']);
        return view('applications.viewapplication', compact('application'));
    }

    public function edit(Application $application)
    {
        $application->load(['participant','offer']);

        $participants = Participant::orderBy('nombre')->get(['id', 'nombre', 'dni_nie']);
        $offers = Offer::orderBy('id', 'desc')->get(['id','titulo','nombre']);
        $estados = ['pendiente','en_proceso','aceptada','rechazada'];

        return view('applications.editapplication', compact('application','participants','offers','estados'));
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'participant_id' => ['required','integer','exists:participants,id'],
            'offer_id'       => ['required','integer','exists:offers,id'],
            'estado'         => ['required', Rule::in(['pendiente','en_proceso','aceptada','rechazada'])],
            'fecha'          => ['required','date'],
        ]);

        $application->update($validated);

        return redirect()->route('applications')->with('success', 'Candidatura actualizada.');
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('applications')->with('success', 'Candidatura eliminada.');
    }
}
