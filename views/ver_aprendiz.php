<?php

session_start();

if(!isset($_SESSION["id"])){
    header("Location: ../login.php");
    exit();
}

require_once "../models/AprendizModel.php";
require_once "../models/AsistenciaModel.php";

if(!isset($_GET["id"]) || empty($_GET["id"])){
    header("Location: aprendices.php");
    exit();
}

$id = $_GET["id"];
$aprendiz = AprendizModel::obtenerPorId($id);

if(!$aprendiz){
    header("Location: aprendices.php");
    exit();
}

$resumen  = AsistenciaModel::resumenPorAprendiz($id);
$historial = AsistenciaModel::historialPorAprendiz($id);

$total     = (int)($resumen["total_asistencias"] ?? 0);
$puntuales = (int)($resumen["puntuales"] ?? 0);
$tardanzas = (int)($resumen["tardanzas"] ?? 0);
$puntualidad = $total > 0 ? round(($puntuales / $total) * 100) : 0;

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
    font-size: 14px;
    color: #1a1a1a;
}

/* ══════════════════════════════════════
   LAYOUT
══════════════════════════════════════ */
.dash-layout { display: flex; min-height: 100vh; }

/* ── Sidebar ── */
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
.sidebar-nav a i     { width: 16px; text-align: center; }
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

/* ── Área principal ── */
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

/* ── Cabecera de página ── */
.page-topbar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px;
}

.page-topbar-left h2 { font-size: 20px; font-weight: 700; margin: 0 0 2px; }
.page-topbar-left p  { color: #888; font-size: 13px; margin: 0; }

.page-topbar-actions { display: flex; gap: 10px; }

.btn-volver {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: #fff; color: #555;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: border-color .2s, color .2s;
}
.btn-volver:hover { border-color: var(--verde); color: var(--verde); }

.btn-imprimir {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .2s;
}
.btn-imprimir:hover { background: var(--verde-oscuro); }

/* ── Card info aprendiz ── */
.info-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    margin-bottom: 20px;
    overflow: hidden;
}

.info-card-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 16px 24px;
    display: flex; justify-content: space-between; align-items: center;
}

.info-card-header .titulo {
    color: #fff; font-size: 15px; font-weight: 700;
}

.info-card-header .titulo small {
    display: block; font-size: 12px; font-weight: 400;
    color: rgba(255,255,255,.75); margin-top: 2px;
}

.info-card-header .generado {
    text-align: right; color: rgba(255,255,255,.85); font-size: 12px;
}

.info-card-header .generado strong {
    display: block; font-size: 13px; color: #fff;
}

.info-card-body { padding: 22px 24px; }

.info-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 16px 24px;
}

.info-field label {
    display: block; font-size: 11px; font-weight: 700;
    color: #888; text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 4px;
}

.info-field span {
    font-size: 14px; color: #222; font-weight: 500;
}

