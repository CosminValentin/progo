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

        $applications = Application::query()
            ->with(['participant', 'offer.company'])
            ->when($q, function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->whereHas('participant', function ($p) use ($q) {
                        $p->where('nombre', 'like', "%{$q}%")
                          ->orWhere('dni_nie', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('offer', function ($o) use ($q) {
                        $o->where('puesto', 'like', "%{$q}%")
                          ->orWhereHas('company', function ($c) use ($q) {
                              $c->where('nombre', 'like', "%{$q}%");
                          });
                    })
                    ->orWhere('estado', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('applications.applications', compact('applications', 'q'));
    }

    public function show(Application $application)
    {
        $application->load(['participant', 'offer.company']);
        return view('applications.viewapplication', compact('application'));
    }

    public function edit(Application $application)
    {
        $application->load(['participant', 'offer.company']);
        $offers = Offer::with('company')->orderByDesc('fecha')->get();
        return view('applications.editapplication', compact('application', 'offers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'offer_id'       => ['required', 'exists:offers,id'],
            'estado'         => ['required', Rule::in(['pendiente','en_proceso','aceptada','rechazada'])],
            'fecha'          => ['required', 'date'],
        ]);

        $app = new Application();
        $app->participant_id = $data['participant_id'];
        $app->offer_id       = $data['offer_id'];
        $app->estado         = $data['estado'];
        $app->fecha          = $data['fecha'];
        $app->save();

        return $request->wantsJson()
            ? response()->json(['ok' => true, 'id' => $app->id], 201)
            : back()->with('success', 'Candidatura creada correctamente.');
    }

    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'offer_id'       => ['required', 'exists:offers,id'],
            'estado'         => ['required', Rule::in(['pendiente','en_proceso','aceptada','rechazada'])],
            'fecha'          => ['required', 'date'],
        ]);

        $application->participant_id = $data['participant_id'];
        $application->offer_id       = $data['offer_id'];
        $application->estado         = $data['estado'];
        $application->fecha          = $data['fecha'];
        $application->save();

        return $request->wantsJson()
            ? response()->json(['ok' => true], 200)
            : back()->with('success', 'Candidatura actualizada correctamente.');
    }

    public function destroy(Request $request, Application $application)
    {
        $application->delete();

        return $request->wantsJson()
            ? response()->json(['ok' => true], 200)
            : back()->with('success', 'Candidatura eliminada correctamente.');
    }
}
