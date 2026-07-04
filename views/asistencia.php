<?php

session_start();

if(!isset($_SESSION["nombre"])){
    header("Location: ../index.php");
    exit();
}

require_once "../models/AprendizModel.php";
require_once "../models/AsistenciaModel.php";

$aprendices  = AprendizModel::listar();
$asistencias = AsistenciaModel::listarHoy();

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
.page-header-card {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 20px 24px; margin-bottom: 20px;
    box-shadow: var(--shadow);
    border-left: 5px solid var(--verde);
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

/* ══════════════════════════════════════
   DOS COLUMNAS
══════════════════════════════════════ */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
}

/* ══════════════════════════════════════
   PANEL GENÉRICO
══════════════════════════════════════ */
.table-card {
    background: #fff;
    border-radius: var(--card-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.table-card-header {
    padding: 14px 22px;
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-weight: 600; font-size: 14px;
}

.header-huella { background: linear-gradient(90deg, #1a6b2e, #39A900); }
.header-hoy    { background: linear-gradient(90deg, #1565c0, #1a73e8); }

.total-badge {
    background: rgba(255,255,255,.2);
    border-radius: 20px; padding: 3px 10px;
    font-size: 12px; margin-left: 4px;
}

/* Buscador */
.table-search {
    padding: 12px 18px;
    border-bottom: 1px solid #f0f0f0;
}
.search-wrap { position: relative; }
.search-wrap i {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: #bbb; font-size: 13px;
}
.search-wrap input {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border: 1.5px solid #e8e8e8; border-radius: 10px;
    font-size: 13px; color: #333;
    outline: none; background: #fafafa;
    transition: border-color .2s;
}
.search-wrap input:focus        { border-color: var(--verde); background: #fff; }
.search-wrap input::placeholder { color: #ccc; }

/* Tablas */
.tabla { width: 100%; border-collapse: collapse; font-size: 13.5px; }

.tabla thead tr { background: #f0faf3; border-bottom: 2px solid #d4edda; }
.tabla thead th {
    padding: 11px 16px; font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}

.tabla-hoy thead tr { background: #e8f0fe; border-bottom: 2px solid #c5d8fb; }
.tabla-hoy thead th { color: #1565c0; }

.tabla tbody tr         { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
.tabla tbody tr:last-child { border-bottom: none; }
.tabla tbody tr:hover   { background: #f8fffe; }
.tabla tbody td         { padding: 12px 16px; color: #444; vertical-align: middle; }

/* Nombre */
.nombre-cell { font-weight: 600; color: #222; }
.doc-cell    { font-family: monospace; color: #555; font-size: 13px; }

/* Hora */
.hora-cell {
    font-family: monospace; font-size: 13px;
    font-weight: 600; color: #1a6b2e;
}

/* Botón registrar huella */
.btn-huella {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    background: var(--verde-claro); color: var(--verde-oscuro);
    border: 1.5px solid #c8e6c9; border-radius: 8px;
    font-size: 12px; font-weight: 700; text-decoration: none;
    transition: background .15s, border-color .15s, transform .1s;
}
.btn-huella:hover {
    background: var(--verde); color: #fff;
    border-color: var(--verde);
    transform: translateY(-1px);
}

/* Badges estado */
.badge-puntual {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9;
}
.badge-tarde {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #fff6e0; color: #b45309; border: 1px solid #fde68a;
}

/* Empty state */
.empty-state { text-align: center; padding: 36px 20px; color: #bbb; }
.empty-state i { font-size: 36px; margin-bottom: 10px; display: block; color: #d4edda; }
.empty-state p { font-size: 13px; }

/* Indicador pulso (biométrico activo) */
.bio-status {
    display: flex; align-items: center; gap: 8px;
    background: var(--verde-claro);
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    padding: 10px 16px;
    margin: 12px 18px;
    font-size: 13px; color: #1a5c00; font-weight: 500;
}
.pulse-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--verde); flex-shrink: 0;
    box-shadow: 0 0 0 0 rgba(57,169,0,.4);
    animation: pulse 1.8s infinite;
}
@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(57,169,0,.4); }
    70%  { box-shadow: 0 0 0 8px rgba(57,169,0,0); }
    100% { box-shadow: 0 0 0 0 rgba(57,169,0,0); }
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
                <a href="programas.php"><i class="fas fa-book"></i> Programas</a>
                <a href="asistencia.php" class="active"><i class="fas fa-fingerprint"></i> Asistencia</a>
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
                <h4>Asistencia</h4>
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
                    <i class="fas fa-fingerprint" style="color:white;"></i>
                </div>
                <div>
                    <h2>Registro de Asistencia</h2>
                    <p>Simulación de huella y control de asistencias del día de hoy.</p>
                </div>
            </div>

            <!-- DOS COLUMNAS -->
            <div class="two-col">

                <!-- ══ TABLA SIMULACIÓN HUELLA ══ -->
                <div class="table-card">

                    <div class="table-card-header header-huella">
                        <i class="fas fa-fingerprint"></i>
                        Simulación de Huella
                        <span class="total-badge"><?= count($aprendices) ?> aprendices</span>
                    </div>

                    <div class="bio-status">
                        <span class="pulse-dot"></span>
                        Dispositivo biométrico activo y en espera
                    </div>

                    <div class="table-search">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="buscarAprendiz"
                                placeholder="Buscar aprendiz o documento...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="tabla" id="tablaAprendices">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($aprendices)): ?>
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <i class="fas fa-user-graduate"></i>
                                                <p>No hay aprendices registrados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($aprendices as $a): ?>
                                        <tr>
                                            <td class="doc-cell">
                                                <?= htmlspecialchars($a["documento"]) ?>
                                            </td>
                                            <td class="nombre-cell">
                                                <?= htmlspecialchars($a["nombres"]) ?>
                                                <?= htmlspecialchars($a["apellidos"]) ?>
                                            </td>
                                            <td>
                                                <a href="../controllers/asistencia.php?aprendiz=<?= $a["id"] ?>"
                                                   class="btn-huella"
                                                   onclick="return confirm('¿Registrar asistencia de <?= htmlspecialchars($a["nombres"]) ?>?')">
                                                    <i class="fas fa-fingerprint"></i>
                                                    Registrar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- ══ TABLA ASISTENCIAS HOY ══ -->
                <div class="table-card">

                    <div class="table-card-header header-hoy">
                        <i class="fas fa-calendar-check"></i>
                        Asistencias de Hoy
                        <span class="total-badge" id="totalHoy"><?= count($asistencias) ?> registros</span>
                    </div>

                    <div class="table-search">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="buscarHoy"
                                placeholder="Buscar en asistencias de hoy...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="tabla tabla-hoy" id="tablaHoy">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($asistencias)): ?>
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <i class="fas fa-calendar-xmark"></i>
                                                <p>No hay asistencias registradas hoy.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($asistencias as $r): ?>
                                        <tr>
                                            <td class="nombre-cell">
                                                <?= htmlspecialchars($r["nombres"]) ?>
                                                <?= htmlspecialchars($r["apellidos"]) ?>
                                            </td>
                                            <td class="hora-cell">
                                                <i class="fas fa-clock" style="font-size:11px; opacity:.6;"></i>
                                                <?= htmlspecialchars($r["hora"]) ?>
                                            </td>
                                            <td>
                                                <?php if($r["estado"] === "Puntual"): ?>
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

/* ── Buscador aprendices ── */
document.getElementById("buscarAprendiz").addEventListener("keyup", function(){
    const filtro = this.value.toLowerCase();
    document.querySelectorAll("#tablaAprendices tbody tr").forEach(fila => {
        fila.style.display = fila.innerText.toLowerCase().includes(filtro) ? "" : "none";
    });
});

/* ── Buscador asistencias hoy ── */
document.getElementById("buscarHoy").addEventListener("keyup", function(){
    const filtro  = this.value.toLowerCase();
    const filas   = document.querySelectorAll("#tablaHoy tbody tr");
    const badge   = document.getElementById("totalHoy");
    let visibles   = 0;

    filas.forEach(fila => {
        const match = fila.innerText.toLowerCase().includes(filtro);
        fila.style.display = match ? "" : "none";
        if(match) visibles++;
    });

    badge.textContent = visibles + " registro" + (visibles !== 1 ? "s" : "");
});

</script>

<?php require_once "layout/footer.php"; ?>