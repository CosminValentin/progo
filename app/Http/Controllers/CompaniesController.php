<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // LISTAR
    public function companies(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $companies = Company::when($q, function ($query) use ($q) {
                $query->where('cif_nif', 'like', "%{$q}%")
                      ->orWhere('nombre', 'like', "%{$q}%")
                      ->orWhere('actividad', 'like', "%{$q}%")
                      ->orWhere('contacto_email', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        return view('companies.companies', compact('companies', 'q'));
    }

    // VER
    public function viewCompany(Company $company)
    {
        return view('companies.viewcompany', compact('company'));
    }

    // NUEVO
    public function addCompany()
    {
        return view('companies.addcompany');
    }

    // GUARDAR
    public function saveCompany(Request $request)
    {
        $validated = $request->validate([
            'cif_nif'         => ['required', 'max:16', 'unique:companies,cif_nif'],
            'nombre'          => ['required', 'max:160'],
            'ambito'          => ['nullable', 'max:30'],
            'actividad'       => ['nullable', 'max:80'],
            'contacto_nombre' => ['nullable', 'max:120'],
            'contacto_email'  => ['nullable', 'email', 'max:120'],
            'contacto_tel'    => ['nullable', 'max:30'],
        ]);

        Company::create($validated);

        return redirect()->route('companies')->with('success', 'Empresa creada correctamente.');
    }

    // EDITAR
    public function editCompany(Company $company)
    {
        return view('companies.editcompany', compact('company'));
    }

    // ACTUALIZAR
    public function updateCompany(Request $request, Company $company)
    {
        $validated = $request->validate([
            'cif_nif'         => ['required', 'max:16', 'unique:companies,cif_nif,' . $company->id],
            'nombre'          => ['required', 'max:160'],
            'ambito'          => ['nullable', 'max:30'],
            'actividad'       => ['nullable', 'max:80'],
            'contacto_nombre' => ['nullable', 'max:120'],
            'contacto_email'  => ['nullable', 'email', 'max:120'],
            'contacto_tel'    => ['nullable', 'max:30'],
        ]);

        $company->update($validated);

        return redirect()->route('companies')->with('success', 'Empresa actualizada correctamente.');
    }

    // ELIMINAR
    public function deleteCompany(Company $company)
    {
        $company->delete();
        return redirect()->route('companies')->with('success', 'Empresa eliminada.');
    }
}
