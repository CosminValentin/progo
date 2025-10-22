<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));

        $companies = Company::query()
            ->when($q, function ($qb) use ($q) {
                $qb->where('nombre', 'like', "%{$q}%")
                   ->orWhere('cif_nif', 'like', "%{$q}%")
                   ->orWhere('actividad', 'like', "%{$q}%")
                   ->orWhere('ambito', 'like', "%{$q}%")
                   ->orWhere('contacto_email', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->paginate(5)             
            ->withQueryString();        

        return view('companies.index', compact('companies','q'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif_nif'         => ['required','string','max:30'],
            'nombre'          => ['required','string','max:160'],
            'ambito'          => ['nullable','string','max:80'],
            'actividad'       => ['nullable','string','max:160'],
            'contacto_nombre' => ['nullable','string','max:120'],
            'contacto_email'  => ['nullable','email','max:120'],
            'contacto_tel'    => ['nullable','string','max:30'],
        ]);

        Company::create($validated);

        return redirect()->route('companies')->with('success', 'Empresa creada correctamente.');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'cif_nif'         => ['required','string','max:30'],
            'nombre'          => ['required','string','max:160'],
            'ambito'          => ['nullable','string','max:80'],
            'actividad'       => ['nullable','string','max:160'],
            'contacto_nombre' => ['nullable','string','max:120'],
            'contacto_email'  => ['nullable','email','max:120'],
            'contacto_tel'    => ['nullable','string','max:30'],
        ]);

        $company->update($validated);

        return redirect()->route('companies')->with('success', 'Empresa actualizada.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies')->with('success', 'Empresa eliminada.');
    }
}
