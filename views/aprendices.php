<?php

session_start();

require_once "../models/AprendizModel.php";

$aprendices = AprendizModel::listar();

$total = AprendizModel::totalAprendices();

$activos = AprendizModel::totalActivos();

$inactivos = AprendizModel::totalInactivos();

$conHuella = AprendizModel::conHuella();

$sinHuella = AprendizModel::sinHuella();

$fichas = AprendizModel::totalFichas();

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
.dash-layout {
    display: flex;
    min-height: 100vh;
}

/* ── Sidebar ── */
.sidebar {
    width: 220px;
    flex-shrink: 0;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    overflow-y: auto;
    background: linear-gradient(180deg, var(--negro) 0%, var(--negro-suave) 100%);
    padding: 22px 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 1000;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}

.sidebar-brand .icon-box {
    width: 40px; height: 40px;
    background: var(--verde);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff; flex-shrink: 0;
}

.sidebar-brand h5 { color: #fff; font-size: 14px; font-weight: 700; margin: 0; }
.sidebar-brand small { color: #b9c9b0; font-size: 10px; display: block; margin-top: 1px; }

.sidebar-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    margin-bottom: 4px;
    color: #cfd9c8;
    text-decoration: none;
    font-size: 13px;
    transition: .15s;
}

.sidebar-nav a i { width: 16px; text-align: center; }
.sidebar-nav a:hover  { background: rgba(57,169,0,.18); color: #fff; }
.sidebar-nav a.active { background: var(--verde); color: #fff; font-weight: 600; }

.sidebar-bottom { margin-top: 20px; }

.sidebar-biometric {
    background: rgba(57,169,0,.15);
    border: 1px solid rgba(57,169,0,.4);
    border-radius: 12px;
    padding: 10px 12px;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px;
}

.sidebar-biometric .icon-box {
    width: 32px; height: 32px;
    background: var(--verde); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 14px; flex-shrink: 0;
}

.sidebar-biometric strong { color: #fff; font-size: 12px; display: block; }
.sidebar-biometric span   { color: #7ddc5a; font-size: 11px; }
.sidebar-biometric span i { font-size: 8px; margin-right: 3px; }

.sidebar-user {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,.06);
}

.sidebar-user .avatar {
    width: 32px; height: 32px;
    background: var(--verde); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 13px; flex-shrink: 0;
}

.sidebar-user .info p    { color: #fff; font-size: 12px; font-weight: 600; margin: 0; }
.sidebar-user .info span { color: #9aa39a; font-size: 11px; }

/* ── Área principal ── */
.main-area {
    margin-left: 220px;
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ══════════════════════════════════════
   TOPBAR
══════════════════════════════════════ */
.topbar {
    background: #fff;
    padding: 14px 24px;
    border-bottom: 1px solid #eaeaea;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky; top: 0; z-index: 999;
}

.topbar-left h4    { font-size: 18px; font-weight: 700; margin: 0; }
.topbar-left small { color: #888; font-size: 12px; }

.topbar-right { display: flex; align-items: center; gap: 14px; }

.time-box .hora  { font-size: 20px; font-weight: 700; color: #1a1a1a; line-height: 1; text-align: right; }
.time-box .fecha { font-size: 11px; color: #888; margin-top: 2px; text-align: right; }

.notif-btn {
    position: relative;
    width: 36px; height: 36px;
    background: #f4f6f4;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #555; font-size: 15px; cursor: pointer;
}

.notif-btn .badge-dot {
    position: absolute; top: -4px; right: -4px;
    background: var(--verde); color: #fff;
    font-size: 9px; font-weight: 700;
    width: 17px; height: 17px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}

.user-btn {
    display: flex; align-items: center; gap: 8px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    padding: 8px 14px;
    font-size: 13px; font-weight: 600; cursor: pointer;
}

/* ══════════════════════════════════════
   CONTENIDO
══════════════════════════════════════ */
.content { padding: 24px; flex: 1; }

/* ── Cabecera de página ── */
.page-header-card {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 20px 24px;
    margin-bottom: 20px;
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

/* ── Barra de acciones ── */
.actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 12px; flex-wrap: wrap;
}

.btn-nuevo {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s, transform .1s;
}

.btn-nuevo:hover { background: var(--verde-oscuro); color: #fff; transform: translateY(-1px); }

.export-btns { display: flex; gap: 10px; }

.btn-export {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 16px;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: opacity .2s, transform .1s;
}

.btn-export:hover { opacity: .88; transform: translateY(-1px); }
.btn-excel { background: #1a73e8; color: #fff; }
.btn-pdf   { background: #e53935; color: #fff; }

/* ── Buscador ── */
.search-card {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 14px 18px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
}

.search-wrap { position: relative; }

.search-wrap i {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    color: #aaa; font-size: 14px;
}

.search-wrap input {
    width: 100%;
    padding: 11px 14px 11px 40px;
    border: 1.5px solid #e8e8e8;
    border-radius: 10px;
    font-size: 14px; color: #333;
    outline: none; background: #fafafa;
    transition: border-color .2s, background .2s;
}

.search-wrap input:focus       { border-color: var(--verde); background: #fff; }
.search-wrap input::placeholder { color: #bbb; }

/* ── Tabla ── */
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

.mensaje-aprendiz{
    margin: 20px 0;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,.08);
}

.total-badge {
    background: rgba(255,255,255,.2);
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 12px; margin-left: 4px;
}

.tabla-aprendices {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.tabla-aprendices thead tr {
    background: #f0faf3;
    border-bottom: 2px solid #d4edda;
}

.tabla-aprendices thead th {
    padding: 12px 16px;
    font-weight: 700; color: #1a6b2e;
    font-size: 11px; text-transform: uppercase;
    letter-spacing: .5px; white-space: nowrap;
}

.tabla-aprendices tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background .15s;
}

.tabla-aprendices tbody tr:last-child { border-bottom: none; }
.tabla-aprendices tbody tr:hover      { background: #f8fffe; }

.tabla-aprendices tbody td {
    padding: 12px 16px;
    color: #444; vertical-align: middle;
}

.id-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px;
    background: #f0faf3;
    border: 1.5px solid #d4edda;
    border-radius: 8px;
    font-size: 12px; font-weight: 700; color: #1a6b2e;
}

.nombre-cell { font-weight: 600; color: #222; }
.correo-cell { color: #666; font-size: 13px; }

.badge-huella {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}

.badge-registrada { background: #e8f5e9; color: #2d8f45; border: 1px solid #c8e6c9; }
.badge-pendiente  { background: #fdecea; color: #c62828; border: 1px solid #ffcdd2; }

.acciones-cell { display: flex; gap: 6px; align-items: center; }

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
.btn-eliminar { background: #fdecea; color: #c62828; }
.btn-reporte  { background: #f3e5f5; color: #6a1b9a; }

.empty-state { text-align: center; padding: 50px 20px; color: #bbb; }
.empty-state i { font-size: 48px; margin-bottom: 12px; display: block; color: #d4edda; }
.empty-state p { font-size: 15px; margin: 0; }

.page-title-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    margin-bottom:22px;
}

.page-title-row h3{
    margin:0 0 5px;
    font-size:24px;
    font-weight:700;
    color:#172217;
}

.page-title-row p{
    margin:0;
    color:#7d877b;
    font-size:14px;
}

.form-card{
    background:#fff;
    border-radius:16px;
    padding:28px;
    box-shadow:0 5px 18px rgba(0,0,0,.07);
    border:1px solid #edf1ed;
    max-width:1000px;
}

.form-card-header{
    display:flex;
    align-items:center;
    gap:14px;
    padding-bottom:20px;
    margin-bottom:24px;
    border-bottom:1px solid #edf1ed;
}

.form-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#eaf7e0;
    color:#39A900;
    font-size:20px;
}

.form-card-header h5{
    margin:0 0 4px;
    font-size:17px;
    font-weight:700;
}

.form-card-header span{
    font-size:13px;
    color:#7d877b;
}

.form-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:28px;
    padding-top:20px;
    border-top:1px solid #edf1ed;
}

.form-card .form-label{
    font-weight:600;
    font-size:13px;
    color:#3d493c;
}

.form-card .form-control,
.form-card .form-select{
    min-height:44px;
    border-radius:9px;
    border:1px solid #dfe6de;
}

.form-card .form-control:focus,
.form-card .form-select:focus{
    border-color:#39A900;
    box-shadow:0 0 0 .2rem rgba(57,169,0,.12);
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

    <?php if(isset($_GET["mensaje"])): ?>

    <?php if($_GET["mensaje"] === "tiene_asistencias"): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i>
            <strong>No se puede eliminar este aprendiz porque ya tiene asistencias registradas.</strong>
            Puedes desactivarlo en lugar de eliminarlo.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($_GET["mensaje"] === "suspendido"): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-user-slash me-2"></i>
            El aprendiz fue suspendido correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($_GET["mensaje"] === "activado"): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-user-check me-2"></i>
            El aprendiz fue activado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($_GET["mensaje"] === "eliminado"): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            El aprendiz fue eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($_GET["mensaje"] === "creado"): ?>
    <div class="alert alert-success alert-dismissible fade show mensaje-aprendiz" role="alert">
        <i class="fas fa-user-check me-2"></i>
        El aprendiz fue registrado correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php endif; ?>

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h4>Gestión de Aprendices</h4>
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

        <?php if(isset($_GET["mensaje"]) && $_GET["mensaje"] == "eliminado"): ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        El aprendiz fue eliminado correctamente.

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

<?php endif; ?>

<?php if(isset($_GET["error"]) && $_GET["error"] == "tiene_asistencias"): ?>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-triangle-exclamation"></i>
        No se puede eliminar este aprendiz porque ya tiene asistencias registradas. Puedes desactivarlo en lugar de eliminarlo.

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

<?php endif; ?>

<?php if(isset($_GET["error"]) && $_GET["error"] == "eliminar"): ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-circle-xmark"></i>
        Ocurrió un error al intentar eliminar el aprendiz.

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

<?php endif; ?>

        <div class="row g-4 mb-4">

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle">

                <i class="fas fa-user-graduate"></i>

            </div>

            <div>

                <div class="stat-label">Aprendices</div>

                <h2 id="totalAprendices">

                    <?= $total["total"] ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle">

                <i class="fas fa-user-check"></i>

            </div>

            <div>

                <div class="stat-label">Activos</div>

                <h2 id="totalActivos">

                    <?= $activos["total"] ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle danger">

                <i class="fas fa-user-times"></i>

            </div>

            <div>

                <div class="stat-label">Inactivos</div>

                <h2 id="totalInactivos">

                    <?= $inactivos["total"] ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle">

                <i class="fas fa-fingerprint"></i>

            </div>

            <div>

                <div class="stat-label">Con huella</div>

                <h2 id="conHuella">

                    <?= $conHuella["total"] ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle warning">

                <i class="fas fa-hand-paper"></i>

            </div>

            <div>

                <div class="stat-label">Sin huella</div>

                <h2 id="sinHuella">

                    <?= $sinHuella["total"] ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-lg-2 col-md-4">

        <div class="stat-card">

            <div class="icon-circle">

                <i class="fas fa-book"></i>

            </div>

            <div>

                <div class="stat-label">Fichas</div>

                <h2 id="totalFichas">

                    <?= $fichas["total"] ?>

                </h2>

            </div>

        </div>

    </div>

</div>

            <!-- CABECERA -->
            <div class="page-header-card">
                <div class="page-header-icon">
                    <i class="fas fa-user-graduate" style="color:white;"></i>
                </div>
                <div>
                    <h2>Gestión de Aprendices</h2>
                    <p>Administración completa de aprendices registrados en el sistema biométrico.</p>
                </div>
            </div>

           

            <!-- TABLA -->
            <div class="table-card">

                <div class="table-card-header">
                    <i class="fas fa-list"></i>
                    Lista de Aprendices
                    <span class="total-badge" id="totalCount">
                        <?= count($aprendices) ?> registros
                    </span>
                </div>

                <div class="table-responsive">

                <div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-center">

            <div class="col-lg-3">

                <input
                    type="text"
                    id="buscarAprendiz"
                    class="form-control"
                    placeholder="🔍 Buscar aprendiz...">

            </div>

            <div class="col-lg-2">

                <select
                    id="filtroEstado"
                    class="form-select">

                    <option value="">Todos</option>

                    <option>Activo</option>

                    <option>Inactivo</option>

                </select>

            </div>

            <div class="col-lg-2">

                <select
                    id="filtroFicha"
                    class="form-select">

                    <option value="">Todas las fichas</option>

                    <?php

                    require_once "../config/conexion.php";

                    $sql=Conexion::conectar()->query("SELECT * FROM fichas");

                    foreach($sql as $f){

                        ?>

                        <option value="<?= $f["numero_ficha"] ?>">

                            <?= $f["numero_ficha"] ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="col-lg-5 text-end">

               <a href="crear_aprendiz.php" class="btn-nuevo">
    <i class="fas fa-plus"></i> Nuevo
</a>
                <button
    type="button"
    class="btn-excel"
    onclick="window.location.href='../reports/exportar_aprendices_excel.php'">

    <i class="fas fa-file-excel"></i>
    Excel

</button>

                
                <button
                    class="btn btn-secondary"
                    onclick="location.reload()">

                    <i class="fas fa-rotate"></i>

                </button>

            </div>

        </div>

    </div>

</div>
                    <table class="tabla-aprendices" id="tablaAprendices">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Documento</th>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Ficha</th>
                                <th>Huella</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            
                            <?php if(empty($aprendices)): ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-user-graduate"></i>
                                            <p>No hay aprendices registrados aún.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($aprendices as $a): ?>
                                    <tr>

                                        <td><span class="id-badge"><?= $a["id"] ?></span></td>

                                        <td><?= htmlspecialchars($a["documento"]) ?></td>

                                        <td class="nombre-cell">
                                            <?= htmlspecialchars($a["nombres"]) ?>
                                            <?= htmlspecialchars($a["apellidos"]) ?>
                                        </td>


                                        <td class="correo-cell"><?= htmlspecialchars($a["correo"]) ?></td>

                                        <td><?= htmlspecialchars($a["telefono"]) ?></td>

                                        <td><?= htmlspecialchars($a["numero_ficha"]) ?></td>

                                        <td>
                                            <?php if(!empty($a["huella_id"])): ?>
                                                <span class="badge-huella badge-registrada">
                                                    <i class="fas fa-check-circle"></i> Registrada
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-huella badge-pendiente">
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <<td class="acciones-cell">

    <!-- Ver perfil -->
    <a href="ver_aprendiz.php?id=<?= $a["id"] ?>"
       class="btn btn-sm btn-ver"
       title="Ver perfil">
        <i class="fas fa-eye"></i>
    </a>

    <!-- Editar -->
    <a href="editar_aprendiz.php?id=<?= $a["id"] ?>"
       class="btn btn-sm btn-editar"
       title="Editar aprendiz">
        <i class="fas fa-pen"></i>
    </a>

    <!-- Suspender o activar -->
    <?php if($a["estado"] === "Activo"): ?>

        <a href="../controllers/AprendizController.php?accion=suspender&id=<?= $a["id"] ?>"
           class="btn btn-sm btn-warning"
           title="Suspender aprendiz"
           onclick="return confirm('¿Deseas suspender a este aprendiz?');">
            <i class="fas fa-user-slash"></i>
        </a>

    <?php else: ?>

        <a href="../controllers/AprendizController.php?accion=activar&id=<?= $a["id"] ?>"
           class="btn btn-sm btn-success"
           title="Activar aprendiz"
           onclick="return confirm('¿Deseas activar a este aprendiz?');">
            <i class="fas fa-user-check"></i>
        </a>

    <?php endif; ?>

    <!-- Eliminar -->
    <a href="../controllers/AprendizController.php?accion=eliminar&id=<?= $a["id"] ?>"
       class="btn btn-sm btn-eliminar"
       title="Eliminar aprendiz"
       onclick="return confirm('¿Seguro que deseas eliminar este aprendiz?');">
        <i class="fas fa-trash"></i>
    </a>

    <!-- Reporte -->
    <a href="reporte_aprendiz.php?id=<?= $a["id"] ?>"
       class="btn btn-sm btn-reporte"
       title="Ver reporte de asistencia">
        <i class="fas fa-file-pdf"></i>
    </a>

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

/* ── Buscador ── */
document.getElementById("buscar").addEventListener("keyup", function(){
    const filtro = this.value.toLowerCase();
    const filas  = document.querySelectorAll("#tablaAprendices tbody tr");
    const badge  = document.getElementById("totalCount");
    let visibles  = 0;

    filas.forEach(fila => {
        const match = fila.innerText.toLowerCase().includes(filtro);
        fila.style.display = match ? "" : "none";
        if(match) visibles++;
    });

    badge.textContent = visibles + " registro" + (visibles !== 1 ? "s" : "");
});

</script>

<script>
const buscador = document.getElementById("buscarAprendiz");

const estado = document.getElementById("filtroEstado");

const ficha = document.getElementById("filtroFicha");

const filas = document.querySelectorAll("#tablaAprendices tbody tr");

function filtrar(){

    let texto = buscador.value.toLowerCase();

    let estadoSeleccionado = estado.value;

    let fichaSeleccionada = ficha.value;

    filas.forEach(fila=>{

        let contenido = fila.innerText.toLowerCase();

        let estadoFila = fila.dataset.estado;

        let fichaFila = fila.dataset.ficha;

        let mostrar = true;

        if(!contenido.includes(texto))
            mostrar = false;

        if(estadoSeleccionado!="" &&
           estadoFila!=estadoSeleccionado)
            mostrar = false;

        if(fichaSeleccionada!="" &&
           fichaFila!=fichaSeleccionada)
            mostrar = false;

        fila.style.display = mostrar ? "" : "none";

    });

}

buscador.addEventListener("keyup",filtrar);

estado.addEventListener("change",filtrar);

ficha.addEventListener("change",filtrar);

</script>

<?php require_once "layout/footer.php"; ?>