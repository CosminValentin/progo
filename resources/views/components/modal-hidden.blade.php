{{-- Inserta campos y utilidades cuando el form se carga dentro de una modal inline --}}
@if(request()->boolean('modal'))
  <input type="hidden" name="modal" value="1">
  <input type="hidden" name="return_to" value="{{ request('return_to', url()->previous()) }}">

  {{-- Utilidad: convertir enlaces con data-modal-cancel en botón que cierra la modal --}}
  <script>
    (function(){
      const cancelLinks = document.querySelectorAll('[data-modal-cancel]');
      cancelLinks.forEach(a=>{
        a.addEventListener('click', function(e){
          e.preventDefault();
          window.dispatchEvent(new CustomEvent('modal-close-inline'));
        });
      });
    })();
  </script>
@endif
