<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Participant;
use App\Models\Company;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // INDEX: buscador simple "q"
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $docs = Document::with(['owner', 'uploader'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nombre_archivo', 'like', "%{$q}%")
                      ->orWhere('tipo', 'like', "%{$q}%")
                      ->orWhere('owner_type', 'like', "%{$q}%")
                      ->orWhere('owner_id', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('fecha')
            ->paginate(10)
            ->withQueryString();

        $total       = Document::count();
        $tProtected  = Document::where('protegido', true)->count();

        return view('documents.index', compact('docs', 'q', 'total', 'tProtected'));
    }

    // CREATE
    public function create()
    {
        // Listas rápidas para chips (sin IDs manuales)
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        $companies    = Company::orderBy('nombre')->get(['id','nombre']);
        $offers       = Offer::orderBy('id')->get(['id','puesto']);

        return view('documents.create', compact('participants', 'companies', 'offers'));
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'files'      => ['required', 'array', 'min:1'],
            'files.*'    => ['file', 'max:20480'], // 20 MB por archivo
            'tipo'       => ['nullable', 'string', 'max:30'],
            'owner_type' => ['nullable', Rule::in(['participants','companies','offers','users'])],
            'owner_id'   => ['nullable', 'integer', 'min:1'],
            'protegido'  => ['nullable', 'boolean'],
        ]);

        // Validación cruzada propietario
        $ownerTypeKey = $validated['owner_type'] ?? null;
        $ownerId      = $validated['owner_id']   ?? null;
        if (($ownerTypeKey && !$ownerId) || (!$ownerTypeKey && $ownerId)) {
            return back()->withInput()->with('error', 'Si indicas propietario, debes indicar también su tipo e ID.');
        }

        // Guardar cada archivo
        foreach ($request->file('files', []) as $file) {
            $origName = $file->getClientOriginalName();
            $basename = pathinfo($origName, PATHINFO_FILENAME);
            $ext      = strtolower($file->getClientOriginalExtension());
            $mimetype = $file->getMimeType();

            // Generamos hash único + conservamos extensión
            $hash = sha1(uniqid('', true)).'_'.bin2hex(random_bytes(6));
            if ($ext) {
                $hash .= '.'.$ext;
            }

            // Guardar en storage/app/documents
            $path = 'documents/'.$hash;
            Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));

            // Ojo: owner_type ahora almacena la CLAVE del morphMap (no la clase)
            Document::create([
                'owner_type'     => $ownerTypeKey ?: null,     // 'participants', 'companies', 'offers', 'users'
                'owner_id'       => $ownerId ?: null,
                'tipo'           => $validated['tipo'] ?: ($mimetype ?? null),
                'nombre_archivo' => $origName,
                'hash'           => $hash,
                'uploader_id'    => auth()->id(),
                'fecha'          => now(),
                'protegido'      => (bool) $request->boolean('protegido'),
            ]);
        }

        return redirect()->route('documents.index')->with('success', 'Documento(s) subido(s) correctamente.');
    }

    // DESCARGA
    public function download(Document $document)
    {
        $path = $document->storagePath();
        if (!Storage::disk('local')->exists($path)) {
            return back()->with('error', 'El fichero no existe en el servidor.');
        }
        return Storage::disk('local')->download($path, $document->nombre_archivo);
    }

    // DELETE (respeta protegido)
    public function destroy(Document $document)
    {
        if ($document->protegido) {
            return back()->with('error', 'No puedes eliminar un documento protegido.');
        }

        try {
            // Borrar fichero si existe
            $path = $document->storagePath();
            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            $document->delete();

            return back()->with('success', 'Documento eliminado correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }
}
