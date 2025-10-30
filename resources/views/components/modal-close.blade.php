{{-- resources/views/components/modal-close.blade.php --}}
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cerrar</title>
  </head>
  <body style="background:#0b0b0b; color:#fff; font:14px/1.4 system-ui,Segoe UI,Roboto;">
    <div style="display:grid;place-items:center;min-height:40vh;opacity:.8">
      <div>Guardado. Cerrando…</div>
    </div>
    <script>
      window.parent?.postMessage({modal:'close-reload'}, '*');
    </script>
  </body>
</html>
