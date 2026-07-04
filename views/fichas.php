<?php

session_start();

require_once "../models/FichaModel.php";

$fichas                   = FichaModel::listar();
$totalFichas              = FichaModel::totalFichas();
$totalActivas             = FichaModel::totalActivas();
$totalSuspendidas         = FichaModel::totalSuspendidas();
$totalAprendicesAsignados = FichaModel::totalAprendicesAsignados();

require_once "layout/header.php";

?>

<style>

/* ══════════════════════════════════════
   RESET & BASE
══════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --verde:        #39A900;
    --verde-oscuro: #1f6b00;
    --verde-claro:  #eaf7e0;
    --negro:        #0d1b08;
    --negro-suave:  #15290e;
    --bg:           #f4f6f9;
    --card-radius:  14px;
    --shadow:       0 2px 12px rgba(0,0,0,.07);
}

body {
    background: var(--bg);
    font-family: 'Segoe UI', Roboto, Arial, sans-serif;
    font-size: 14px; color: #1a1a1a;
}

/* ══════════════════════════════════════
   LAYOUT
══════════════════════════════════════ */
.dash-layout { display: flex; min-height: 100vh; }

.sidebar {
    width: 220px; flex-shrink: 0;
    position: fixed; top: 0; left: 0; bottom: 0;
    overflow-y: auto;
    background: linear-gradient(180deg, var(--negro) 0%, var(--negro-suave) 100%);
    padding: 22px 16px;
    display: flex; flex-direction: column; justify-content: space-between;
    z-index: 1000;
}

