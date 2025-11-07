<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExportUsersController extends Controller
{
    public function download(Request $request)
    {
        $format = strtolower($request->get('format', 'json'));
        $users  = User::orderBy('id', 'desc')->get();

        $payload = $users->map(function (User $u) {
            return [
                'id'                    => $u->id,
                'email'                 => $u->email,
                'dni'                   => $u->dni,
                'nombre'                => $u->first_name,
                'apellido1'             => $u->last_name1,
                'apellido2'             => $u->last_name2,
                'fecha_nacimiento'      => optional($u->birth_date)->format('Y-m-d'),
                'sexo'                  => $u->gender,
                'nivel_formativo'       => $u->education_level,
                'residente_comunitario' => (int) $u->eu_resident,
                'created_at'            => optional($u->created_at)->toJSON(),
                'updated_at'            => optional($u->updated_at)->toJSON(),
            ];
        });

        // Por ahora devolvemos JSON. Si luego quieres CSV/XLSX lo añadimos.
        return response()->json($payload);
    }
}
