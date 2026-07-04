<?php

session_start();

if(!isset($_SESSION["nombre"])){
    header("Location: ../index.php");
    exit();
}

require_once "../models/ProgramaModel.php";

if(!isset($_GET["id"]) || empty($_GET["id"])){
    header("Location: programas.php");
    exit();
}

$id      = $_GET["id"];
$programa = ProgramaModel::obtenerPorId($id);

if(!$programa){
    header("Location: programas.php?mensaje=no_encontrado");
    exit();
}

$fichas          = ProgramaModel::obtenerFichasPorPrograma($id);
$totalAprendices = ProgramaModel::totalAprendicesPorPrograma($id);

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
.page-topbar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
}
.page-topbar-left h2 { font-size: 20px; font-weight: 700; margin: 0 0 2px; }
.page-topbar-left p  { color: #888; font-size: 13px; margin: 0; }

.page-topbar-actions { display: flex; gap: 10px; }

.btn-volver {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px; background: #fff; color: #555;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: border-color .2s, color .2s;
}
.btn-volver:hover { border-color: var(--verde); color: var(--verde); }

.btn-nueva-ficha {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: var(--verde-claro); color: var(--verde-oscuro);
    border: 1.5px solid #c8e6c9; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-nueva-ficha:hover { background: #c8e6c9; color: var(--verde-oscuro); }

.btn-editar {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px; background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-editar:hover { background: var(--verde-oscuro); color: #fff; }

/* ══════════════════════════════════════
   HERO DEL PROGRAMA
══════════════════════════════════════ */
.programa-hero {
    background: linear-gradient(135deg, #1a6b2e, #39A900);
    border-radius: var(--card-radius);
    padding: 24px 28px;
    display: flex; align-items: center; gap: 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 24px rgba(31,107,0,.18);
    flex-wrap: wrap;
}

.hero-icon {
    width: 64px; height: 64px;
    background: rgba(255,255,255,.18); border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff; flex-shrink: 0;
}

.hero-info h2  { color: #fff; font-size: 22px; font-weight: 700; margin: 0 0 4px; }
.hero-info p   { color: rgba(255,255,255,.85); font-size: 13px; margin: 0 0 8px; }

.hero-codigo {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.18); border-radius: 20px;
    padding: 4px 12px; font-size: 12px; font-weight: 700;
    color: #fff; font-family: monospace;
}

.hero-estado {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.18); border-radius: 20px;
    padding: 8px 16px; font-size: 13px; font-weight: 600; color: #fff;
}
.hero-estado i { font-size: 8px; }

/* ══════════════════════════════════════
   STATS
══════════════════════════════════════ */
.stats-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
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
.stat-num   { font-size: 26px; font-weight: 700; line-height: 1; color: #1a1a1a; }
.stat-sub   { font-size: 11px; color: #aaa; margin-top: 3px; }

/* ══════════════════════════════════════
   PANEL INFORMACIÓN
══════════════════════════════════════ */
.panel {
    background: #fff; border-radius: var(--card-radius);
    box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px;
}

.panel-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 14px 22px;
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}
.panel-header i { opacity: .85; }

.panel-body { padding: 22px; }

/* Grid de datos */
.datos-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.dato-item {
    background: #fafafa; border: 1px solid #f0f0f0;
    border-radius: 10px; padding: 14px;
}
.dato-item label {
    display: block; font-size: 11px; font-weight: 700;
    color: #aaa; text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 5px;
}
.dato-item span {
    font-size: 14px; color: #222; font-weight: 500;
}
.dato-item.full { grid-column: 1 / -1; }

/* ══════════════════════════════════════
   TABLA FICHAS
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
    border-radius: 20px; padding: 3px 10px; font-size: 12px; margin-left: 4px;
}

.btn-add-ficha {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,.15); color: #fff;
    border: 1px solid rgba(255,255,255,.3); border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-add-ficha:hover { background: rgba(255,255,255,.25); color: #fff; }

.tabla { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.tabla thead tr { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla thead th {
    padding: 11px 16px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}
.tabla tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla tbody tr:last-child { border-bottom: none; }
.tabla tbody tr:hover   { background: #f8fffe; }
.tabla tbody td         { padding: 13px 16px; color: #444; vertical-align: middle; }

.ficha-num {
    display: inline-flex; align-items: center;
    padding: 4px 10px;
    background: #f0faf3; border: 1.5px solid #d4edda;
    border-radius: 8px; font-size: 13px; font-weight: 700;
    color: #1a6b2e; font-family: monospace;
}

.jornada-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #e8f0fe; color: #1565c0; border: 1px solid #c5d8fb;
}

.cnt-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: var(--verde-claro); color: var(--verde-oscuro); border: 1px solid #c8e6c9;
}

.estado-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.estado-activo     { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }
.estado-suspendido { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.estado-finalizado { background: #f0f0f0; color: #666;    border: 1px solid #ddd; }

.empty-state { text-align: center; padding: 44px 20px; color: #bbb; }
.empty-state i { font-size: 40px; margin-bottom: 12px; display: block; color: #d4edda; }
.empty-state p { font-size: 13px; margin-bottom: 14px; }
.btn-crear-ficha {
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
                <h4>Detalle de Programa</h4>
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
            <div class="page-topbar">
                <div class="page-topbar-left">
                    <h2><i class="fas fa-book-open" style="color:var(--verde);"></i> Detalle del programa</h2>
                    <p><?= htmlspecialchars($programa["nombre"]) ?> — <?= htmlspecialchars($programa["nivel"]) ?></p>
                </div>
                <div class="page-topbar-actions">
                    <a href="crear_ficha.php?programa_id=<?= $programa["id"] ?>" class="btn-nueva-ficha">
                        <i class="fas fa-plus"></i> Nueva ficha
                    </a>
                    <a href="editar_programa.php?id=<?= $programa["id"] ?>" class="btn-editar">
                        <i class="fas fa-pen"></i> Editar programa
                    </a>
                    <a href="programas.php" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <!-- HERO -->
            <div class="programa-hero">
                <div class="hero-icon"><i class="fas fa-book-open"></i></div>
                <div class="hero-info">
                    <h2><?= htmlspecialchars($programa["nombre"]) ?></h2>
                    <p><?= htmlspecialchars($programa["nivel"]) ?> · Duración: <?= htmlspecialchars($programa["duracion"]) ?> meses</p>
                    <span class="hero-codigo">
                        <i class="fas fa-hashtag"></i>
                        <?= htmlspecialchars($programa["codigo"]) ?>
                    </span>
                </div>
                <div class="hero-estado">
                    <i class="fas fa-circle"></i>
                    <?= htmlspecialchars($programa["estado"]) ?>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="ic ic-blue"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="stat-label">Fichas asociadas</div>
                        <div class="stat-num"><?= count($fichas) ?></div>
                        <div class="stat-sub">en este programa</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <div class="stat-label">Aprendices vinculados</div>
                        <div class="stat-num"><?= $totalAprendices["total"] ?? 0 ?></div>
                        <div class="stat-sub">en todas las fichas</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-orange"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-label">Duración</div>
                        <div class="stat-num"><?= htmlspecialchars($programa["duracion"]) ?></div>
                        <div class="stat-sub">meses de formación</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-purple"><i class="fas fa-signal"></i></div>
                    <div>
                        <div class="stat-label">Estado actual</div>
                        <div class="stat-num" style="font-size:16px; margin-top:4px;">
                            <?= htmlspecialchars($programa["estado"]) ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- INFO DEL PROGRAMA -->
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-circle-info"></i>
                    Información del programa
                </div>
                <div class="panel-body">
                    <div class="datos-grid">

                        <div class="dato-item">
                            <label><i class="fas fa-hashtag" style="color:var(--verde);margin-right:3px;"></i>Código</label>
                            <span><?= htmlspecialchars($programa["codigo"]) ?></span>
                        </div>

                        <div class="dato-item">
                            <label><i class="fas fa-layer-group" style="color:var(--verde);margin-right:3px;"></i>Nivel de formación</label>
                            <span><?= htmlspecialchars($programa["nivel"]) ?></span>
                        </div>

                        <div class="dato-item">
                            <label><i class="fas fa-clock" style="color:var(--verde);margin-right:3px;"></i>Duración</label>
                            <span><?= htmlspecialchars($programa["duracion"]) ?> meses</span>
                        </div>

                        <div class="dato-item full">
                            <label><i class="fas fa-align-left" style="color:var(--verde);margin-right:3px;"></i>Descripción</label>
                            <span>
                                <?= !empty($programa["descripcion"])
                                    ? htmlspecialchars($programa["descripcion"])
                                    : "Este programa no tiene descripción registrada." ?>
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TABLA FICHAS -->
            <div class="table-card">

                <div class="table-card-header">
                    <div class="table-card-header-left">
                        <i class="fas fa-folder"></i>
                        Fichas asociadas al programa
                        <span class="total-badge"><?= count($fichas) ?></span>
                    </div>
                    <a href="crear_ficha.php?programa_id=<?= $programa["id"] ?>" class="btn-add-ficha">
                        <i class="fas fa-plus"></i> Nueva ficha
                    </a>
                </div>

                <?php if(empty($fichas)): ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>Aún no hay fichas asociadas a este programa.</p>
                        <a href="crear_ficha.php?programa_id=<?= $programa["id"] ?>" class="btn-crear-ficha">
                            <i class="fas fa-plus"></i> Crear primera ficha
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>Ficha</th>
                                    <th>Jornada</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Aprendices</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($fichas as $f):
                                    $ec = strtolower($f["estado"] ?? "activo");
                                    $claseEstado = match($ec) {
                                        "activa"      => "estado-activo",
                                        "suspendida"  => "estado-suspendido",
                                        default       => "estado-finalizado"
                                    };
                                ?>
                                    <tr>
                                        <td>
                                            <span class="ficha-num">
                                                <?= htmlspecialchars($f["numero_ficha"]) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="jornada-badge">
                                                <i class="fas fa-clock"></i>
                                                <?= htmlspecialchars($f["jornada"] ?? "Sin definir") ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= !empty($f["fecha_inicio"])
                                                ? date("d/m/Y", strtotime($f["fecha_inicio"]))
                                                : "—" ?>
                                        </td>
                                        <td>
                                            <?= !empty($f["fecha_fin"])
                                                ? date("d/m/Y", strtotime($f["fecha_fin"]))
                                                : "—" ?>
                                        </td>
                                        <td>
                                            <span class="cnt-badge">
                                                <i class="fas fa-users"></i>
                                                <?= $f["total_aprendices"] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="estado-badge <?= $claseEstado ?>">
                                                <i class="fas fa-circle" style="font-size:7px;"></i>
                                                <?= htmlspecialchars($f["estado"] ?? "Activa") ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>

        </div><!-- /content -->

    </div><!-- /main-area -->

</div><!-- /dash-layout -->

<script>
function actualizarHora(){
    const now = new Date();
    document.getElementById("horaActual").textContent =
        now.toLocaleTimeString('es-CO');
    document.getElementById("fechaActual").textContent =
        now.toLocaleDateString('es-CO', { day:'numeric', month:'long', year:'numeric' });
}
actualizarHora();
setInterval(actualizarHora, 1000);
</script>

<?php include "layout/footer.php"; ?>