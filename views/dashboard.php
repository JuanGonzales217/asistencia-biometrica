<?php
session_start();

if(!isset($_SESSION["nombre"])){
    header("Location: ../index.php");
    exit();
}

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
    font-size: 14px;
    color: #1a1a1a;
}

/* ══════════════════════════════════════
   LAYOUT: sidebar fijo + contenido
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

.sidebar-brand h5 {
    color: #fff; font-size: 14px; font-weight: 700; margin: 0;
}

.sidebar-brand small {
    color: #b9c9b0; font-size: 10px; display: block; margin-top: 1px;
}

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
    background: var(--verde);
    border-radius: 50%;
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

.topbar-left h4   { font-size: 18px; font-weight: 700; margin: 0; }
.topbar-left small { color: #888; font-size: 12px; }

.topbar-right { display: flex; align-items: center; gap: 14px; }

.time-box { text-align: right; }
.time-box .hora  { font-size: 20px; font-weight: 700; color: #1a1a1a; line-height: 1; }
.time-box .fecha { font-size: 11px; color: #888; margin-top: 2px; }

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
    background: var(--verde);
    color: #fff;
    border: none; border-radius: 10px;
    padding: 8px 14px;
    font-size: 13px; font-weight: 600;
    cursor: pointer;
}

/* ══════════════════════════════════════
   CONTENIDO
══════════════════════════════════════ */
.content {
    padding: 24px;
    flex: 1;
}

/* Fecha encabezado */
.fecha-header {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    margin-bottom: 20px;
    color: #888;
    font-size: 13px;
}

.fecha-header strong { color: #333; }

/* ══════════════════════════════════════
   TARJETAS STAT
══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow);
    border: 1px solid #eef1ee;
}

.stat-card .ic {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}

.ic-green  { background: var(--verde-claro); color: var(--verde); }
.ic-yellow { background: #fff6e0;            color: #e6a400; }
.ic-red    { background: #fdeaea;            color: #e53935; }
.ic-blue   { background: #e8f0fe;            color: #1a73e8; }

.stat-label { font-size: 12px; color: #888; margin-bottom: 2px; }
.stat-num   { font-size: 28px; font-weight: 700; line-height: 1; margin-bottom: 3px; }
.stat-sub   { font-size: 12px; color: #888; }
.stat-sub.ok  { color: var(--verde); }
.stat-sub.wr  { color: #e6a400; }
.stat-sub.er  { color: #e53935; }

/* ══════════════════════════════════════
   FILA MEDIA: gráfico + actividad
══════════════════════════════════════ */
.mid-grid {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 16px;
    margin-bottom: 20px;
}

/* ══════════════════════════════════════
   FILA BAJA: accesos + estado
══════════════════════════════════════ */
.bot-grid {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 16px;
}

.estado-side {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 16px;
}

/* ══════════════════════════════════════
   PANEL CARD (tarjeta genérica)
══════════════════════════════════════ */
.panel {
    background: #fff;
    border-radius: var(--card-radius);
    border: 1px solid #eef1ee;
    padding: 18px 20px;
    box-shadow: var(--shadow);
    height: 100%;
}

.panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.panel-head h6 {
    font-size: 14px; font-weight: 600; margin: 0;
    display: flex; align-items: center; gap: 7px;
    color: #1a1a1a;
}

.panel-head h6 i { color: var(--verde); }

.panel-head select {
    border: 1px solid #e3e3e3;
    border-radius: 8px;
    font-size: 12px;
    padding: 5px 10px;
    color: #555;
    background: #fafafa;
    outline: none;
}

/* ══════════════════════════════════════
   ACTIVIDAD
══════════════════════════════════════ */
.act-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f3f3f3;
}
.act-item:last-of-type { border-bottom: none; }

.act-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #eef1ee;
    display: flex; align-items: center; justify-content: center;
    color: #9aa39a; font-size: 14px; flex-shrink: 0;
}

