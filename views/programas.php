<?php

session_start();

require_once "../models/ProgramaModel.php";

$programas = ProgramaModel::listar();
$mensaje   = $_GET["mensaje"] ?? "";

$alertas = [
    "creado"           => ["success", "check-circle",        "Programa registrado correctamente."],
    "actualizado"      => ["success", "check-circle",        "Programa actualizado correctamente."],
    "estado_actualizado"=>["success", "check-circle",        "El estado del programa fue actualizado."],
    "eliminado"        => ["success", "trash-alt",           "Programa eliminado correctamente."],
    "tiene_fichas"     => ["warning", "triangle-exclamation","No se puede eliminar este programa porque tiene fichas registradas."],
    "estado_invalido"  => ["danger",  "circle-xmark",        "El estado seleccionado no es válido."],
    "error"            => ["danger",  "circle-xmark",        "Ocurrió un error. Intenta nuevamente."],
];

$alerta = $alertas[$mensaje] ?? null;

$totalProgramas  = count($programas);
$totalActivos    = count(array_filter($programas, fn($p) => $p["estado"] === "Activo"));
$totalAprendices = array_sum(array_column($programas, "total_aprendices"));
$totalFichas     = array_sum(array_column($programas, "total_fichas"));

include "layout/header.php";

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
.page-header-card {
    background: #fff; border-radius: var(--card-radius);
    padding: 20px 24px; margin-bottom: 20px;
    box-shadow: var(--shadow); border-left: 5px solid var(--verde);
    display: flex; align-items: center; gap: 16px;
}
.page-header-icon {
    width: 50px; height: 50px;
    background: linear-gradient(135deg, var(--verde), var(--verde-oscuro));
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 21px; flex-shrink: 0;
}
.page-header-card h2 { font-size: 20px; font-weight: 700; margin: 0 0 2px; }
.page-header-card p  { color: #888; font-size: 13px; margin: 0; }

/* ── Alerta ── */
.alerta {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; padding: 12px 16px; border-radius: 10px;
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
    transition: opacity .25s, transform .25s;
}
.alerta-left { display: flex; align-items: center; gap: 8px; }
.alerta.success { background: #e8f5e9; color: #1a6b2e; border: 1px solid #c8e6c9; }
.alerta.warning { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.alerta.danger  { background: #fdecea; color: #c62828; border: 1px solid #ffcdd2; }
.alerta-close {
    background: none; border: none; cursor: pointer;
    color: inherit; font-size: 14px; opacity: .6; padding: 0;
}
.alerta-close:hover { opacity: 1; }

/* ── Stats ── */
.stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
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
.ic-orange { background: #fff3e0; color: #e65100; }
.ic-purple { background: #f3e5f5; color: #7b1fa2; }
.stat-label { font-size: 12px; color: #888; margin-bottom: 4px; }
.stat-num   { font-size: 28px; font-weight: 700; line-height: 1; color: #1a1a1a; }
.stat-sub   { font-size: 11px; color: #aaa; margin-top: 3px; }

/* ── Acciones bar ── */
.actions-bar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
}
.btn-nuevo {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s, transform .1s;
}
.btn-nuevo:hover { background: var(--verde-oscuro); color: #fff; transform: translateY(-1px); }

.btn-recargar {
    width: 36px; height: 36px;
    background: #f4f4f4; color: #666;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; cursor: pointer; transition: background .2s;
}
.btn-recargar:hover { background: #e8e8e8; }

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
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}
.total-badge {
    background: rgba(255,255,255,.2);
    border-radius: 20px; padding: 3px 10px; font-size: 12px; margin-left: 4px;
}

.table-tools {
    padding: 14px 20px; border-bottom: 1px solid #f0f0f0;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-wrap i {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: #bbb; font-size: 13px;
}
.search-wrap input {
    width: 100%; padding: 9px 12px 9px 36px;
    border: 1.5px solid #e8e8e8; border-radius: 10px;
    font-size: 13px; color: #333; outline: none; background: #fafafa;
    transition: border-color .2s;
}
.search-wrap input:focus        { border-color: var(--verde); background: #fff; }
.search-wrap input::placeholder { color: #ccc; }

.filtro-select {
    padding: 9px 32px 9px 12px;
    border: 1.5px solid #e8e8e8; border-radius: 10px;
    font-size: 13px; color: #555; outline: none; background: #fafafa;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23aaa' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    cursor: pointer; min-width: 160px; transition: border-color .2s;
}
.filtro-select:focus { border-color: var(--verde); }

/* Tabla */
.tabla-programas { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.tabla-programas thead tr { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla-programas thead th {
    padding: 11px 14px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}
.tabla-programas tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla-programas tbody tr:last-child { border-bottom: none; }
.tabla-programas tbody tr:hover   { background: #f8fffe; }
.tabla-programas tbody td         { padding: 12px 14px; color: #444; vertical-align: middle; }

/* Código */
.codigo-tag {
    display: inline-block; background: #f0f4ff; color: #3d5a99;
    border: 1px solid #d0dcf5; border-radius: 6px;
    padding: 3px 9px; font-size: 12px; font-weight: 600; font-family: monospace;
}

/* Nombre */
.prog-nombre { font-weight: 600; color: #222; font-size: 13px; }
.prog-desc   { font-size: 11px; color: #999; margin-top: 2px; }

/* Nivel */
.nivel-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7;
}

/* Duración */
.dur-cell { font-size: 13px; color: #555; }
.dur-cell span { font-weight: 600; color: #333; }

/* Badges cantidad */
.cnt-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 28px; height: 24px; padding: 0 8px;
    border-radius: 20px; font-size: 12px; font-weight: 700;
}
.cnt-azul   { background: #e8f0fe; color: #1565c0; border: 1px solid #c5d8fb; }
.cnt-verde  { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }

/* Estado */
.estado-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.estado-activo     { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }
.estado-suspendido { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.estado-finalizado { background: #f0f0f0; color: #666;    border: 1px solid #ddd; }

/* Acciones */
.acciones-cell { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.btn-accion {
    width: 30px; height: 30px; border: none; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; text-decoration: none;
    transition: transform .15s, opacity .15s; cursor: pointer;
}
.btn-accion:hover { transform: translateY(-2px); opacity: .85; }
.btn-ver      { background: #e3f2fd; color: #1565c0; }
.btn-editar   { background: #fff8e1; color: #f57f17; }
.btn-suspender{ background: #fff6e0; color: #b45309; }
.btn-activar  { background: #e8f5e9; color: #2d8f45; }
.btn-finalizar{ background: #f0f0f0; color: #555; }
.btn-eliminar { background: #fdecea; color: #c62828; }

/* Empty state */
.empty-state { text-align: center; padding: 50px 20px; color: #bbb; }
.empty-state i { font-size: 44px; margin-bottom: 12px; display: block; color: #d4edda; }
.empty-state p { font-size: 14px; margin-bottom: 14px; }
.btn-crear-primero {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; background: var(--verde); color: #fff;
    border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
    text-decoration: none;
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
                <a href="fichas.php"><i class="fas fa-folder"></i> Fichas</a>
                <a href="programas.php" class="active"><i class="fas fa-book"></i> Programas</a>
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
                <h4>Programas</h4>
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

            <!-- Cabecera -->
            <div class="page-header-card">
                <div class="page-header-icon">
                    <i class="fas fa-book-open" style="color:white;"></i>
                </div>
                <div>
                    <h2>Gestión de Programas</h2>
                    <p>Administra los programas de formación y consulta sus fichas y aprendices asociados.</p>
                </div>
            </div>

            <!-- Alerta -->
            <?php if($alerta): ?>
                <div class="alerta <?= $alerta[0] ?>" id="alertaPrograma">
                    <div class="alerta-left">
                        <i class="fas fa-<?= $alerta[1] ?>"></i>
                        <?= htmlspecialchars($alerta[2]) ?>
                    </div>
                    <button class="alerta-close" onclick="cerrarAlerta()">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- STATS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="ic ic-blue"><i class="fas fa-book"></i></div>
                    <div>
                        <div class="stat-label">Total programas</div>
                        <div class="stat-num"><?= $totalProgramas ?></div>
                        <div class="stat-sub">registrados</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-circle-check"></i></div>
                    <div>
                        <div class="stat-label">Activos</div>
                        <div class="stat-num"><?= $totalActivos ?></div>
                        <div class="stat-sub">en formación</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-orange"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-label">Aprendices vinculados</div>
                        <div class="stat-num"><?= $totalAprendices ?></div>
                        <div class="stat-sub">en todos los programas</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-purple"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="stat-label">Fichas creadas</div>
                        <div class="stat-num"><?= $totalFichas ?></div>
                        <div class="stat-sub">en todos los programas</div>
                    </div>
                </div>

            </div>

            <!-- BOTÓN + TABLA -->
            <div class="actions-bar">
                <a href="nuevo_programa.php" class="btn-nuevo">
                    <i class="fas fa-plus"></i> Nuevo programa
                </a>
                <button class="btn-recargar" onclick="location.reload()" title="Actualizar">
                    <i class="fas fa-rotate"></i>
                </button>
            </div>

            <div class="table-card">

                <div class="table-card-header">
                    <i class="fas fa-list"></i>
                    Lista de Programas
                    <span class="total-badge" id="totalCount"><?= $totalProgramas ?> registros</span>
                </div>

                <div class="table-tools">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscarPrograma"
                            placeholder="Buscar por código, nombre o nivel...">
                    </div>
                    <select id="filtroEstado" class="filtro-select">
                        <option value="">Todos los estados</option>
                        <option value="Activo">Activos</option>
                        <option value="Suspendido">Suspendidos</option>
                        <option value="Finalizado">Finalizados</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="tabla-programas">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Programa</th>
                                <th>Nivel</th>
                                <th>Duración</th>
                                <th>Fichas</th>
                                <th>Aprendices</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="cuerpoTabla">
                            <?php if(empty($programas)): ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-book-open"></i>
                                            <p>No hay programas registrados todavía.</p>
                                            <a href="crear_programa.php" class="btn-crear-primero">
                                                <i class="fas fa-plus"></i> Crear primer programa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($programas as $p): ?>
                                    <tr data-nombre="<?= htmlspecialchars(strtolower($p["nombre"]." ".$p["codigo"]." ".$p["nivel"])) ?>"
                                        data-estado="<?= htmlspecialchars($p["estado"]) ?>">

                                        <td>
                                            <span class="codigo-tag"><?= htmlspecialchars($p["codigo"]) ?></span>
                                        </td>

                                        <td>
                                            <div class="prog-nombre"><?= htmlspecialchars($p["nombre"]) ?></div>
                                            <?php if(!empty($p["descripcion"])): ?>
                                                <div class="prog-desc"><?= htmlspecialchars($p["descripcion"]) ?></div>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span class="nivel-badge">
                                                <i class="fas fa-layer-group"></i>
                                                <?= htmlspecialchars($p["nivel"]) ?>
                                            </span>
                                        </td>

                                        <td class="dur-cell">
                                            <span><?= htmlspecialchars($p["duracion"]) ?></span> meses
                                        </td>

                                        <td>
                                            <span class="cnt-badge cnt-azul"><?= $p["total_fichas"] ?></span>
                                        </td>

                                        <td>
                                            <span class="cnt-badge cnt-verde"><?= $p["total_aprendices"] ?></span>
                                        </td>

                                        <td>
                                            <?php
                                                $ec = strtolower($p["estado"]);
                                                $claseEstado = match($ec) {
                                                    "activo"     => "estado-activo",
                                                    "suspendido" => "estado-suspendido",
                                                    default      => "estado-finalizado"
                                                };
                                            ?>
                                            <span class="estado-badge <?= $claseEstado ?>">
                                                <i class="fas fa-circle" style="font-size:7px;"></i>
                                                <?= htmlspecialchars($p["estado"]) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="acciones-cell">

                                                <a href="detalle_programa.php?id=<?= $p["id"] ?>"
                                                   class="btn-accion btn-ver" title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="editar_programa.php?id=<?= $p["id"] ?>"
                                                   class="btn-accion btn-editar" title="Editar">
                                                    <i class="fas fa-pen"></i>
                                                </a>

                                                <?php if($p["estado"] === "Activo"): ?>
                                                    <a href="../controllers/ProgramaController.php?accion=estado&id=<?= $p["id"] ?>&estado=Suspendido"
                                                       class="btn-accion btn-suspender" title="Suspender"
                                                       onclick="return confirm('¿Suspender este programa?')">
                                                        <i class="fas fa-pause"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="../controllers/ProgramaController.php?accion=estado&id=<?= $p["id"] ?>&estado=Activo"
                                                       class="btn-accion btn-activar" title="Activar"
                                                       onclick="return confirm('¿Activar este programa?')">
                                                        <i class="fas fa-play"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="../controllers/ProgramaController.php?accion=estado&id=<?= $p["id"] ?>&estado=Finalizado"
                                                   class="btn-accion btn-finalizar" title="Finalizar"
                                                   onclick="return confirm('¿Finalizar este programa?')">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </a>

                                                <a href="../controllers/ProgramaController.php?accion=eliminar&id=<?= $p["id"] ?>"
                                                   class="btn-accion btn-eliminar" title="Eliminar"
                                                   onclick="return confirm('¿Eliminar este programa?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>

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

/* ── Alerta automática ── */
function cerrarAlerta(){
    const el = document.getElementById("alertaPrograma");
    if(!el) return;
    el.style.opacity = "0";
    el.style.transform = "translateY(-8px)";
    setTimeout(() => el.remove(), 260);
}
setTimeout(cerrarAlerta, 6000);

/* ── Filtros ── */
const buscar       = document.getElementById("buscarPrograma");
const filtroEstado = document.getElementById("filtroEstado");
const badge        = document.getElementById("totalCount");

function filtrar(){
    const txt    = buscar.value.toLowerCase().trim();
    const estado = filtroEstado.value;
    const filas  = document.querySelectorAll("#cuerpoTabla tr[data-nombre]");
    let visibles  = 0;

    filas.forEach(fila => {
        const ok = fila.dataset.nombre.includes(txt) &&
                   (estado === "" || fila.dataset.estado === estado);
        fila.style.display = ok ? "" : "none";
        if(ok) visibles++;
    });

    badge.textContent = visibles + " registro" + (visibles !== 1 ? "s" : "");
}

buscar.addEventListener("input", filtrar);
filtroEstado.addEventListener("change", filtrar);

</script>

<?php include "layout/footer.php"; ?>