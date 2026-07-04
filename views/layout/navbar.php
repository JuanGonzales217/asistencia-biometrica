<div class="topbar d-flex justify-content-between align-items-center bg-white shadow-sm rounded-4 p-3 mb-4">

    <div>

        <h3 class="mb-1 fw-bold">
            Dashboard
        </h3>

        <small class="text-muted">
            Sistema Inteligente de Control de Asistencia
        </small>

    </div>

    <div class="d-flex align-items-center">

        <!-- Hora -->

        <div class="text-end me-4">

            <div id="horaActual" class="fw-bold fs-5"></div>

            <small id="fechaActual" class="text-muted"></small>

        </div>

        <!-- Campana -->

        <button class="btn btn-light position-relative me-3">

            <i class="fa-solid fa-bell"></i>

            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                3

            </span>

        </button>

        <!-- Usuario -->

        <div class="dropdown">

    <button
        type="button"
        class="btn btn-success dropdown-toggle"
        data-bs-toggle="dropdown"
        aria-expanded="false">

        <i class="fa-solid fa-user"></i>
        <?= $_SESSION["nombre"] ?>

    </button>

    <ul class="dropdown-menu dropdown-menu-end">

        <li>
            <a class="dropdown-item" href="#">
                <i class="fa-solid fa-user me-2"></i>
                Mi Perfil
            </a>
        </li>

        <li>
            <a class="dropdown-item" href="#">
                <i class="fa-solid fa-gear me-2"></i>
                Configuración
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item text-danger" href="../logout.php">
                <i class="fa-solid fa-right-from-bracket me-2"></i>
                Cerrar sesión
            </a>
        </li>

    </ul>

</div>

    </div>

</div>