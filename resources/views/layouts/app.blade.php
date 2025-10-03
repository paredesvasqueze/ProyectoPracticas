{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Estilos personalizados -->
    <style>
        .nav-link { 
            color: white; 
            border-radius: 6px; 
            padding: 8px 12px; 
            margin-bottom: 4px; 
            transition: all 0.3s ease; 
        }
        .nav-link:hover, .nav-link.active { 
            background-color: white !important; 
            color: #99001F !important; 
            font-weight: bold; 
        }
        button:focus { outline: none !important; box-shadow: none !important; }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        {{-- Sidebar incluido en todas las vistas --}}
        @include('layouts.sidebar')

        {{-- Contenido de cada página --}}
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para toggle del menú -->
    <script>
        function toggleMenu(menuId, btn) {
            const menu = document.getElementById(menuId);
            menu.classList.toggle('show'); // abrir/cerrar menú

            // Negrita al abrir/cerrar
            if(menu.classList.contains('show')){
                btn.classList.add('fw-bold');
            } else {
                btn.classList.remove('fw-bold');
            }

            // Rotar flecha
            const icon = btn.querySelector('i.bi');
            icon.classList.toggle('bi-chevron-down');
            icon.classList.toggle('bi-chevron-up');
        }

        // Hover para negrita sin abrir menú
        document.querySelectorAll('.menu-toggle').forEach(btn => {
            btn.addEventListener('mouseenter', () => btn.classList.add('fw-bold'));
            btn.addEventListener('mouseleave', () => {
                const menuId = btn.getAttribute('onclick').match(/'(.*?)'/)[1];
                const menu = document.getElementById(menuId);
                if(!menu.classList.contains('show')){
                    btn.classList.remove('fw-bold');
                }
            });
        });
    </script>
</body>
</html>