.badge-estado {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.badge-activo   { background: var(--verde-claro); color: var(--verde); border: 1px solid #c8e6c9; }
.badge-inactivo { background: #f0f0f0; color: #777; border: 1px solid #ddd; }

/* ── Stats ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: var(--shadow);
    border: 1px solid #eef1ee;
}

.stat-card .ic {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}

.ic-green  { background: var(--verde-claro); color: var(--verde); }
.ic-yellow { background: #fff6e0;            color: #e6a400; }
.ic-red    { background: #fdeaea;            color: #e53935; }
.ic-blue   { background: #e8f0fe;            color: #1a73e8; }

.stat-label { font-size: 12px; color: #888; margin-bottom: 4px; }
.stat-num   { font-size: 30px; font-weight: 700; line-height: 1; color: #1a1a1a; }

/* Barra puntualidad */
.puntualidad-wrap { margin-top: 6px; }
.puntualidad-bar {
    height: 5px; background: #eee; border-radius: 4px; overflow: hidden; margin-top: 5px;
}
.puntualidad-fill { height: 100%; background: var(--verde); border-radius: 4px; }

/* ── Tabla historial ── */
.table-card {
    background: #fff;
    border-radius: var(--card-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.table-card-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 14px 22px;
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}

.tabla-historial { width: 100%; border-collapse: collapse; font-size: 13.5px; }

.tabla-historial thead tr  { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla-historial thead th  {
    padding: 12px 16px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
}

.tabla-historial tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla-historial tbody tr:last-child { border-bottom: none; }
.tabla-historial tbody tr:hover   { background: #f8fffe; }
.tabla-historial tbody td         { padding: 12px 16px; color: #444; vertical-align: middle; }

.num-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px;
    background: #f0faf3; border: 1.5px solid #d4edda;
    border-radius: 8px; font-size: 12px; font-weight: 700; color: #1a6b2e;
}

.badge-puntual {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9;
}

.badge-tarde {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #fff6e0; color: #b45309; border: 1px solid #fde68a;
}

.empty-state { text-align: center; padding: 50px 20px; color: #bbb; }
.empty-state i { font-size: 44px; margin-bottom: 12px; display: block; color: #d4edda; }
.empty-state p { font-size: 14px; }

/* ══════════════════════════════════════
   PRINT
══════════════════════════════════════ */
@media print {
    .sidebar, .main-area > .topbar, .page-topbar-actions, .notif-btn, .user-btn,
    .dropdown, .sidebar-bottom { display: none !important; }

    .dash-layout { display: block; }
    .main-area   { margin-left: 0 !important; }
    .content     { padding: 0 !important; }
    body         { background: #fff !important; }

    .info-card, .table-card, .stat-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .info-card-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .table-card-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
                <a href="aprendices.php" class="active"><i class="fas fa-user-graduate"></i> Aprendices</a>
                <a href="instructores.php"><i class="fas fa-chalkboard-teacher"></i> Instructores</a>
                <a href="fichas.php"><i class="fas fa-folder"></i> Fichas</a>
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
                <h4>Reporte de Aprendiz</h4>
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

            <!-- Cabecera de página -->
            <div class="page-topbar no-print">
                <div class="page-topbar-left">
                    <h2><i class="fas fa-file-lines" style="color:var(--verde);"></i> Reporte de asistencia</h2>
                    <p>Historial individual del aprendiz.</p>
                </div>
                <div class="page-topbar-actions">
                    <a href="aprendices.php" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button class="btn-imprimir" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir / Guardar PDF
                    </button>
                </div>
            </div>

            <!-- INFO APRENDIZ -->
            <div class="info-card">

                <div class="info-card-header">
                    <div class="titulo">
                        Sistema Inteligente de Control de Asistencia
                        <small>Reporte individual de asistencia</small>
                    </div>
                    <div class="generado">
                        Generado el
                        <strong><?= date("d/m/Y H:i") ?></strong>
                    </div>
                </div>

                <div class="info-card-body">
                    <div class="info-grid">

                        <div class="info-field">
                            <label>Aprendiz</label>
                            <span><?= htmlspecialchars($aprendiz["nombres"] . " " . $aprendiz["apellidos"]) ?></span>
                        </div>

                        <div class="info-field">
                            <label>Documento</label>
                            <span><?= htmlspecialchars($aprendiz["documento"]) ?></span>
                        </div>

                        <div class="info-field">
                            <label>Ficha</label>
                            <span><?= htmlspecialchars($aprendiz["numero_ficha"] ?? "Sin ficha") ?></span>
                        </div>

                        <div class="info-field">
                            <label>Correo</label>
                            <span><?= htmlspecialchars($aprendiz["correo"]) ?></span>
                        </div>

                        <div class="info-field">
                            <label>Teléfono</label>
                            <span><?= htmlspecialchars($aprendiz["telefono"]) ?></span>
                        </div>

                        <div class="info-field">
                            <label>Estado</label>
                            <span>
                                <?php if($aprendiz["estado"] === "Activo"): ?>
                                    <span class="badge-estado badge-activo">
                                        <i class="fas fa-circle" style="font-size:7px;"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge-estado badge-inactivo">
                                        <i class="fas fa-circle" style="font-size:7px;"></i>
                                        <?= htmlspecialchars($aprendiz["estado"]) ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- STATS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="ic ic-blue"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-label">Asistencias</div>
                        <div class="stat-num"><?= $total ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-label">Puntuales</div>
                        <div class="stat-num"><?= $puntuales ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-yellow"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-label">Tardanzas</div>
                        <div class="stat-num"><?= $tardanzas ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-chart-pie"></i></div>
                    <div>
                        <div class="stat-label">Puntualidad</div>
                        <div class="stat-num"><?= $puntualidad ?>%</div>
                        <div class="puntualidad-wrap">
                            <div class="puntualidad-bar">
                                <div class="puntualidad-fill" style="width:<?= $puntualidad ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABLA HISTORIAL -->
            <div class="table-card">

                <div class="table-card-header">
                    <i class="fas fa-history"></i>
                    Historial de asistencias
                </div>

                <div class="table-responsive">
                    <table class="tabla-historial">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if(empty($historial)): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-xmark"></i>
                                            <p>No hay asistencias registradas para este aprendiz.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($historial as $i => $a): ?>
                                    <tr>
                                        <td><span class="num-badge"><?= $i + 1 ?></span></td>
                                        <td><?= date("d/m/Y", strtotime($a["fecha"])) ?></td>
                                        <td><?= htmlspecialchars($a["hora"]) ?></td>
                                        <td>
                                            <?php if($a["estado"] === "Puntual"): ?>
                                                <span class="badge-puntual">
                                                    <i class="fas fa-check-circle"></i> Puntual
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-tarde">
                                                    <i class="fas fa-clock"></i> Tarde
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