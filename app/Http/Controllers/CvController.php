<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTADO
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $docs = Document::with(['owner' => function ($q) {
                            $q->select('id','nombre');
                        }, 'uploader'])
                        ->where('tipo', 'cv')
                        ->where('owner_type', 'participants')
                        ->when($q, function ($query) use ($q) {
                            $query->where(function ($sub) use ($q) {
                                $sub->where('nombre_archivo', 'like', "%{$q}%")
                                    ->orWhere('hash', 'like', "%{$q}%")
                                    ->orWhereHas('owner', fn($p) =>
                                        $p->where('nombre', 'like', "%{$q}%")
                                          ->orWhere('id', $q)
                                    );
                            });
                        })
                        ->orderByDesc('fecha')
                        ->paginate(10)
                        ->withQueryString();

        $total       = Document::where('tipo','cv')->where('owner_type','participants')->count();
        $tProtected  = Document::where('tipo','cv')->where('owner_type','participants')->where('protegido',1)->count();

        return view('cv.index', compact('docs','q','total','tProtected'));
    }

    // NUEVO
    public function create()
    {
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        return view('cv.create', compact('participants'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => ['required','exists:participants,id'],
            'file'           => ['required','file','max:20480'], // 20MB
            'protegido'      => ['sometimes','boolean'],
        ]);

        $file     = $request->file('file');
        $origName = $file->getClientOriginalName();
        $ext      = strtolower($file->getClientOriginalExtension());
        $mimetype = $file->getClientMimeType();
        $hash     = sha1_file($file->getRealPath()) . '_' . Str::random(10);
        if ($ext) {
            $hash .= '.' . $ext;
        }

        $path = 'documents/' . $hash;
        Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));

        Document::create([
            'owner_type'     => 'participants',          // morphMap key
            'owner_id'       => (int) $validated['participant_id'],
            'tipo'           => 'cv',                    // SIEMPRE 'cv'
            'nombre_archivo' => $origName,
            'hash'           => $hash,
            'uploader_id'    => auth()->id(),
            'fecha'          => now(),
            'protegido'      => (bool) $request->boolean('protegido'),
        ]);

        return redirect()->route('cvs.index')->with('success','CV subido correctamente.');
    }

    // EDITAR
    public function edit(Document $cv)
    {
        // Solo permitir editar documentos que realmente sean tipo CV de participante
        if ($cv->tipo !== 'cv' || $cv->owner_type !== 'participants') {
            return redirect()->route('cvs.index')->with('error','Este registro no es un CV válido.');
        }

        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        return view('cv.edit', compact('cv','participants'));
    }

    // ACTUALIZAR (puede cambiar de participante y opcionalmente reemplazar el archivo)
    public function update(Request $request, Document $cv)
    {
        if ($cv->tipo !== 'cv' || $cv->owner_type !== 'participants') {
            return redirect()->route('cvs.index')->with('error','Este registro no es un CV válido.');
        }

        $validated = $request->validate([
            'participant_id' => ['required','exists:participants,id'],
            'file'           => ['nullable','file','max:20480'], // 20MB
            'protegido'      => ['sometimes','boolean'],
        ]);

        // Cambios básicos
        $cv->owner_type = 'participants';
        $cv->owner_id   = (int) $validated['participant_id'];
        $cv->protegido  = (bool) $request->boolean('protegido');
        $cv->tipo       = 'cv'; // forzado

        // Reemplazo de archivo (opcional)
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $ext      = strtolower($file->getClientOriginalExtension());
            $hash     = sha1_file($file->getRealPath()) . '_' . Str::random(10);
            if ($ext) {
                $hash .= '.' . $ext;
            }

            // Borrar el anterior si existe
            if (!empty($cv->hash)) {
                $old = 'documents/'.$cv->hash;
                if (Storage::disk('local')->exists($old)) {
                    Storage::disk('local')->delete($old);
                }
            }

            Storage::disk('local')->put('documents/'.$hash, file_get_contents($file->getRealPath()));
            $cv->hash           = $hash;
            $cv->nombre_archivo = $origName;
            $cv->fecha          = now(); // actualizamos fecha si se reemplaza
            $cv->uploader_id    = auth()->id();
        }

        $cv->save();

        return redirect()->route('cvs.index')->with('success','CV actualizado correctamente.');
    }

    // DESCARGAR
    public function download(Document $cv): StreamedResponse
    {
        if ($cv->tipo !== 'cv' || $cv->owner_type !== 'participants') {
            return redirect()->route('cvs.index')->with('error','Este registro no es un CV válido.');
        }

        $path = 'documents/'.$cv->hash;
        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error','El fichero no existe en el almacenamiento.');
        }

        return Storage::disk('local')->download($path, $cv->nombre_archivo ?? 'cv'.($cv->id).'.dat');
    }

    // ELIMINAR
    public function destroy(Document $cv)
    {
        if ($cv->tipo !== 'cv' || $cv->owner_type !== 'participants') {
            return redirect()->route('cvs.index')->with('error','Este registro no es un CV válido.');
        }

        if ($cv->protegido) {
            return redirect()->route('cvs.index')->with('error','No se puede eliminar un CV protegido.');
        }

        try {
            // Borrar archivo físico
            if ($cv->hash) {
                $path = 'documents/'.$cv->hash;
                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->delete($path);
                }
            }

            $cv->delete();
            return redirect()->route('cvs.index')->with('success','CV eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('cvs.index')->with('error','No se pudo eliminar el CV.');
        }
    }
}