.act-info { flex: 1; min-width: 0; }
.act-info p    { font-size: 13px; font-weight: 600; margin: 0; }
.act-info span { font-size: 11px; color: #888; }
.act-hora      { font-size: 11px; color: #aaa; white-space: nowrap; }

.act-check {
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--verde); color: #fff;
    font-size: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.ver-todas {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 12px; font-size: 13px; color: #555; text-decoration: none;
}
.ver-todas:hover { color: var(--verde); }

/* ══════════════════════════════════════
   ACCESOS RÁPIDOS
══════════════════════════════════════ */
.accesos-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.acceso-btn {
    background: var(--verde-claro);
    border-radius: 12px;
    border: none;
    padding: 16px 10px;
    text-align: center;
    color: #1f5500;
    text-decoration: none;
    display: block;
    font-size: 12px; font-weight: 500;
    transition: .15s;
    line-height: 1.3;
}

.acceso-btn i {
    display: block;
    font-size: 22px;
    margin-bottom: 8px;
    color: var(--verde);
}

.acceso-btn:hover { background: var(--verde); color: #fff; }
.acceso-btn:hover i { color: #fff; }

/* ══════════════════════════════════════
   ESTADO DEL SISTEMA
══════════════════════════════════════ */
.estado-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 9px 0;
    font-size: 13px; color: #444;
    border-bottom: 1px solid #f3f3f3;
}
.estado-item:last-of-type { border-bottom: none; }

.estado-label { display: flex; align-items: center; gap: 8px; }
.estado-label i { color: #9aa39a; width: 16px; }

.dot-ok { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: var(--verde); margin-right: 4px; }

.barra {
    width: 80px; height: 6px;
    background: #eee; border-radius: 4px;
    overflow: hidden; display: inline-block;
    margin-right: 6px; vertical-align: middle;
}
.barra-fill { height: 100%; background: var(--verde); }

.estado-valor { font-size: 12px; color: #555; }

/* Caja "todo en orden" */
.ok-box {
    background: var(--verde);
    border-radius: 14px;
    color: #fff;
    text-align: center;
    padding: 20px 14px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    height: 100%;
    min-height: 140px;
}

.ok-box i     { font-size: 30px; margin-bottom: 10px; }
.ok-box strong { font-size: 13px; display: block; }

</style>

<!-- ══════════════════════════════════════
     ESTRUCTURA HTML
══════════════════════════════════════ -->
<div class="dash-layout">

    <!-- SIDEBAR -->
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
                <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Inicio</a>
                <a href="aprendices.php"><i class="fas fa-user-graduate"></i> Aprendices</a>
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
                    <p><?php echo $_SESSION["nombre"]; ?></p>
                    <span>Administrador</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ÁREA PRINCIPAL -->
    <div class="main-area">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h4>Dashboard</h4>
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
                        <?php echo $_SESSION["nombre"]; ?>
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

            <!-- Fecha -->
            <div class="fecha-header">
                <i class="fas fa-calendar-day"></i>
                <strong>
                    <?php
                        $dias = ["Sunday"=>"Domingo","Monday"=>"Lunes","Tuesday"=>"Martes",
                                 "Wednesday"=>"Miércoles","Thursday"=>"Jueves",
                                 "Friday"=>"Viernes","Saturday"=>"Sábado"];
                        $meses = ["January"=>"enero","February"=>"febrero","March"=>"marzo",
                                  "April"=>"abril","May"=>"mayo","June"=>"junio",
                                  "July"=>"julio","August"=>"agosto","September"=>"septiembre",
                                  "October"=>"octubre","November"=>"noviembre","December"=>"diciembre"];
                        echo $dias[date("l")] . ", " . date("d") . " de " . $meses[date("F")] . " de " . date("Y");
                    ?>
                </strong>
            </div>

            <!-- STATS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="ic ic-blue"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-label">Aprendices</div>
                        <div class="stat-num" id="totalAprendices"><?php echo $total["total"] ?? 0; ?></div>
                        <div class="stat-sub">Total registrados</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-label">Presentes hoy</div>
                        <div class="stat-num" id="totalPresentes"><?php echo $presentes["total"] ?? 0; ?></div>
                        <div class="stat-sub ok">75% del total</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-yellow"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-label">Tardanzas hoy</div>
                        <div class="stat-num" id="totalTardes"><?php echo $tardes["total"] ?? 0; ?></div>
                        <div class="stat-sub wr">5% del total</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="ic ic-red"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <div class="stat-label">Ausentes hoy</div>
                        <div class="stat-num" id="totalAusentes"><?php echo $ausentes["total"] ?? 0; ?></div>
                        <div class="stat-sub er">20% del total</div>
                    </div>
                </div>

            </div>

            <!-- GRÁFICO + ACTIVIDAD -->
            <div class="mid-grid">

                <div class="panel">
                    <div class="panel-head">
                        <h6><i class="fas fa-chart-bar"></i> Asistencia últimos 7 días</h6>
                        <select>
                            <option>Últimos 7 días</option>
                            <option>Últimos 30 días</option>
                        </select>
                    </div>
                    <canvas id="graficoAsistencia" height="200"></canvas>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h6><i class="fas fa-heartbeat"></i> Actividad en tiempo real</h6>
                    </div>

                   <div id="actividadTiempoReal">

</div>

                    <a href="historial.php" class="ver-todas">
                        Ver todas las actividades
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>

            <!-- ACCESOS + ESTADO -->
            <div class="bot-grid">

                <div class="panel">
                    <div class="panel-head">
                        <h6><i class="fas fa-bolt"></i> Accesos rápidos</h6>
                    </div>
                    <div class="accesos-grid">
                        <a href="asistencia.php" class="acceso-btn">
                            <i class="fas fa-fingerprint"></i>Registrar asistencia
                        </a>
                        <a href="aprendices.php" class="acceso-btn">
                            <i class="fas fa-user-graduate"></i>Ver aprendices
                        </a>
                        <a href="reportes.php" class="acceso-btn">
                            <i class="fas fa-file-alt"></i>Generar reporte
                        </a>
                        <a href="historial.php" class="acceso-btn">
                            <i class="fas fa-history"></i>Ver historial
                        </a>
                    </div>
                </div>

                <div class="estado-side">

                    <div class="panel">
                        <div class="panel-head">
                            <h6><i class="fas fa-shield-alt"></i> Estado del sistema</h6>
                        </div>

                        <div class="estado-item">
                            <div class="estado-label"><i class="fas fa-database"></i> Base de datos</div>
                            <div class="estado-valor"><span class="dot-ok"></span>Conectada</div>
                        </div>

                        <div class="estado-item">
                            <div class="estado-label"><i class="fas fa-fingerprint"></i> Dispositivo biométrico</div>
                            <div class="estado-valor"><span class="dot-ok"></span>Conectado</div>
                        </div>

                        <div class="estado-item">
                            <div class="estado-label"><i class="fas fa-hdd"></i> Almacenamiento</div>
                            <div class="estado-valor">
                                <span class="barra"><span class="barra-fill" style="width:68%"></span></span>68%
                            </div>
                        </div>

                        <div class="estado-item">
                            <div class="estado-label"><i class="fas fa-circle-notch"></i> Sistema</div>
                            <div class="estado-valor"><span class="dot-ok"></span>Activo</div>
                        </div>
                    </div>

                    <div class="ok-box">
                        <i class="fas fa-shield-check"></i>
                        <strong>Todo en orden</strong>
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

/* ── Gráfico ── */
new Chart(document.getElementById('graficoAsistencia'), {
    type: 'bar',
    data: {
        labels: ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'],
        datasets: [{
            label: 'Asistencias',
            data: [120, 180, 150, 220, 190, 90, 70],
            backgroundColor: '#39A900',
            borderRadius: 6,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position:'bottom', labels:{ boxWidth:12, color:'#777' } }
        },
        scales: {
            y: { beginAtZero:true, grid:{ color:'#f0f0f0' } },
            x: { grid:{ display:false } }
        }
    }
});

/* ── Actualización en tiempo real ── */
async function actualizarDashboard(){
    try {
        const r = await fetch("../api/dashboard.php");
        const d = await r.json();
        document.getElementById("totalAprendices").textContent = d.aprendices;
        document.getElementById("totalPresentes").textContent  = d.presentes;
        document.getElementById("totalTardes").textContent     = d.tardes;
        document.getElementById("totalAusentes").textContent   = d.ausentes;
        // Construir la actividad en tiempo real
let actividadHTML = "";

d.actividad.forEach(item => {

    actividadHTML += `
        <div class="actividad-item">

            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>

            <div class="info">
                <p>${item.nombres} ${item.apellidos}</p>
                <span>${item.estado}</span>
            </div>

            <div class="hora">
                ${item.hora}
            </div>

            <div class="check-ok">
                <i class="fas fa-check"></i>
            </div>

        </div>
    `;

});

document.getElementById("actividadTiempoReal").innerHTML = actividadHTML;
    } catch(e){ console.log(e); }
}
actualizarDashboard();
setInterval(actualizarDashboard, 5000);

</script>

<?php include "layout/footer.php"; ?>