.sidebar-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
.sidebar-brand .icon-box {
    width: 40px; height: 40px; background: var(--verde); border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff; flex-shrink: 0;
}
.sidebar-brand h5    { color: #fff; font-size: 14px; font-weight: 700; margin: 0; }
.sidebar-brand small { color: #b9c9b0; font-size: 10px; display: block; margin-top: 1px; }

.sidebar-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px; margin-bottom: 4px;
    color: #cfd9c8; text-decoration: none; font-size: 13px; transition: .15s;
}
.sidebar-nav a i      { width: 16px; text-align: center; }
.sidebar-nav a:hover  { background: rgba(57,169,0,.18); color: #fff; }
.sidebar-nav a.active { background: var(--verde); color: #fff; font-weight: 600; }

.sidebar-bottom { margin-top: 20px; }

.sidebar-biometric {
    background: rgba(57,169,0,.15); border: 1px solid rgba(57,169,0,.4);
    border-radius: 12px; padding: 10px 12px;
    display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
}
.sidebar-biometric .icon-box {
    width: 32px; height: 32px; background: var(--verde); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 14px; flex-shrink: 0;
}
.sidebar-biometric strong { color: #fff; font-size: 12px; display: block; }
.sidebar-biometric span   { color: #7ddc5a; font-size: 11px; }
.sidebar-biometric span i { font-size: 8px; margin-right: 3px; }

.sidebar-user {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px; background: rgba(255,255,255,.06);
}
.sidebar-user .avatar {
    width: 32px; height: 32px; background: var(--verde); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 13px; flex-shrink: 0;
}
.sidebar-user .info p    { color: #fff; font-size: 12px; font-weight: 600; margin: 0; }
.sidebar-user .info span { color: #9aa39a; font-size: 11px; }

.main-area {
    margin-left: 220px; flex: 1;
    display: flex; flex-direction: column; min-height: 100vh;
}

/* ══════════════════════════════════════
   TOPBAR
══════════════════════════════════════ */
.topbar {
    background: #fff; padding: 14px 24px;
    border-bottom: 1px solid #eaeaea;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 999;
}
.topbar-left h4    { font-size: 18px; font-weight: 700; margin: 0; }
.topbar-left small { color: #888; font-size: 12px; }
.topbar-right      { display: flex; align-items: center; gap: 14px; }
.time-box .hora    { font-size: 20px; font-weight: 700; color: #1a1a1a; line-height: 1; text-align: right; }
.time-box .fecha   { font-size: 11px; color: #888; margin-top: 2px; text-align: right; }

.notif-btn {
    position: relative; width: 36px; height: 36px;
    background: #f4f6f4; border: 1px solid #e8e8e8; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #555; font-size: 15px; cursor: pointer;
}
.notif-btn .badge-dot {
    position: absolute; top: -4px; right: -4px;
    background: var(--verde); color: #fff;
    font-size: 9px; font-weight: 700; width: 17px; height: 17px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.user-btn {
    display: flex; align-items: center; gap: 8px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px; padding: 8px 14px;
    font-size: 13px; font-weight: 600; cursor: pointer;
}

/* ══════════════════════════════════════
   CONTENIDO
══════════════════════════════════════ */
.content { padding: 24px; flex: 1; }

/* ── Cabecera ── */
.page-topbar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
}
.page-topbar-left h2 { font-size: 20px; font-weight: 700; margin: 0 0 2px; }
.page-topbar-left p  { color: #888; font-size: 13px; margin: 0; }

.page-topbar-actions { display: flex; gap: 10px; }

.btn-excel {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: #e8f5e9; color: #1a6b2e;
    border: 1.5px solid #c8e6c9; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-excel:hover { background: #c8e6c9; color: #1a6b2e; }

.btn-nuevo {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s, transform .1s;
}
.btn-nuevo:hover { background: var(--verde-oscuro); color: #fff; transform: translateY(-1px); }

/* ── Alertas ── */
.alerta {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
    position: relative;
}
.alerta i { font-size: 15px; flex-shrink: 0; }
.alerta-success { background: #e8f5e9; color: #1a6b2e; border: 1px solid #c8e6c9; }
.alerta-info    { background: #e8f0fe; color: #1565c0; border: 1px solid #c5d8fb; }
.alerta-warning { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.alerta-danger  { background: #fdecea; color: #c62828; border: 1px solid #ffcdd2; }

.alerta-close {
    margin-left: auto; background: none; border: none;
    cursor: pointer; color: inherit; font-size: 16px; opacity: .6;
    padding: 0; line-height: 1;
}
.alerta-close:hover { opacity: 1; }

/* ══════════════════════════════════════
   STATS
══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 20px;
}

.stat-card {
    background: #fff; border-radius: var(--card-radius);
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: var(--shadow); border: 1px solid #eef1ee;
}
.stat-card .ic {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.ic-blue   { background: #e8f0fe; color: #1a73e8; }
.ic-green  { background: var(--verde-claro); color: var(--verde); }
.ic-yellow { background: #fff6e0; color: #e6a400; }
.ic-purple { background: #f3e5f5; color: #7b1fa2; }

.stat-label { font-size: 12px; color: #888; margin-bottom: 4px; }
.stat-num   { font-size: 28px; font-weight: 700; line-height: 1; color: #1a1a1a; }
.stat-sub   { font-size: 11px; color: #aaa; margin-top: 3px; }
.stat-sub.ok { color: var(--verde); }
.stat-sub.wr { color: #e6a400; }

/* ══════════════════════════════════════
   TABLA
══════════════════════════════════════ */
.table-card {
    background: #fff; border-radius: var(--card-radius);
    overflow: hidden; box-shadow: var(--shadow);
}

.table-card-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 14px 22px;
    display: flex; align-items: center; justify-content: space-between;
}
.table-card-header-left {
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}
.total-badge {
    background: rgba(255,255,255,.2);
    border-radius: 20px; padding: 3px 10px;
    font-size: 12px; margin-left: 4px;
}

/* Herramientas búsqueda/filtro */
.table-tools {
    padding: 14px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}

.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-wrap i {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: #bbb; font-size: 13px;
}
.search-wrap input {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border: 1.5px solid #e8e8e8; border-radius: 10px;
    font-size: 13px; color: #333;
    outline: none; background: #fafafa; transition: border-color .2s;
}
.search-wrap input:focus        { border-color: var(--verde); background: #fff; }
.search-wrap input::placeholder { color: #ccc; }

.filtro-select {
    padding: 9px 32px 9px 12px;
    border: 1.5px solid #e8e8e8; border-radius: 10px;
    font-size: 13px; color: #555;
    outline: none; background: #fafafa;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23aaa' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer; min-width: 160px;
    transition: border-color .2s;
}
.filtro-select:focus { border-color: var(--verde); }

/* Tabla */
.tabla-fichas { width: 100%; border-collapse: collapse; font-size: 13.5px; }

.tabla-fichas thead tr { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla-fichas thead th {
    padding: 11px 14px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}

.tabla-fichas tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla-fichas tbody tr:last-child { border-bottom: none; }
.tabla-fichas tbody tr:hover   { background: #f8fffe; }
.tabla-fichas tbody td         { padding: 12px 14px; color: #444; vertical-align: middle; }

/* Número de ficha */
.num-ficha {
    display: inline-flex; align-items: center;
    padding: 4px 10px;
    background: #f0faf3; border: 1.5px solid #d4edda;
    border-radius: 8px; font-size: 13px; font-weight: 700; color: #1a6b2e;
    font-family: monospace;
}

/* Programa */
.programa-nombre { font-weight: 600; color: #222; font-size: 13px; }
.programa-nivel  { font-size: 11px; color: #999; margin-top: 2px; }

/* Jornada */
.jornada-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #e8f0fe; color: #1565c0; border: 1px solid #c5d8fb;
}

/* Instructor */
.instructor-cell {
    display: flex; align-items: center; gap: 8px;
}
.mini-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--verde-claro);
    display: flex; align-items: center; justify-content: center;
    color: var(--verde); font-size: 12px; flex-shrink: 0;
}

/* Barra de cupos */
.cupo-info { display: flex; justify-content: space-between; margin-bottom: 4px; }
.cupo-nums { font-size: 12px; font-weight: 600; color: #333; }
.cupo-pct  { font-size: 11px; color: #aaa; }
.cupo-barra {
    height: 5px; background: #eee; border-radius: 4px; overflow: hidden;
}
.cupo-barra-fill { height: 100%; border-radius: 4px; background: var(--verde); }
.cupo-barra-fill.lleno { background: #e53935; }
.cupo-barra-fill.casi  { background: #e6a400; }

/* Fechas */
.fecha-row { font-size: 12px; color: #555; line-height: 1.6; }
.fecha-row b { color: #333; }

/* Estado badges */
.estado-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.estado-activo     { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }
.estado-suspendido { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.estado-finalizado { background: #f0f0f0; color: #666;    border: 1px solid #ddd; }

/* Acciones */
.acciones-cell { display: flex; align-items: center; gap: 6px; justify-content: center; }

.btn-accion {
    width: 32px; height: 32px;
    border: none; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; text-decoration: none;
    transition: transform .15s, opacity .15s; cursor: pointer;
}
.btn-accion:hover { transform: translateY(-2px); opacity: .85; }
.btn-ver      { background: #e3f2fd; color: #1565c0; }
.btn-editar   { background: #fff8e1; color: #f57f17; }
.btn-opciones { background: #f0f0f0; color: #555; }

/* Empty state */
.empty-state { text-align: center; padding: 50px 20px; color: #bbb; }
.empty-state i { font-size: 44px; margin-bottom: 12px; display: block; color: #d4edda; }
.empty-state p { font-size: 14px; margin-bottom: 14px; }
.btn-crear-primera {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
}

</style>

<div class="dash-layout">

    <!-- ══ SIDEBAR ══ -->
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">
                <div class="icon-box"><i class="fas fa-seedling"></i></div>
                <div>
                    <h5>BioAsist SENA</h5>
                    <small>Sistema Biométrico</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a>
                <a href="aprendices.php"><i class="fas fa-user-graduate"></i> Aprendices</a>
                <a href="instructores.php"><i class="fas fa-chalkboard-teacher"></i> Instructores</a>
                <a href="fichas.php" class="active"><i class="fas fa-folder"></i> Fichas</a>
                <a href="programas.php"><i class="fas fa-book"></i> Programas</a>
                <a href="asistencia.php"><i class="fas fa-fingerprint"></i> Asistencia</a>
                <a href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes</a>
                <a href="historial.php"><i class="fas fa-clock"></i> Historial</a>
                <a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="sidebar-biometric">
                <div class="icon-box"><i class="fas fa-fingerprint"></i></div>
                <div>
                    <strong>Sistema biométrico</strong>
                    <span><i class="fas fa-circle"></i> Conectado</span>
                </div>
            </div>
            <div class="sidebar-user">
                <div class="avatar"><i class="fas fa-user"></i></div>
                <div class="info">
                    <p><?php echo $_SESSION["nombre"] ?? "Usuario"; ?></p>
                    <span>Administrador</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ ÁREA PRINCIPAL ══ -->
    <div class="main-area">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h4>Fichas</h4>
                <small>Sistema Inteligente de Control de Asistencia</small>
            </div>
            <div class="topbar-right">
                <div class="time-box">
                    <div class="hora" id="horaActual"></div>
                    <div class="fecha" id="fechaActual"></div>
                </div>
                <div class="notif-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge-dot">3</span>
                </div>
                <div class="dropdown">
                    <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i>
                        <?php echo $_SESSION["nombre"] ?? "Usuario"; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-gear me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-right-from-bracket me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="content">

            <!-- Cabecera + botones -->
            <div class="page-topbar">
                <div class="page-topbar-left">
                    <h2><i class="fas fa-folder" style="color:var(--verde);"></i> Gestión de Fichas</h2>
                    <p>Administra las fichas, programas de formación y aprendices asignados.</p>
                </div>
                <div class="page-topbar-actions">
                    <a href="../reports/exportar_fichas_excel.php" class="btn-excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="crear_ficha.php" class="btn-nuevo">
                        <i class="fas fa-plus"></i> Nueva ficha
                    </a>
                </div>
            </div>

            <!-- ALERTAS -->
            <?php if(isset($_GET["mensaje"])): ?>
                <?php
                    $alertas = [
                        "creada"            => ["success", "check-circle",        "La ficha fue creada correctamente."],
                        "actualizada"       => ["success", "check-circle",        "La ficha fue actualizada correctamente."],
                        "estado_actualizado"=> ["info",    "info-circle",         "El estado de la ficha fue actualizado."],
                        "eliminada"         => ["success", "trash-alt",           "La ficha fue eliminada correctamente."],
                        "tiene_aprendices"  => ["warning", "triangle-exclamation","No se puede eliminar esta ficha porque tiene aprendices asignados. Puedes suspenderla o finalizarla."],
                        "error"             => ["danger",  "circle-xmark",        "Ocurrió un error. Intenta nuevamente."],
                        "error_eliminar"    => ["danger",  "circle-xmark",        "Ocurrió un error al eliminar. Intenta nuevamente."],
                    ];
                    $msg = $_GET["mensaje"];
                    if(isset($alertas[$msg])):
                        [$tipo, $icono, $texto] = $alertas[$msg];
                ?>
                    <div class="alerta alerta-<?= $tipo ?>" id="alertaMensaje">
                        <i class="fas fa-<?= $icono ?>"></i>
                        <?= $texto ?>
                        <button class="alerta-close" onclick="document.getElementById('alertaMensaje').remove()">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- STATS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="ic ic-blue"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="stat-label">Total fichas</div>
                        <div class="stat-num"><?= $totalFichas["total"] ?? 0 ?></div>
                        <div class="stat-sub">Registradas en el sistema</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-circle-check"></i></div>
                    <div>
                        <div class="stat-label">Fichas activas</div>
                        <div class="stat-num"><?= $totalActivas["total"] ?? 0 ?></div>
                        <div class="stat-sub ok">Disponibles para matrícula</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-yellow"><i class="fas fa-pause-circle"></i></div>
                    <div>
                        <div class="stat-label">Suspendidas</div>
                        <div class="stat-num"><?= $totalSuspendidas["total"] ?? 0 ?></div>
                        <div class="stat-sub wr">No admiten nuevos aprendices</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-purple"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <div class="stat-label">Aprendices asignados</div>
                        <div class="stat-num"><?= $totalAprendicesAsignados["total"] ?? 0 ?></div>
                        <div class="stat-sub">Distribuidos entre fichas</div>
                    </div>
                </div>

            </div>

            <!-- TABLA -->
            <div class="table-card">

                <div class="table-card-header">
                    <div class="table-card-header-left">
                        <i class="fas fa-list"></i>
                        Listado de fichas
                        <span class="total-badge" id="totalCount"><?= count($fichas) ?> registros</span>
                    </div>
                </div>

                <div class="table-tools">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscarFicha"
                            placeholder="Buscar ficha, programa o instructor...">
                    </div>
                    <select id="filtroEstadoFicha" class="filtro-select">
                        <option value="">Todos los estados</option>
                        <option value="Activa">Activas</option>
                        <option value="Suspendida">Suspendidas</option>
                        <option value="Finalizada">Finalizadas</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="tabla-fichas" id="tablaFichas">

                        <thead>
                            <tr>
                                <th>Ficha</th>
                                <th>Programa</th>
                                <th>Jornada</th>
                                <th>Instructor</th>
                                <th>Cupos</th>
                                <th>Fechas</th>
                                <th>Estado</th>
                                <th style="text-align:center;">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if(empty($fichas)): ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-folder-open"></i>
                                            <p>No hay fichas registradas todavía.</p>
                                            <a href="crear_ficha.php" class="btn-crear-primera">
                                                <i class="fas fa-plus"></i> Crear primera ficha
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($fichas as $f):
                                    $tot = (int)($f["total_aprendices"] ?? 0);
                                    $cupo = (int)($f["cupo_maximo"] ?? 0);
                                    $pct  = $cupo > 0 ? min(100, round(($tot / $cupo) * 100)) : 0;
                                    $barraClass = $pct >= 100 ? "lleno" : ($pct >= 80 ? "casi" : "");
                                ?>
                                    <tr class="fila-ficha" data-estado="<?= htmlspecialchars($f["estado"]) ?>">

                                        <td>
                                            <span class="num-ficha">
                                                <?= htmlspecialchars($f["numero_ficha"]) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="programa-nombre">
                                                <?= htmlspecialchars($f["programa"] ?? "Sin programa") ?>
                                            </div>
                                            <div class="programa-nivel">
                                                <?= htmlspecialchars($f["nivel_formacion"] ?? "Sin nivel") ?>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="jornada-badge">
                                                <i class="fas fa-clock"></i>
                                                <?= htmlspecialchars($f["jornada"] ?? "Sin jornada") ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="instructor-cell">
                                                <div class="mini-avatar">
                                                    <i class="fas fa-chalkboard-user"></i>
                                                </div>
                                                <span><?= htmlspecialchars($f["instructor"] ?? "Sin asignar") ?></span>
                                            </div>
                                        </td>

                                        <td style="min-width:140px;">
                                            <div class="cupo-info">
                                                <span class="cupo-nums"><?= $tot ?> / <?= $cupo ?></span>
                                                <span class="cupo-pct"><?= $pct ?>%</span>
                                            </div>
                                            <div class="cupo-barra">
                                                <div class="cupo-barra-fill <?= $barraClass ?>" style="width:<?= $pct ?>%;"></div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="fecha-row">
                                                <b>Inicio:</b>
                                                <?= !empty($f["fecha_inicio"]) ? date("d/m/Y", strtotime($f["fecha_inicio"])) : "—" ?>
                                                <br>
                                                <b>Fin:</b>
                                                <?= !empty($f["fecha_fin"])    ? date("d/m/Y", strtotime($f["fecha_fin"]))    : "—" ?>
                                            </div>
                                        </td>

                                        <td>
                                            <?php if($f["estado"] === "Activa"): ?>
                                                <span class="estado-badge estado-activo">
                                                    <i class="fas fa-circle" style="font-size:7px;"></i> Activa
                                                </span>
                                            <?php elseif($f["estado"] === "Suspendida"): ?>
                                                <span class="estado-badge estado-suspendido">
                                                    <i class="fas fa-pause-circle"></i> Suspendida
                                                </span>
                                            <?php else: ?>
                                                <span class="estado-badge estado-finalizado">
                                                    <i class="fas fa-flag-checkered"></i> Finalizada
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="acciones-cell">

                                                <a href="detalle_ficha.php?id=<?= $f["id"] ?>"
                                                   class="btn-accion btn-ver" title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="editar_ficha.php?id=<?= $f["id"] ?>"
                                                   class="btn-accion btn-editar" title="Editar ficha">
                                                    <i class="fas fa-pen"></i>
                                                </a>

                                                <div class="dropdown">
                                                    <button class="btn-accion btn-opciones dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        title="Más opciones">
                                                        <i class="fas fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">

                                                        <?php if($f["estado"] !== "Activa"): ?>
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                   href="../controllers/FichaController.php?accion=estado&id=<?= $f["id"] ?>&estado=Activa">
                                                                    <i class="fas fa-circle-check me-2"></i>Reactivar ficha
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($f["estado"] !== "Suspendida"): ?>
                                                            <li>
                                                                <a class="dropdown-item text-warning"
                                                                   href="../controllers/FichaController.php?accion=estado&id=<?= $f["id"] ?>&estado=Suspendida">
                                                                    <i class="fas fa-pause-circle me-2"></i>Suspender ficha
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($f["estado"] !== "Finalizada"): ?>
                                                            <li>
                                                                <a class="dropdown-item text-primary"
                                                                   href="../controllers/FichaController.php?accion=estado&id=<?= $f["id"] ?>&estado=Finalizada">
                                                                    <i class="fas fa-flag-checkered me-2"></i>Finalizar ficha
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                               href="../controllers/FichaController.php?accion=eliminar&id=<?= $f["id"] ?>"
                                                               onclick="return confirm('¿Eliminar esta ficha? Solo funcionará si no tiene aprendices asignados.')">
                                                                <i class="fas fa-trash me-2"></i>Eliminar ficha
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>

                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            </div>

        </div><!-- /content -->

    </div><!-- /main-area -->

</div><!-- /dash-layout -->

<script>

/* ── Reloj ── */
function actualizarHora(){
    const now = new Date();
    document.getElementById("horaActual").textContent =
        now.toLocaleTimeString('es-CO');
    document.getElementById("fechaActual").textContent =
        now.toLocaleDateString('es-CO', { day:'numeric', month:'long', year:'numeric' });
}
actualizarHora();
setInterval(actualizarHora, 1000);

/* ── Filtros ── */
document.addEventListener("DOMContentLoaded", function(){
    const buscar       = document.getElementById("buscarFicha");
    const filtroEstado = document.getElementById("filtroEstadoFicha");
    const filas        = document.querySelectorAll(".fila-ficha");
    const badge        = document.getElementById("totalCount");

    function filtrar(){
        const texto  = buscar.value.toLowerCase().trim();
        const estado = filtroEstado.value;
        let visibles  = 0;

        filas.forEach(fila => {
            const ok = fila.textContent.toLowerCase().includes(texto) &&
                       (estado === "" || fila.dataset.estado === estado);
            fila.style.display = ok ? "" : "none";
            if(ok) visibles++;
        });

        badge.textContent = visibles + " registro" + (visibles !== 1 ? "s" : "");
    }

    buscar.addEventListener("input", filtrar);
    filtroEstado.addEventListener("change", filtrar);
});

</script>

<?php require_once "layout/footer.php"; ?>