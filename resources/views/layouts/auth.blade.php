<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'PRO-GO')</title>
    <!-- Aquí puedes agregar estilos CSS, Tailwind u otro framework -->
  <script>tailwind = { config: { darkMode: 'class' } }</script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" referrerpolicy="no-referrer"/>    
</head>
<body class="bg-gray-100">

    @yield('content')

</body>
</html>
