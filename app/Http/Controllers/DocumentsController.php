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
        // Listas para asignar propietario (opcional)
        $participants = Participant::orderBy('nombre')->get(['id','nombre']);
        $companies    = Company::orderBy('nombre')->get(['id','nombre']);
        $offers       = Offer::orderBy('id')->get(['id','puesto']);

        return view('documents.create', compact('participants', 'companies', 'offers'));
    }

    /**
     * Mostrar el documento en el navegador (inline).
     * Busca primero en storage/app/documents (DISK local).
     */
    public function show(Document $document)
    {
        [$disk, $path] = $this->resolveStorageLocation($document);

        if (!$disk || !$path) {
            abort(404, 'Archivo no encontrado.');
        }

        // Soporte para path absoluto en /public (fallback muy raro)
        if ($disk === 'absolute_public') {
            $mime = mime_content_type($path) ?: 'application/octet-stream';
            return response()->file($path, [
                'Content-Type'        => $mime,
                'Content-Disposition' => 'inline; filename="'.$document->nombre_archivo.'"',
            ]);
        }

        $mime   = $document->tipo ?: (Storage::disk($disk)->mimeType($path) ?: 'application/octet-stream');
        $stream = Storage::disk($disk)->readStream($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="'.$document->nombre_archivo.'"',
        ]);
    }

    // STORE (subida múltiple opcional)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'files'      => ['required', 'array', 'min:1'],
            'files.*'    => ['file', 'max:20480'], // 20 MB
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

        foreach ($request->file('files', []) as $file) {
            $origName = $file->getClientOriginalName();
            $ext      = strtolower($file->getClientOriginalExtension());
            $mimetype = $file->getMimeType();

            // Hash único + mantener extensión si hay
            $hash = sha1(uniqid('', true)).'_'.bin2hex(random_bytes(6));
            if ($ext) { $hash .= '.'.$ext; }

            // Guardar en storage/app/documents -> DISK 'local'
            $path = 'documents/'.$hash;
            Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));

            Document::create([
                'owner_type'     => $ownerTypeKey ?: null, // 'participants', 'companies', 'offers', 'users'
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

    /**
     * Descargar (attachment).
     */
    public function download(Document $document)
    {
        [$disk, $path] = $this->resolveStorageLocation($document);

        if (!$disk || !$path) {
            return back()->with('error', 'El fichero no existe en el servidor.');
        }

        if ($disk === 'absolute_public') {
            return response()->download($path, $document->nombre_archivo);
        }

        $abs = Storage::disk($disk)->path($path);
        return response()->download($abs, $document->nombre_archivo);
    }

    // DELETE (respeta protegido)
    public function destroy(Document $document)
    {
        if ($document->protegido) {
            return back()->with('error', 'No puedes eliminar un documento protegido.');
        }

        try {
            [$disk, $path] = $this->resolveStorageLocation($document);
            if ($disk && $path) {
                if ($disk === 'absolute_public' && is_file($path)) {
                    @unlink($path);
                } else if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            }

            $document->delete();

            return back()->with('success', 'Documento eliminado correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el documento.');
        }
    }

    /**
     * Localiza el archivo físico probando ubicaciones típicas.
     * Devuelve [disk, relative_path] o [null, null] si no existe.
     */
    private function resolveStorageLocation(Document $document): array
    {
        // 1) Donde lo guardamos al subir: DISK 'local' en storage/app/documents/{hash}
        $localCandidates = [
            'documents/'.$document->hash,  // nuestra ruta principal
            $document->hash,               // por si se guardó sin carpeta
        ];
        foreach ($localCandidates as $rel) {
            if (Storage::disk('local')->exists($rel)) {
                return ['local', $rel];
            }
        }

        // 2) Si en algún momento migraste a 'public' (storage/app/public/...)
        $publicCandidates = [
            'documents/'.$document->hash,
            'uploads/'.$document->hash,
            'files/'.$document->hash,
            $document->hash,
        ];
        foreach ($publicCandidates as $rel) {
            if (Storage::disk('public')->exists($rel)) {
                return ['public', $rel];
            }
        }

        // 3) Último intento: archivo directo en /public (no recomendado)
        $publicPath = public_path($document->hash);
        if (is_file($publicPath)) {
            // Path absoluto especial (sin disk de Storage)
            return ['absolute_public', $publicPath];
        }

        return [null, null];
    }
}
