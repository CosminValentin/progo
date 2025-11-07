<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Formulario para capturar datos del CV
     */
    public function show(Request $request)
    {
        $participants = Participant::orderBy('nombre')
            ->get(['id','nombre','dni_nie','email','telefono','provincia']);

        return view('cv.generator', compact('participants'));
    }

    /**
     * Previsualización imprimible (HTML -> Imprimir/Guardar PDF)
     * Guarda la foto en storage público y además embebe base64 como fallback.
     */
    public function preview(Request $request)
    {
        $data = $request->validate([
            'participant_id' => ['required','exists:participants,id'],

            // Encabezado / contacto
            'titulo'         => ['nullable','string','max:160'],
            'resumen'        => ['nullable','string','max:4000'],
            'telefono'       => ['nullable','string','max:60'],
            'email'          => ['nullable','email','max:160'],
            'direccion'      => ['nullable','string','max:200'],
            'fecha_nac'      => ['nullable','date'],

            // Redes
            'linkedin'       => ['nullable','url','max:200'],
            'github'         => ['nullable','url','max:200'],
            'web'            => ['nullable','url','max:200'],

            // Foto
            'foto'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],

            // Habilidades
            'habilidades'    => ['nullable','string','max:2000'],

            // Idiomas (hasta 5)
            'lang.*.nombre'  => ['nullable','string','max:80'],
            'lang.*.nivel'   => ['nullable','integer','min:1','max:5'],

            // Experiencia (hasta 5)
            'exp.*.puesto'   => ['nullable','string','max:160'],
            'exp.*.empresa'  => ['nullable','string','max:160'],
            'exp.*.fecha'    => ['nullable','string','max:80'],
            'exp.*.desc'     => ['nullable','string','max:2000'],

            // Formación (hasta 5)
            'edu.*.titulo'   => ['nullable','string','max:160'],
            'edu.*.centro'   => ['nullable','string','max:160'],
            'edu.*.fecha'    => ['nullable','string','max:80'],

            // Proyectos (hasta 5)
            'proy.*.titulo'  => ['nullable','string','max:160'],
            'proy.*.link'    => ['nullable','url','max:200'],
            'proy.*.desc'    => ['nullable','string','max:2000'],

            // Intereses
            'intereses'      => ['nullable','string','max:1000'],
        ]);

        $participant = Participant::findOrFail($data['participant_id']);

        // Foto: guardado + data URI base64 como fallback
        $fotoUrl = null;
        $fotoDataUri = null;

        if ($request->hasFile('foto')) {
            // 1) Guardar en storage público
            $path = $request->file('foto')->store('cv_photos', 'public'); // storage/app/public/cv_photos
            $fotoUrl = Storage::url($path);                               // /storage/cv_photos/...

            // 2) Generar data URI base64 para que se vea aunque falle el symlink
            $file = $request->file('foto');
            $mime = $file->getMimeType() ?: 'image/jpeg';
            $bytes = file_get_contents($file->getRealPath());
            $fotoDataUri = 'data:'.$mime.';base64,'.base64_encode($bytes);
        }

        // Payload para la vista
        $payload = $data;
        $payload['foto_url'] = $fotoUrl;          // /storage/... (rápido si el symlink funciona)
        $payload['foto_data_uri'] = $fotoDataUri; // base64 (fallback infalible)

        return view('cv.print', [
            'p'    => $participant,
            'data' => $payload,
        ]);
    }
}
