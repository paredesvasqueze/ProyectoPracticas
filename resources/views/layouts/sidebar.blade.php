{{-- resources/views/layouts/sidebar.blade.php --}}
<div class="text-white p-3 d-flex flex-column position-fixed" 
     style="width: 250px; height: 100vh; background-color: #99001F;">
    <div class="text-center mb-4">
        <h4 class="fw-bold">Sistema EFSRT</h4>
    </div>

    <div id="menuSidebar">

        <!-- Sección: Mantenedores -->
        <div class="mb-2">
            <button class="btn w-100 text-start text-white d-flex align-items-center justify-content-between menu-toggle"
                    style="background-color: transparent; border: none;"
                    onclick="toggleMenu('mantenedoresMenu', this)">
                <span><i class="bi bi-gear-fill me-2"></i> Mantenedores</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul id="mantenedoresMenu" class="nav flex-column ms-3 collapse {{ request()->is('usuarios*') || request()->is('empresas*') || request()->is('estudiantes*') || request()->is('docentes*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('usuarios*') ? 'active' : '' }}" 
                       href="{{ route('usuarios.index') }}">
                        <i class="bi bi-people-fill me-2"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('empresas*') ? 'active' : '' }}" 
                       href="{{ route('empresas.index') }}">
                        <i class="bi bi-building me-2"></i> Empresas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('estudiantes*') ? 'active' : '' }}" 
                       href="{{ route('estudiantes.index') }}">
                        <i class="bi bi-mortarboard-fill me-2"></i> Estudiantes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('docentes*') ? 'active' : '' }}" 
                       href="{{ route('docentes.index') }}">
                        <i class="bi bi-person-badge-fill me-2"></i> Docentes
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sección: Procesos -->
        <div class="mb-2">
            <button class="btn w-100 text-start text-white d-flex align-items-center justify-content-between menu-toggle"
                    style="background-color: transparent; border: none;"
                    onclick="toggleMenu('procesosMenu', this)">
                <span><i class="bi bi-diagram-3-fill me-2"></i> Procesos</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul id="procesosMenu" class="nav flex-column ms-3 collapse {{ request()->is('cartas*') || request()->is('supervisiones*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cartas*') ? 'active' : '' }}" 
                       href="{{ route('cartas.index') }}">
                        <i class="bi bi-file-earmark-text-fill me-2"></i> Carta de Presentación
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('supervisiones*') ? 'active' : '' }}" 
                       href="{{ route('supervisiones.index') }}">
                        <i class="bi bi-journal-check me-2"></i> Supervisiones
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sección: Documentos -->
        <div class="mb-2">
            <button class="btn w-100 text-start text-white d-flex align-items-center justify-content-between menu-toggle"
                    style="background-color: transparent; border: none;"
                    onclick="toggleMenu('documentosMenu', this)">
                <span><i class="bi bi-folder-fill me-2"></i> Documentos</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul id="documentosMenu" class="nav flex-column ms-3 collapse {{ request()->is('documentos*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('documentos*') ? 'active' : '' }}" 
                       href="{{ route('documentos.index') }}">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Documentos Generales
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sección: Reportes -->
        <div class="mb-2">
            <button class="btn w-100 text-start text-white d-flex align-items-center justify-content-between menu-toggle"
                    style="background-color: transparent; border: none;"
                    onclick="toggleMenu('reportesMenu', this)">
                <span><i class="bi bi-file-text-fill me-2"></i> Reportes</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul id="reportesMenu" class="nav flex-column ms-3 collapse {{ request()->is('reportes*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}" 
                    href="{{ route('reportes.index') }}">
                        <i class="bi bi-clipboard-data-fill me-2"></i> Reportes Generales
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>

<script>
function toggleMenu(menuId, button) {
    const menu = document.getElementById(menuId);
    menu.classList.toggle('show');

    // Cambiar icono de flecha
    const icon = button.querySelector('i.bi-chevron-down, i.bi-chevron-up');
    if(icon.classList.contains('bi-chevron-down')){
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    } else {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    }
}
</script>

