{{-- resources/views/components/modal-remote.blade.php --}}
<div
  x-data="{
    open:false, url:null, title:'', loading:false,
    openModal(u){ this.url = u; this.open = true; this.loading = true; },
    close(){ this.open=false; this.url=null; this.loading=false; },
    onLoad(){ this.loading=false; }
  }"
  x-on:modal-open.window="
    const u = $event.detail?.url || '';
    const t = $event.detail?.title || '';
    openModal(u);
    title = t || 'Formulario';
  "
  x-init="
    window.addEventListener('message', (e)=>{
      if(!e?.data) return;
      if(e.data.modal === 'close-reload'){
        close(); window.location.reload();
      }else if(e.data.modal === 'close'){
        close();
      }
    });
  "
  x-cloak
>
  <div x-show="open" class="fixed inset-0 z-[100]">
    <div class="absolute inset-0 bg-black/40" @click="close()" x-transition.opacity></div>

    <div class="absolute inset-0 p-4 sm:p-6 lg:p-10 overflow-auto">
      <div x-show="open" x-transition
           class="mx-auto w-full max-w-3xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
          <h3 class="text-sm font-semibold text-gray-800 dark:text-slate-100" x-text="title"></h3>
          <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-slate-800" @click="close()">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div class="relative">
          <div x-show="loading" class="absolute inset-0 grid place-items-center bg-white/60 dark:bg-slate-900/60 z-10">
            <div class="animate-spin h-6 w-6 border-2 border-indigo-600 border-t-transparent rounded-full"></div>
          </div>

          {{-- El componente añade ?modal=1 automáticamente --}}
          <iframe
            x-show="open"
            :src="url + (url.includes('?') ? '&' : '?') + 'modal=1'"
            class="w-full h-[70vh] rounded-b-2xl"
            @load="onLoad()"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
