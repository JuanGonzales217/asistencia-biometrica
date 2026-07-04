<?php

session_start();

require_once "../models/FichaModel.php";

$fichasActivas = FichaModel::listarActivas();

require_once "../models/FichaModel.php";

$fichas = FichaModel::listar();

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
   CARD FORMULARIO
══════════════════════════════════════ */
.form-card {
    background: #fff;
    border-radius: var(--card-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.form-card-header {
    background: linear-gradient(90deg, #1a6b2e, #39A900);
    padding: 16px 24px;
    display: flex; align-items: center; gap: 12px;
}

.form-card-header .header-icon {
    width: 42px; height: 42px;
    background: rgba(255,255,255,.2);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff; flex-shrink: 0;
}

.form-card-header h3   { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
.form-card-header small { color: rgba(255,255,255,.75); font-size: 12px; }

/* ── Nota campos obligatorios ── */
.required-note {
    display: flex; align-items: center; gap: 6px;
    background: var(--verde-claro);
    border-left: 3px solid var(--verde);
    border-radius: 0 8px 8px 0;
    padding: 10px 16px;
    font-size: 12px; color: #1a5c00;
    margin: 20px 24px 0;
}

.required-note i { color: var(--verde); }

/* ── Grid del formulario ── */
.form-body { padding: 20px 24px 0; }

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 24px;
}

.form-group       { margin-bottom: 18px; }
.form-group.full  { grid-column: 1 / -1; }

.field-label {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700;
    color: #555; text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 7px;
}

.field-label .req { color: #e53935; font-size: 14px; line-height: 1; }

.input-wrap { position: relative; }

.input-icon {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%);
    color: #bbb; font-size: 14px; pointer-events: none;
}

/* Para textarea el ícono va arriba */
.input-wrap.textarea-wrap .input-icon {
    top: 14px; transform: none;
}

.input-wrap input,
.input-wrap select,
.input-wrap textarea {
    width: 100%;
    padding: 11px 14px 11px 38px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px; color: #333;
    outline: none; background: #fafafa;
    transition: border-color .2s, background .2s;
    appearance: none; -webkit-appearance: none;
    font-family: inherit;
}

.input-wrap input:focus,
.input-wrap select:focus,
.input-wrap textarea:focus {
    border-color: var(--verde); background: #fff;
}

.input-wrap input::placeholder,
.input-wrap textarea::placeholder { color: #ccc; }

.select-arrow {
    position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%);
    color: #bbb; font-size: 11px; pointer-events: none;
}

/* Campo opcional */
.field-hint {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: #999; margin-top: 5px;
}
.field-hint i { font-size: 10px; }

/* Badge opcional */
.badge-opcional {
    display: inline-block;
    background: #f0f0f0; color: #888;
    border-radius: 6px; padding: 1px 7px;
    font-size: 10px; font-weight: 600;
    text-transform: none; letter-spacing: 0;
    margin-left: 4px; vertical-align: middle;
}

/* Sección huella */
.section-label {
    grid-column: 1 / -1;
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; font-weight: 700; color: #888;
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}

.section-label::after {
    content: '';
    flex: 1; height: 1px; background: #eee;
}

/* ── Footer botones ── */
.form-footer {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 16px 24px 24px;
    border-top: 1px solid #f0f0f0;
    margin-top: 4px;
}

.btn-cancelar {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px;
    background: #f4f4f4; color: #666;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: background .2s;
}
.btn-cancelar:hover { background: #e8e8e8; color: #444; }

.btn-guardar {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 24px;
    background: var(--verde); color: #fff;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .2s, transform .1s;
}
.btn-guardar:hover  { background: var(--verde-oscuro); }
.btn-guardar:active { transform: scale(.98); }

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
                <h4>Nuevo Aprendiz</h4>
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
                    <h2><i class="fas fa-user-plus" style="color:var(--verde);"></i> Registrar nuevo aprendiz</h2>
                    <p>Completa la información para agregar un aprendiz al sistema biométrico.</p>
                </div>
                <a href="aprendices.php" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a aprendices
                </a>
            </div>

            <!-- FORMULARIO -->
            <div class="form-card">

                <div class="form-card-header">
                    <div class="header-icon"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <h3>Datos del aprendiz</h3>
                        <small>Los campos marcados con * son obligatorios</small>
                    </div>
                </div>

                <div class="required-note">
                    <i class="fas fa-circle-info"></i>
                    Todos los campos marcados con <strong>&nbsp;*&nbsp;</strong> son obligatorios para registrar el aprendiz.
                </div>

                <?php if(isset($_GET["mensaje"]) && $_GET["mensaje"] === "ficha_no_disponible"): ?>
    <div class="alert alert-warning alert-dismissible fade show mensaje-aprendiz" role="alert">
        <i class="fas fa-triangle-exclamation me-2"></i>
        La ficha seleccionada no está activa. Elige una ficha activa para registrar al aprendiz.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

                <form action="../controllers/AprendizController.php" method="POST" id="formCrearAprendiz">

                    <div class="form-body">
                        <div class="form-grid">

                            <!-- Documento -->
                            <div class="form-group">
                                <label class="field-label">
                                    Documento <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input
                                        type="number"
                                        name="documento"
                                        placeholder="Ej: 1042151376"
                                        required>
                                </div>
                            </div>

                            <!-- Ficha -->
                            <div class="form-group">
                                <label class="field-label">
                                    Ficha <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-folder input-icon"></i>
                                   <select name="ficha_id" class="form-select" required>

    <option value="">Selecciona una ficha activa</option>

    <?php foreach($fichasActivas as $ficha): ?>

        <option value="<?= $ficha["id"] ?>">
            <?= htmlspecialchars($ficha["numero_ficha"]) ?>
            -
            <?= htmlspecialchars($ficha["programa"]) ?>
            (<?= htmlspecialchars($ficha["jornada"]) ?>)
        </option>

    <?php endforeach; ?>

</select>

<?php if(empty($fichasActivas)): ?>
    <small class="text-danger">
        No hay fichas activas disponibles. Primero debes crear o reactivar una ficha.
    </small>
<?php endif; ?>
                                    <span class="select-arrow">▼</span>
                                </div>
                            </div>

                            <!-- Nombres -->
                            <div class="form-group">
                                <label class="field-label">
                                    Nombres <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-user input-icon"></i>
                                    <input
                                        type="text"
                                        name="nombres"
                                        placeholder="Ej: Juan Horacio"
                                        required>
                                </div>
                            </div>

                            <!-- Apellidos -->
                            <div class="form-group">
                                <label class="field-label">
                                    Apellidos <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-user input-icon"></i>
                                    <input
                                        type="text"
                                        name="apellidos"
                                        placeholder="Ej: González Ramírez"
                                        required>
                                </div>
                            </div>

                            <!-- Correo -->
                            <div class="form-group">
                                <label class="field-label">
                                    Correo electrónico <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input
                                        type="email"
                                        name="correo"
                                        placeholder="ejemplo@correo.com"
                                        required>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group">
                                <label class="field-label">
                                    Teléfono <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input
                                        type="tel"
                                        name="telefono"
                                        placeholder="Ej: 3001234567"
                                        minlength="10"
                                        maxlength="10"
                                        required>
                                </div>
                            </div>

                            <!-- Separador sección huella -->
                            <div class="section-label">
                                <i class="fas fa-fingerprint" style="color:var(--verde);"></i>
                                Biometría
                            </div>

                            <!-- Código de huella -->
                            <div class="form-group full">
                                <label class="field-label">
                                    Código de huella
                                    <span class="badge-opcional">Opcional</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-fingerprint input-icon"></i>
                                    <input
                                        type="text"
                                        name="huella_id"
                                        placeholder="Ej: 1003">
                                </div>
                                <div class="field-hint">
                                    <i class="fas fa-circle-info"></i>
                                    Puedes dejarlo vacío si todavía no se ha registrado la huella.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-footer">
                        <a href="aprendices.php" class="btn-cancelar">
                            <i class="fas fa-xmark"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-guardar">
                            <i class="fas fa-floppy-disk"></i> Guardar aprendiz
                        </button>
                    </div>

                </form>

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