<?php

session_start();

require_once "layout/header.php";
require_once "../models/ProgramaModel.php";

$programas = ProgramaModel::listarActivos();

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
    background: rgba(255,255,255,.2); border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff; flex-shrink: 0;
}
.form-card-header h3    { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
.form-card-header small { color: rgba(255,255,255,.75); font-size: 12px; }

/* ══════════════════════════════════════
   CUERPO DEL FORMULARIO
══════════════════════════════════════ */
.form-body { padding: 26px 26px 0; }

/* Grids */
.form-grid-1-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 0 24px; }
.form-grid-3   { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 24px; }
.form-grid-2   { display: grid; grid-template-columns: 1fr 1fr; gap: 0 24px; }

.form-group      { margin-bottom: 20px; }
.form-group.full { grid-column: 1 / -1; }

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

.input-wrap input,
.input-wrap select {
    width: 100%;
    padding: 11px 14px 11px 38px;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    font-size: 14px; color: #333;
    outline: none; background: #fafafa;
    transition: border-color .2s, background .2s;
    appearance: none; -webkit-appearance: none;
    font-family: inherit;
}
.input-wrap input:focus,
.input-wrap select:focus { border-color: var(--verde); background: #fff; }
.input-wrap input::placeholder { color: #ccc; }

.select-arrow {
    position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%);
    color: #bbb; font-size: 11px; pointer-events: none;
}

/* Separadores de sección */
.section-sep {
    display: flex; align-items: center; gap: 10px;
    font-size: 11px; font-weight: 700; color: #aaa;
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px; grid-column: 1 / -1;
}
.section-sep::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }
.section-sep i { color: var(--verde); font-size: 12px; }

/* ── Footer botones ── */
.form-footer {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 16px 26px 24px;
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
                <h4>Nueva Ficha</h4>
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
                    <h2><i class="fas fa-folder-plus" style="color:var(--verde);"></i> Nueva ficha</h2>
                    <p>Registra una nueva ficha de formación en el sistema.</p>
                </div>
                <a href="fichas.php" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver a fichas
                </a>
            </div>

            <!-- FORMULARIO -->
            <div class="form-card">

                <div class="form-card-header">
                    <div class="header-icon"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <h3>Información de la ficha</h3>
                        <small>Completa los datos académicos y administrativos</small>
                    </div>
                </div>

                <form action="../controllers/FichaController.php" method="POST">

                    <input type="hidden" name="accion" value="crear">

                    <div class="form-body">

                        <!-- Número + programa -->
                        <div class="form-grid-1-2">

                            <div class="form-group">
                                <label class="field-label">
                                    Número de ficha <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="text" name="numero_ficha"
                                        placeholder="Ej: 2876543" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="field-label">
                                    Programa de formación <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-book input-icon"></i>
                                    <select name="programa_id" required>
                                        <option value="">Selecciona un programa</option>
                                        <?php foreach($programas as $p): ?>
                                            <option value="<?= $p["id"] ?>">
                                                <?= htmlspecialchars($p["codigo"]) ?> —
                                                <?= htmlspecialchars($p["nombre"]) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                            </div>

                        </div>

                        <!-- Sep: configuración -->
                        <div class="form-grid-3">
                            <div class="section-sep">
                                <i class="fas fa-sliders"></i> Configuración
                            </div>
                        </div>

                        <!-- Jornada + nivel + cupo -->
                        <div class="form-grid-3">

                            <div class="form-group">
                                <label class="field-label">
                                    Jornada <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-clock input-icon"></i>
                                    <select name="jornada" required>
                                        <option value="">Selecciona una jornada</option>
                                        <option value="Mañana">Mañana</option>
                                        <option value="Tarde">Tarde</option>
                                        <option value="Noche">Noche</option>
                                        <option value="Mixta">Mixta</option>
                                        <option value="Virtual">Virtual</option>
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="field-label">Nivel de formación</label>
                                <div class="input-wrap">
                                    <i class="fas fa-layer-group input-icon"></i>
                                    <select name="nivel_formacion">
                                        <option value="">Selecciona un nivel</option>
                                        <option value="Técnico">Técnico</option>
                                        <option value="Tecnólogo">Tecnólogo</option>
                                        <option value="Especialización tecnológica">Especialización tecnológica</option>
                                        <option value="Complementario">Complementario</option>
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="field-label">
                                    Cupo máximo <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="number" name="cupo_maximo"
                                        value="35" min="1" max="200" required>
                                </div>
                            </div>

                        </div>

                        <!-- Sep: fechas -->
                        <div class="form-grid-3">
                            <div class="section-sep">
                                <i class="fas fa-calendar"></i> Fechas y responsable
                            </div>
                        </div>

                        <!-- Fechas + instructor -->
                        <div class="form-grid-3">

                            <div class="form-group">
                                <label class="field-label">Fecha de inicio</label>
                                <div class="input-wrap">
                                    <i class="fas fa-calendar-day input-icon"></i>
                                    <input type="date" name="fecha_inicio">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="field-label">Fecha estimada de finalización</label>
                                <div class="input-wrap">
                                    <i class="fas fa-calendar-check input-icon"></i>
                                    <input type="date" name="fecha_fin">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="field-label">Instructor responsable</label>
                                <div class="input-wrap">
                                    <i class="fas fa-chalkboard-teacher input-icon"></i>
                                    <input type="text" name="instructor"
                                        placeholder="Ej: Carlos Pérez">
                                </div>
                            </div>

                        </div>

                    </div><!-- /form-body -->

                    <div class="form-footer">
                        <a href="fichas.php" class="btn-cancelar">
                            <i class="fas fa-xmark"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-guardar">
                            <i class="fas fa-floppy-disk"></i> Guardar ficha
                        </button>
                    </div>

                </form>

            </div><!-- /form-card -->

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