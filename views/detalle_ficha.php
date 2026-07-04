<?php

session_start();

require_once "../models/FichaModel.php";

if(!isset($_GET["id"])){
    header("Location: fichas.php");
    exit();
}

$id    = $_GET["id"];
$ficha = FichaModel::obtenerPorId($id);

if(!$ficha){
    header("Location: fichas.php?mensaje=error");
    exit();
}

$aprendices      = FichaModel::aprendicesPorFicha($id);
$asistencias     = FichaModel::asistenciasRecientesPorFicha($id);
$totalAprendices = count($aprendices);
$cupoMaximo      = (int)($ficha["cupo_maximo"] ?? 0);
$porcentaje      = $cupoMaximo > 0 ? min(100, round(($totalAprendices / $cupoMaximo) * 100)) : 0;
$disponibles     = max(0, $cupoMaximo - $totalAprendices);
$barraColor      = $porcentaje >= 100 ? '#e53935' : ($porcentaje >= 80 ? '#e6a400' : '#39A900');

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
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
}
.page-topbar-left h2 { font-size: 20px; font-weight: 700; margin: 0 0 2px; }
.page-topbar-left p  { color: #888; font-size: 13px; margin: 0; }

.page-topbar-actions { display: flex; gap: 10px; }

.btn-editar {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: var(--verde-claro); color: var(--verde-oscuro);
    border: 1.5px solid #c8e6c9; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-editar:hover { background: #c8e6c9; color: var(--verde-oscuro); }

.btn-volver {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: #fff; color: #555;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: border-color .2s, color .2s;
}
.btn-volver:hover { border-color: var(--verde); color: var(--verde); }

/* ══════════════════════════════════════
   FILA SUPERIOR: info académica + ocupación
══════════════════════════════════════ */
.top-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
    margin-bottom: 20px;
    align-items: start;
}

/* ── Card genérica ── */
.panel {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.panel-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 14px 22px;
    display: flex; align-items: center; gap: 12px;
}
.panel-header .ph-icon {
    width: 38px; height: 38px;
    background: rgba(255,255,255,.2); border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #fff; flex-shrink: 0;
}
.panel-header h3    { color: #fff; font-size: 14px; font-weight: 700; margin: 0; }
.panel-header small { color: rgba(255,255,255,.75); font-size: 12px; }
.panel-header .ms-auto { margin-left: auto; }

.panel-body { padding: 22px; }

/* ── Badges de estado ── */
.estado-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.estado-activo     { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }
.estado-suspendido { background: #fff6e0; color: #b45309; border: 1px solid #fde68a; }
.estado-finalizado { background: #f0f0f0; color: #666;    border: 1px solid #ddd; }

/* ── Info grid ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 24px;
}

.info-field label {
    display: block; font-size: 11px; font-weight: 700;
    color: #aaa; text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 4px;
}
.info-field span {
    font-size: 14px; color: #222; font-weight: 500;
}

.info-field.span2 { grid-column: 1 / -1; }

/* ── Card ocupación ── */
.ocupacion-body { padding: 22px; }

.ocup-num {
    font-size: 48px; font-weight: 800; color: #1a1a1a; line-height: 1;
    margin-bottom: 2px;
}
.ocup-num span { font-size: 22px; font-weight: 500; color: #aaa; }

.ocup-label { font-size: 13px; color: #888; margin-bottom: 14px; }

.ocup-barra {
    height: 8px; background: #eee; border-radius: 4px;
    overflow: hidden; margin-bottom: 16px;
}
.ocup-barra-fill { height: 100%; border-radius: 4px; transition: width .4s; }

.ocup-resumen {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.ocup-resumen-item {
    background: #fafafa; border: 1px solid #f0f0f0;
    border-radius: 10px; padding: 12px;
    text-align: center;
}
.ocup-resumen-item .r-label { font-size: 11px; color: #aaa; margin-bottom: 4px; }
.ocup-resumen-item .r-num   { font-size: 22px; font-weight: 700; color: #222; }

/* ══════════════════════════════════════
   FILA INFERIOR: aprendices + asistencias
══════════════════════════════════════ */
.bot-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
    align-items: start;
}

/* ── Table card ── */
.table-card { background: #fff; border-radius: var(--card-radius); box-shadow: var(--shadow); overflow: hidden; }

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

.btn-ver-todos {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px;
    background: rgba(255,255,255,.15); color: #fff;
    border: 1px solid rgba(255,255,255,.3); border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-ver-todos:hover { background: rgba(255,255,255,.25); color: #fff; }

/* Tabla aprendices */
.tabla { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.tabla thead tr { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla thead th {
    padding: 11px 16px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}
.tabla tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla tbody tr:last-child { border-bottom: none; }
.tabla tbody tr:hover   { background: #f8fffe; }
.tabla tbody td         { padding: 12px 16px; color: #444; vertical-align: middle; }

.nombre-cell { font-weight: 600; color: #222; }
.correo-cell { font-size: 11px; color: #999; margin-top: 2px; }

.badge-huella-ok {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9;
}
.badge-huella-no {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #fdecea; color: #c62828; border: 1px solid #ffcdd2;
}

/* ── Asistencias recientes ── */
.asist-header {
    background: linear-gradient(90deg, #1565c0, #1a73e8);
    padding: 14px 22px;
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}

.asist-list { padding: 8px 0; }

.asist-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 18px;
    border-bottom: 1px solid #f0f0f0;
    transition: background .15s;
}
.asist-item:last-child { border-bottom: none; }
.asist-item:hover { background: #f8fffe; }

.asist-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    color: #1565c0; font-size: 14px; flex-shrink: 0;
}

.asist-info { flex: 1; min-width: 0; }
.asist-info .asist-nombre { font-weight: 600; font-size: 13px; color: #222; }
.asist-info .asist-fecha  { font-size: 11px; color: #999; margin-top: 2px; }

.asist-hora {
    font-size: 12px; font-weight: 700;
    color: #1565c0; font-family: monospace;
    white-space: nowrap;
}

.badge-puntual {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 8px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9;
}
.badge-tarde {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 8px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: #fff6e0; color: #b45309; border: 1px solid #fde68a;
}

/* Empty state */
.empty-state { text-align: center; padding: 40px 20px; color: #bbb; }
.empty-state i { font-size: 40px; margin-bottom: 10px; display: block; color: #d4edda; }
.empty-state p { font-size: 13px; }

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
                <h4>Detalle de Ficha</h4>
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
                    <h2>
                        <i class="fas fa-folder-open" style="color:var(--verde);"></i>
                        Detalle de ficha
                    </h2>
                    <p>
                        Ficha <strong><?= htmlspecialchars($ficha["numero_ficha"]) ?></strong> —
                        <?= htmlspecialchars($ficha["programa"] ?? "Sin programa") ?>
                    </p>
                </div>
                <div class="page-topbar-actions">
                    <a href="editar_ficha.php?id=<?= $ficha["id"] ?>" class="btn-editar">
                        <i class="fas fa-pen"></i> Editar ficha
                    </a>
                    <a href="fichas.php" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <!-- ══ FILA SUPERIOR ══ -->
            <div class="top-grid">

                <!-- Info académica -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="ph-icon"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <h3>Información académica</h3>
                            <small>Datos principales de la ficha de formación</small>
                        </div>
                        <div class="ms-auto">
                            <?php if($ficha["estado"] === "Activa"): ?>
                                <span class="estado-badge estado-activo">
                                    <i class="fas fa-circle" style="font-size:7px;"></i> Activa
                                </span>
                            <?php elseif($ficha["estado"] === "Suspendida"): ?>
                                <span class="estado-badge estado-suspendido">
                                    <i class="fas fa-pause-circle"></i> Suspendida
                                </span>
                            <?php else: ?>
                                <span class="estado-badge estado-finalizado">
                                    <i class="fas fa-flag-checkered"></i> Finalizada
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="info-grid">

                            <div class="info-field">
                                <label><i class="fas fa-hashtag" style="color:var(--verde);margin-right:4px;"></i>Número de ficha</label>
                                <span><?= htmlspecialchars($ficha["numero_ficha"]) ?></span>
                            </div>

                            <div class="info-field">
                                <label><i class="fas fa-book" style="color:var(--verde);margin-right:4px;"></i>Programa de formación</label>
                                <span><?= htmlspecialchars($ficha["programa"] ?? "No registrado") ?></span>
                            </div>

                            <div class="info-field">
                                <label><i class="fas fa-clock" style="color:var(--verde);margin-right:4px;"></i>Jornada</label>
                                <span><?= htmlspecialchars($ficha["jornada"] ?? "No registrada") ?></span>
                            </div>

                            <div class="info-field">
                                <label><i class="fas fa-layer-group" style="color:var(--verde);margin-right:4px;"></i>Nivel de formación</label>
                                <span><?= htmlspecialchars($ficha["nivel_formacion"] ?? "No registrado") ?></span>
                            </div>

                            <div class="info-field">
                                <label><i class="fas fa-calendar-day" style="color:var(--verde);margin-right:4px;"></i>Fecha de inicio</label>
                                <span>
                                    <?= !empty($ficha["fecha_inicio"])
                                        ? date("d/m/Y", strtotime($ficha["fecha_inicio"]))
                                        : "No registrada" ?>
                                </span>
                            </div>

                            <div class="info-field">
                                <label><i class="fas fa-calendar-check" style="color:var(--verde);margin-right:4px;"></i>Fecha de finalización</label>
                                <span>
                                    <?= !empty($ficha["fecha_fin"])
                                        ? date("d/m/Y", strtotime($ficha["fecha_fin"]))
                                        : "No registrada" ?>
                                </span>
                            </div>

                            <div class="info-field span2">
                                <label><i class="fas fa-chalkboard-teacher" style="color:var(--verde);margin-right:4px;"></i>Instructor responsable</label>
                                <span><?= htmlspecialchars($ficha["instructor"] ?? "Sin asignar") ?></span>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Ocupación -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="ph-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <h3>Ocupación</h3>
                            <small>Aprendices asignados</small>
                        </div>
                    </div>

                    <div class="ocupacion-body">

                        <div class="ocup-num">
                            <?= $totalAprendices ?>
                            <span>/ <?= $cupoMaximo ?></span>
                        </div>
                        <div class="ocup-label"><?= $porcentaje ?>% de cupos ocupados</div>

                        <div class="ocup-barra">
                            <div class="ocup-barra-fill"
                                 style="width:<?= $porcentaje ?>%; background:<?= $barraColor ?>;"></div>
                        </div>

                        <div class="ocup-resumen">
                            <div class="ocup-resumen-item">
                                <div class="r-label">Disponibles</div>
                                <div class="r-num" style="color:var(--verde);"><?= $disponibles ?></div>
                            </div>
                            <div class="ocup-resumen-item">
                                <div class="r-label">Asignados</div>
                                <div class="r-num"><?= $totalAprendices ?></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ══ FILA INFERIOR ══ -->
            <div class="bot-grid">

                <!-- Aprendices -->
                <div class="table-card">
                    <div class="table-card-header">
                        <div class="table-card-header-left">
                            <i class="fas fa-user-graduate"></i>
                            Aprendices asignados
                            <span class="total-badge"><?= $totalAprendices ?></span>
                        </div>
                        <a href="aprendices.php?ficha_id=<?= $ficha["id"] ?>" class="btn-ver-todos">
                            <i class="fas fa-arrow-right"></i> Ver todos
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>Aprendiz</th>
                                    <th>Documento</th>
                                    <th>Huella</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($aprendices)): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class="fas fa-user-graduate"></i>
                                                <p>No hay aprendices asignados a esta ficha.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($aprendices as $a): ?>
                                        <tr>
                                            <td>
                                                <div class="nombre-cell">
                                                    <?= htmlspecialchars($a["nombres"]) ?>
                                                    <?= htmlspecialchars($a["apellidos"]) ?>
                                                </div>
                                                <div class="correo-cell">
                                                    <?= htmlspecialchars($a["correo"] ?? "") ?>
                                                </div>
                                            </td>
                                            <td style="font-family:monospace; font-size:13px;">
                                                <?= htmlspecialchars($a["documento"]) ?>
                                            </td>
                                            <td>
                                                <?php if($a["huella_id"] !== "SIN_HUELLA" && !empty($a["huella_id"])): ?>
                                                    <span class="badge-huella-ok">
                                                        <i class="fas fa-fingerprint"></i> Registrada
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge-huella-no">
                                                        <i class="fas fa-fingerprint"></i> Pendiente
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($a["estado"] === "Activo"): ?>
                                                    <span class="estado-badge estado-activo">
                                                        <i class="fas fa-circle" style="font-size:7px;"></i> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="estado-badge estado-suspendido">
                                                        <i class="fas fa-pause-circle"></i> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Asistencias recientes -->
                <div class="table-card">
                    <div class="asist-header">
                        <i class="fas fa-calendar-check"></i>
                        Asistencias recientes
                        <span class="total-badge" style="margin-left:4px;"><?= count($asistencias) ?></span>
                    </div>

                    <?php if(empty($asistencias)): ?>
                        <div class="empty-state" style="padding:40px 20px;">
                            <i class="fas fa-clipboard-check" style="color:#c5d8fb;"></i>
                            <p>No hay asistencias registradas.</p>
                        </div>
                    <?php else: ?>
                        <div class="asist-list">
                            <?php foreach($asistencias as $asi): ?>
                                <div class="asist-item">
                                    <div class="asist-avatar">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="asist-info">
                                        <div class="asist-nombre">
                                            <?= htmlspecialchars($asi["nombres"]) ?>
                                            <?= htmlspecialchars($asi["apellidos"]) ?>
                                        </div>
                                        <div class="asist-fecha">
                                            <?php if($asi["estado"] === "Puntual"): ?>
                                                <span class="badge-puntual"><i class="fas fa-check-circle"></i> Puntual</span>
                                            <?php else: ?>
                                                <span class="badge-tarde"><i class="fas fa-clock"></i> Tarde</span>
                                            <?php endif; ?>
                                            &nbsp;· <?= date("d/m/Y", strtotime($asi["fecha"])) ?>
                                        </div>
                                    </div>
                                    <div class="asist-hora">
                                        <?= htmlspecialchars($asi["hora"]) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

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

<?php require_once "layout/footer.php"; ?>