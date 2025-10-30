<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Company;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $offers = Offer::query()
            ->with('company')
            ->when($q, function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                        $w->where('puesto', 'like', "%{$q}%")
                          ->orWhere('ubicacion', 'like', "%{$q}%")
                          ->orWhere('tipo_contrato', 'like', "%{$q}%")
                          ->orWhere('estado', 'like', "%{$q}%");
                    })
                    ->orWhereHas('company', function ($c) use ($q) {
                        $c->where('nombre', 'like', "%{$q}%")
                          ->orWhere('cif_nif', 'like', "%{$q}%");
                    });
            })
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('offers.offers', compact('offers', 'q'));
    }

    public function create()
    {
        $companies = Company::orderBy('nombre')->get(['id','nombre','cif_nif']);
        return view('offers.addoffer', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'    => ['required','integer','exists:companies,id'],
            'puesto'        => ['required','string','max:160'],
            'tipo_contrato' => ['nullable','string','max:80'],
            'jornada_pct'   => ['nullable','integer','min:1','max:100'],
            'ubicacion'     => ['nullable','string','max:160'],
            'requisitos'    => ['nullable','string'],
            'estado'        => ['nullable','string','max:30'],
            'fecha'         => ['required','date'],
        ]);

        Offer::create($validated);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('offers')->with('success', 'Oferta creada correctamente.');
    }

    public function show(Offer $offer)
    {
        $offer->load('company');
        return view('offers.viewoffer', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $companies = Company::orderBy('nombre')->get(['id','nombre','cif_nif']);
        return view('offers.editoffer', compact('offer','companies'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'company_id'    => ['required','integer','exists:companies,id'],
            'puesto'        => ['required','string','max:160'],
            'tipo_contrato' => ['nullable','string','max:80'],
            'jornada_pct'   => ['nullable','integer','min:1','max:100'],
            'ubicacion'     => ['nullable','string','max:160'],
            'requisitos'    => ['nullable','string'],
            'estado'        => ['nullable','string','max:30'],
            'fecha'         => ['required','date'],
        ]);

        $offer->update($validated);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('offers')->with('success', 'Oferta actualizada.');
    }

    public function destroy(Request $request, Offer $offer)
    {
        $offer->delete();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('offers')->with('success', 'Oferta eliminada.');
    }
}
