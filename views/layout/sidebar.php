<?php
$pagina = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">

    <!-- Logo -->
    <div class="sidebar-header">

        <img src="../assets/img/logo-sena.png" alt="Logo SENA">

        <div class="logo-text">
            <h2>BioAsist</h2>
            <span>Sistema Biométrico</span>
        </div>

    </div>

    <!-- Menú -->
    <ul class="sidebar-menu">

        <li class="<?= $pagina == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="<?= $pagina == 'aprendices.php' ? 'active' : '' ?>">
            <a href="aprendices.php">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Aprendices</span>
            </a>
        </li>

        <li class="<?= $pagina == 'instructores.php' ? 'active' : '' ?>">
            <a href="instructores.php">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Instructores</span>
            </a>
        </li>

        <li class="<?= $pagina == 'fichas.php' ? 'active' : '' ?>">
            <a href="fichas.php">
                <i class="fa-solid fa-folder-open"></i>
                <span>Fichas</span>
            </a>
        </li>

        <li class="<?= $pagina == 'programas.php' ? 'active' : '' ?>">
            <a href="programas.php">
                <i class="fa-solid fa-book"></i>
                <span>Programas</span>
            </a>
        </li>

        <li class="<?= $pagina == 'asistencia.php' ? 'active' : '' ?>">
            <a href="asistencia.php">
                <i class="fa-solid fa-fingerprint"></i>
                <span>Asistencia</span>
            </a>
        </li>

        <li class="<?= $pagina == 'reportes.php' ? 'active' : '' ?>">
            <a href="reportes.php">
                <i class="fa-solid fa-chart-column"></i>
                <span>Reportes</span>
            </a>
        </li>

        <li class="<?= $pagina == 'historial.php' ? 'active' : '' ?>">
            <a href="historial.php">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Historial</span>
            </a>
        </li>

        <li class="<?= $pagina == 'configuracion.php' ? 'active' : '' ?>">
            <a href="configuracion.php">
                <i class="fa-solid fa-gear"></i>
                <span>Configuración</span>
            </a>
        </li>

    </ul>

    <!-- Usuario -->
    <div class="sidebar-user">

        <img src="../assets/img/user.png" alt="Usuario">

        <div>

            <h5><?= $_SESSION["nombre"] ?? "Usuario"; ?></h5>

            <span>Administrador</span>

        </div>

    </div>

</aside>