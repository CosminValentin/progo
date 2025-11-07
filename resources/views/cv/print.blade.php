<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>CV — {{ $p->nombre }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: { 50:'#eef2ff', 100:'#e0e7ff', 200:'#c7d2fe', 300:'#a5b4fc', 400:'#818cf8', 500:'#6366f1', 600:'#4f46e5', 700:'#4338ca', 800:'#3730a3', 900:'#312e81' } } } } }
  </script>
  <style>
    @media print {
      @page { margin: 14mm 12mm; }
      .no-print { display:none !important; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      .sheet { box-shadow:none !important; border:0 !important; }
      a { color: inherit !important; text-decoration: none !important; }
    }
    /* bullets bonitos */
    .prose ul { list-style: none; padding-left: 0; }
    .prose ul li { position: relative; padding-left: 1.25rem; }
    .prose ul li::before {
      content: '';
      position: absolute; left: 0.35rem; top: 0.55rem;
      width: 6px; height: 6px; border-radius: 9999px; background: #6366f1;
    }
  </style>
</head>
<body class="bg-slate-100 p-6">
  {{-- Barra superior solo pantalla --}}
  <div class="no-print mb-4 flex justify-end gap-2">
    <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50">Volver</a>
    <button onclick="window.print()" class="px-5 py-2.5 rounded-lg bg-brand-600 text-white hover:bg-brand-700 shadow">Imprimir / Guardar PDF</button>
  </div>

  {{-- Hoja / Lona principal --}}
  <article class="sheet max-w-5xl mx-auto bg-white rounded-2xl shadow-xl ring-1 ring-black/5 overflow-hidden grid grid-cols-[280px,1fr]">
    {{-- SIDEBAR IZQUIERDO --}}
    <aside class="bg-slate-50 px-7 py-8 border-r border-slate-200">
      {{-- Foto + Nombre (compacto en sidebar para look real) --}}
      <div class="flex flex-col items-center text-center">
        @php $imgSrc = $data['foto_data_uri'] ?? $data['foto_url'] ?? null; @endphp
        @if($imgSrc)
          <img src="{{ $imgSrc }}" alt="Foto"
               class="w-32 h-32 rounded-2xl object-cover ring-1 ring-slate-300 shadow-md mb-4">
        @endif
        <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ $p->nombre }}</h1>
        @if(!empty($data['titulo']))
          <p class="text-brand-700 font-medium mt-1">{{ $data['titulo'] }}</p>
        @endif
      </div>

      {{-- Separador --}}
      <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>

      {{-- Contacto --}}
      <section class="space-y-2">
        <h2 class="text-xs tracking-wider text-slate-500 font-semibold">CONTACTO</h2>
        <div class="mt-2 text-[13px] leading-6 text-slate-700">
          <div class="flex gap-2 items-center">
            <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M2 6.5A2.5 2.5 0 0 1 4.5 4h15A2.5 2.5 0 0 1 22 6.5v11A2.5 2.5 0 0 1 19.5 20h-15A2.5 2.5 0 0 1 2 17.5v-11Zm2.06-.22 7.94 5.29 7.94-5.29A1 1 0 0 0 19.5 5h-15a1 1 0 0 0-.44 1.28Z"/></svg>
            <span>{{ $data['email'] ?? $p->email ?? '—' }}</span>
          </div>
          <div class="flex gap-2 items-center">
            <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.3.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C11.85 21 3 12.15 3 1a1 1 0 0 1 1-1h2.49a1 1 0 0 1 1 1c0 1.28.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z"/></svg>
            <span>{{ $data['telefono'] ?? $p->telefono ?? '—' }}</span>
          </div>
          <div class="flex gap-2 items-center">
            <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C7.58 2 4 5.58 4 10c0 5.25 6.9 11.41 7.2 11.68.46.41 1.15.41 1.61 0C13.1 21.41 20 15.25 20 10c0-4.42-3.58-8-8-8Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg>
            <span>{{ $data['direccion'] ?? $p->provincia ?? '—' }}</span>
          </div>
          <div class="flex gap-2 items-center">
            <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5Zm0 2c-3.33 0-10 1.67-10 5v1a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-1c0-3.33-6.67-5-10-5Z"/></svg>
            <span>DNI/NIE: {{ $p->dni_nie }}</span>
          </div>
          @if(!empty($data['fecha_nac']))
            <div class="flex gap-2 items-center">
              <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1.5A2.5 2.5 0 0 1 22 6.5v13A2.5 2.5 0 0 1 19.5 22h-15A2.5 2.5 0 0 1 2 19.5v-13A2.5 2.5 0 0 1 4.5 4H6V3a1 1 0 0 1 1-1Zm13 8H4v9.5c0 .28.22.5.5.5h15a.5.5 0 0 0 .5-.5V10Z"/></svg>
              <span>F. Nac.: {{ \Carbon\Carbon::parse($data['fecha_nac'])->format('d/m/Y') }}</span>
            </div>
          @endif
        </div>
      </section>

      {{-- Redes --}}
      @php
        $links = collect([
          'LinkedIn' => $data['linkedin'] ?? null,
          'GitHub'   => $data['github'] ?? null,
          'Web'      => $data['web'] ?? null,
        ])->filter();
      @endphp
      @if($links->count())
        <div class="my-6 h-px bg-slate-200"></div>
        <section>
          <h2 class="text-xs tracking-wider text-slate-500 font-semibold">REDES</h2>
          <ul class="mt-2 space-y-1 text-[13px] text-slate-700">
            @foreach($links as $label => $url)
              <li class="truncate"><span class="font-medium">{{ $label }}:</span> <span class="text-brand-700">{{ $url }}</span></li>
            @endforeach
          </ul>
        </section>
      @endif

      {{-- Habilidades (barras) --}}
      @php
        $skills = collect(explode(',', $data['habilidades'] ?? ''))->map(fn($s)=>trim($s))->filter()->values();
      @endphp
      @if($skills->count())
        <div class="my-6 h-px bg-slate-200"></div>
        <section>
          <h2 class="text-xs tracking-wider text-slate-500 font-semibold">HABILIDADES</h2>
          <div class="mt-3 space-y-2">
            @foreach($skills as $s)
              <div>
                <div class="text-[13px] text-slate-700 mb-1">{{ $s }}</div>
                {{-- barra simple estética (70%-90% aleatoria para mostrar look) --}}
                @php $pct = 70 + (crc32($s) % 21); @endphp
                <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                  <div class="h-2 bg-brand-500 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      {{-- Idiomas (barras 1–5) --}}
      @php
        $langs = collect($data['lang'] ?? [])->filter(fn($l)=>!empty($l['nombre']) && !empty($l['nivel']));
      @endphp
      @if($langs->count())
        <div class="my-6 h-px bg-slate-200"></div>
        <section>
          <h2 class="text-xs tracking-wider text-slate-500 font-semibold">IDIOMAS</h2>
          <div class="mt-3 space-y-2">
            @foreach($langs as $l)
              @php $nivel = max(1, min(5, (int)($l['nivel']))); @endphp
              <div class="flex items-center justify-between text-[13px]">
                <span class="text-slate-700">{{ $l['nombre'] }}</span>
                <span class="text-amber-600 font-mono">{!! str_repeat('●', $nivel) !!}{!! str_repeat('○', 5-$nivel) !!}</span>
              </div>
              <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ $nivel*20 }}%"></div>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      {{-- Intereses (chips) --}}
      @php
        $intereses = collect(explode(',', $data['intereses'] ?? ''))->map(fn($s)=>trim($s))->filter()->values();
      @endphp
      @if($intereses->count())
        <div class="my-6 h-px bg-slate-200"></div>
        <section>
          <h2 class="text-xs tracking-wider text-slate-500 font-semibold">INTERESES</h2>
          <div class="mt-2 flex flex-wrap gap-2">
            @foreach($intereses as $it)
              <span class="px-2.5 py-1 rounded-full text-[12px] bg-slate-100 text-slate-700 border border-slate-200">{{ $it }}</span>
            @endforeach
          </div>
        </section>
      @endif
    </aside>

    {{-- COLUMNA PRINCIPAL --}}
    <main class="p-9">
      {{-- PERFIL --}}
      @if(!empty($data['resumen']))
        <section class="mb-7">
          <h2 class="text-sm tracking-wider text-slate-500 font-semibold">PERFIL</h2>
          <div class="mt-2 text-[15px] text-slate-800 leading-7">
            {{ $data['resumen'] }}
          </div>
        </section>
      @endif

      {{-- EXPERIENCIA (línea de tiempo) --}}
      @php
        $exp = collect($data['exp'] ?? [])->filter(fn($e)=>!empty($e['puesto']) || !empty($e['empresa']) || !empty($e['desc']));
      @endphp
      @if($exp->count())
        <section class="mb-7">
          <h2 class="text-sm tracking-wider text-slate-500 font-semibold">EXPERIENCIA</h2>
          <ol class="relative mt-4 border-l border-slate-200 space-y-6">
            @foreach($exp as $e)
              <li class="ml-4">
                <div class="absolute -left-[7px] mt-1 w-3 h-3 bg-brand-500 rounded-full ring-4 ring-white"></div>
                <div class="flex items-baseline justify-between gap-3">
                  <h3 class="text-[15px] font-semibold text-slate-900">
                    {{ $e['puesto'] ?? '' }}
                    @if(!empty($e['empresa'])) <span class="text-slate-600 font-normal">· {{ $e['empresa'] }}</span> @endif
                  </h3>
                  @if(!empty($e['fecha'])) <span class="text-[12px] text-slate-500">{{ $e['fecha'] }}</span> @endif
                </div>
                @if(!empty($e['desc']))
                  <div class="prose mt-1 text-[14px] leading-6 text-slate-700">
                    <ul>
                      @foreach(preg_split('/\r\n|\r|\n/', trim($e['desc'])) as $linea)
                        @if(strlen(trim($linea))) <li>{{ trim($linea) }}</li> @endif
                      @endforeach
                    </ul>
                  </div>
                @endif
              </li>
            @endforeach
          </ol>
        </section>
      @endif

      {{-- FORMACIÓN --}}
      @php
        $edu = collect($data['edu'] ?? [])->filter(fn($e)=>!empty($e['titulo']) || !empty($e['centro']));
      @endphp
      @if($edu->count())
        <section class="mb-7">
          <h2 class="text-sm tracking-wider text-slate-500 font-semibold">FORMACIÓN</h2>
          <div class="mt-3 space-y-3">
            @foreach($edu as $e)
              <div class="flex items-baseline justify-between gap-3">
                <div>
                  <div class="text-[15px] font-semibold text-slate-900">{{ $e['titulo'] ?? '' }}</div>
                  <div class="text-[13px] text-slate-600">{{ $e['centro'] ?? '' }}</div>
                </div>
                @if(!empty($e['fecha']))
                  <div class="text-[12px] text-slate-500">{{ $e['fecha'] }}</div>
                @endif
              </div>
            @endforeach
          </div>
        </section>
      @endif

      {{-- PROYECTOS --}}
      @php
        $proy = collect($data['proy'] ?? [])->filter(fn($e)=>!empty($e['titulo']) || !empty($e['desc']) || !empty($e['link']));
      @endphp
      @if($proy->count())
        <section class="mb-2">
          <h2 class="text-sm tracking-wider text-slate-500 font-semibold">PROYECTOS</h2>
          <div class="mt-3 space-y-4">
            @foreach($proy as $e)
              <div>
                <div class="flex items-baseline justify-between gap-3">
                  <div class="text-[15px] font-semibold text-slate-900">{{ $e['titulo'] ?? '' }}</div>
                  @if(!empty($e['link']))
                    <div class="text-[12px] text-brand-700 truncate">{{ $e['link'] }}</div>
                  @endif
                </div>
                @if(!empty($e['desc']))
                  <p class="mt-1 text-[14px] text-slate-700 leading-6">{{ $e['desc'] }}</p>
                @endif
              </div>
            @endforeach
          </div>
        </section>
      @endif
    </main>
  </article>
</body>
</html>
            