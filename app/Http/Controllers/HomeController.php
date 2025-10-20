<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Company;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $stats = [
            'participants' => Participant::count(),
            'companies'    => Company::count(),
            // añade aquí los próximos: 'offers' => Offer::count(), etc.
        ];

        $lastParticipants = Participant::orderByDesc('id')->limit(5)->get();
        $lastCompanies    = Company::orderByDesc('id')->limit(5)->get();

        return view('home', compact('stats','lastParticipants','lastCompanies'));
    }

    // <- NUEVO: endpoint JSON para refrescar KPIs sin recargar la página
    public function metrics(Request $request)
    {
        return response()->json([
            'participants' => Participant::count(),
            'companies'    => Company::count(),
            // futuros:
            // 'offers'       => Offer::count(),
            // 'applications' => Application::count(),
            // 'contracts'    => Contract::count(),
            // 'documents'    => Document::count(),
        ]);
    }
}
