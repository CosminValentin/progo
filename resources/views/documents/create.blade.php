@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between mb-4">
    <div>
      <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
        Subir documentos
      </h1>
      <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
        Selecciona tus archivos, asigna propietario y súbelos de forma segura.
      </p>
    </div>
    <a href="{{ route('documents.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 hover:shadow-md hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
      <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
  </div>
@endsection

@section('content')
  {{-- Mensajes --}}
  @if(session('error'))
    <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow-sm">
      {{ session('error') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow-sm">
      <strong>Corrige los errores:</strong>
      <ul class="list-disc list-inside text-sm mt-1 space-y-0.5">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data"
        x-data="uploader()" x-init="init()"
        class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-lg p-8 space-y-10 transition-all duration-300 hover:shadow-xl">
    @csrf

    {{-- Errores dinámicos --}}
    <template x-if="errors.length">
      <div class="rounded-lg border border-rose-200 dark:border-rose-700 bg-rose-50 dark:bg-rose-900/40 text-rose-800 dark:text-rose-200 p-4 text-sm space-y-1 animate-fade-in">
        <strong class="block mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Revisa lo siguiente:</strong>
        <ul class="list-disc list-inside">
          <template x-for="(err,i) in errors" :key="i">
            <li x-text="err"></li>
          </template>
        </ul>
      </div>
    </template>

    {{-- Zona Drag & Drop --}}
    <section aria-labelledby="filesHeading" class="space-y-4">
      <div class="flex items-baseline justify-between">
        <h2 id="filesHeading" class="text-sm font-medium text-gray-700 dark:text-slate-300">Archivos</h2>
        <span class="text-xs text-gray-500 dark:text-slate-400">Máx. por archivo: 20 MB · Máx. total: 100 MB</span>
      </div>

      <div x-ref="drop"
           @dragover.prevent="drag=true"
           @dragleave.prevent="drag=false"
           @drop.prevent="handleDrop($event)"
           :class="drag ? 'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50/60 dark:bg-slate-700/40' : 'border-dashed border-gray-300 dark:border-slate-600 hover:border-indigo-400/80 hover:bg-indigo-50/30 dark:hover:bg-slate-700/20'"
           class="rounded-2xl border-2 p-10 text-center transition-all duration-200 ease-out cursor-pointer">
        <div class="flex flex-col items-center gap-3 text-gray-600 dark:text-slate-300">
          <i class="fa-solid fa-cloud-arrow-up text-4xl opacity-80"></i>
          <p class="text-sm">
            Arrastra tus archivos aquí o
            <label class="text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 underline cursor-pointer font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded px-1">
              selecciónalos manualmente
              <input type="file" name="files[]" multiple class="hidden" @change="handlePick($event)">
            </label>
          </p>
          <p class="text-xs text-gray-400">Imágenes, PDFs y otros formatos comunes.</p>
        </div>
      </div>

      {{-- Lista de archivos --}}
      <template x-if="files.length">
        <div class="mt-6 space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="(f,idx) in files" :key="idx">
              <div class="group relative rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="relative bg-gray-50 dark:bg-slate-800 aspect-video flex items-center justify-center">
                  <template x-if="isImage(f)">
                    <img :src="fileURL(f)" alt="" class="h-full w-full object-contain">
                  </template>

                  <template x-if="isPDF(f)">
                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-slate-400">
                      <i class="fa-regular fa-file-pdf text-3xl mb-2"></i>
                      <span class="text-xs">PDF</span>
                    </div>
                  </template>

                  <template x-if="!isImage(f) && !isPDF(f)">
                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-slate-400">
                      <i class="fa-regular fa-file text-3xl mb-2"></i>
                      <span class="text-xs" x-text="ext(f)"></span>
                    </div>
                  </template>

                  <button type="button"
                          class="absolute top-2 right-2 bg-white/90 dark:bg-slate-900/90 border border-gray-200 dark:border-slate-700 rounded-full px-2 py-1 text-rose-600 text-xs shadow-sm hover:bg-white dark:hover:bg-slate-800 transition"
                          @click="remove(idx)">
                    <i class="fa-solid fa-xmark"></i> Quitar
                  </button>
                </div>
                <div class="px-3 py-2 text-sm flex items-center justify-between gap-3">
                  <span class="truncate font-medium" :title="f.name" x-text="f.name"></span>
                  <span class="shrink-0 tabular-nums text-gray-500 dark:text-slate-400" x-text="prettySize(f.size)"></span>
                </div>
              </div>
            </template>
          </div>

          {{-- Total --}}
          <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40 px-4 py-3 flex items-center justify-between shadow-sm">
            <div class="text-sm text-gray-600 dark:text-slate-300 flex items-center gap-2">
              <i class="fa-regular fa-folder-open text-indigo-500"></i>
              Seleccionados: <span class="font-semibold" x-text="files.length"></span>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-sm"
                    :class="withinTotal ? 'text-gray-700 dark:text-slate-200' : 'text-rose-600 dark:text-rose-400 font-semibold'">
                Total: <span class="tabular-nums" x-text="prettySize(totalSize)"></span>
              </span>
              <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full"
                    :class="withinTotal ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200'">
                <i :class="withinTotal ? 'fa-solid fa-check-circle' : 'fa-solid fa-circle-exclamation'"></i>
                <span x-text="withinTotal ? 'Dentro del límite' : 'Supera el límite'"></span>
              </span>
            </div>
          </div>
        </div>
      </template>

      <div class="hidden">
        <input type="file" name="files[]" multiple x-ref="hiddenInput">
      </div>

      @error('files')   <p class="text-rose-600 text-sm mt-2">{{ $message }}</p> @enderror
      @error('files.*') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
    </section>

    {{-- Campos adicionales --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium mb-1">Tipo (opcional)</label>
        <div class="relative">
          <i class="fa-solid fa-tags absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 text-xs"></i>
          <input type="text" name="tipo" value="{{ old('tipo') }}"
                 class="w-full pl-8 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                 placeholder="Ejemplo: Contrata, CV, Acuerdo…">
        </div>
        @error('tipo') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="md:col-span-2">
        @include('documents._form', [
          'participants' => $participants,
          'companies'    => $companies,
          'offers'       => $offers,
        ])
      </div>

      <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="protegido" value="1" class="rounded border-gray-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500">
          <i class="fa-solid fa-shield-halved text-indigo-500"></i>
          Marcar como protegido (no se podrá eliminar)
        </label>
      </div>
    </section>

    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
      <a href="{{ route('documents.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
        <i class="fa-solid fa-circle-xmark"></i> Cancelar
      </a>
      <button type="button"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium shadow-md hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed transition-all"
              :disabled="!files.length || uploading || !withinTotal || hasFileTooBig"
              @click="syncAndSubmit($el)">
        <i class="fa-solid fa-cloud-arrow-up" x-show="!uploading"></i>
        <i class="fa-solid fa-circle-notch fa-spin" x-show="uploading"></i>
        <span x-show="!uploading">Subir documentos</span>
        <span x-show="uploading">Subiendo…</span>
      </button>
    </div>
  </form>
@endsection

@section('scripts')
<script>
  function uploader(){
    const MAX_FILE_SIZE_BYTES  = 20 * 1024 * 1024;
    const MAX_TOTAL_SIZE_BYTES = 100 * 1024 * 1024;
    return {
      files: [],
      totalSize: 0,
      drag: false,
      uploading: false,
      errors: [],
      withinTotal: true,
      hasFileTooBig: false,
      init(){},
      isImage(f){ return /^image\//.test(f.type); },
      isPDF(f){ return f.type === 'application/pdf'; },
      ext(f){ const m = (f.name||'').match(/\.([a-z0-9]+)$/i); return m ? m[1].toUpperCase() : 'FILE'; },
      fileURL(f){ return URL.createObjectURL(f); },
      recompute(){
        this.totalSize = this.files.reduce((a,f)=>a+(f?.size||0),0);
        this.withinTotal = this.totalSize <= MAX_TOTAL_SIZE_BYTES;
        this.hasFileTooBig = this.files.some(f => (f?.size||0) > MAX_FILE_SIZE_BYTES);
      },
      pushWithValidation(list){
        const newOnes = [], errs = [];
        for (const f of list){
          if (f.size > MAX_FILE_SIZE_BYTES) errs.push(`“${f.name}” supera los 20 MB.`);
          else newOnes.push(f);
        }
        if (errs.length) this.errors.push(...errs);
        if (newOnes.length) this.files.push(...newOnes);
        this.recompute();
        if (!this.withinTotal)
          this.errors.push('La suma total supera los 100 MB. Quita archivos o sube en varias tandas.');
      },
      handlePick(e){ this.errors=[]; this.pushWithValidation([...e.target.files]); e.target.value=''; },
      handleDrop(e){ this.drag=false; this.errors=[]; this.pushWithValidation([...e.dataTransfer.files]); },
      remove(i){ this.files.splice(i,1); this.recompute(); },
      prettySize(b){ if (!Number.isFinite(b)) return '—'; const u=['B','KB','MB','GB']; const i=Math.floor(Math.log(b)/Math.log(1024)); return (b/1024**i).toFixed(i?1:0)+' '+u[i]; },
      async syncAndSubmit(btn){
        if (!this.files.length || this.uploading || !this.withinTotal || this.hasFileTooBig) return;
        this.uploading = true;
        const form = btn.closest('form');
        const fd = new FormData(form);
        fd.delete('files[]');
        this.files.forEach(f => fd.append('files[]', f));
        try {
          const r = await fetch(form.action, { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: fd });
          if (r.redirected) return window.location = r.url;
          const html = await r.text(); document.open(); document.write(html); document.close();
        } catch {
          alert('Error al subir los archivos. Inténtalo nuevamente.');
        } finally { this.uploading = false; }
      }
    }
  }
</script>
@endsection
