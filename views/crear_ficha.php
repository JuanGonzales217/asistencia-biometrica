<?php

session_start();

require_once "layout/header.php";

?>

<style>
    /* ==========================================================================
   BioAsist SENA — Design System
   Sistema Inteligente de Control de Asistencia
   ========================================================================== */

:root {
  /* Verdes marca */
  --verde-900: #0c1f14;
  --verde-800: #103622;
  --verde-700: #14532d;
  --verde-600: #16a34a;
  --verde-500: #22c55e;
  --verde-400: #4ade80;
  --verde-100: #dcfce7;
  --verde-50:  #f0fdf4;

  /* Acentos de estado */
  --azul-100: #dbeafe;
  --azul-600: #2563eb;
  --amarillo-100: #fef3c7;
  --amarillo-600: #d97706;
  --morado-100: #f3e8ff;
  --morado-600: #9333ea;
  --rojo-600: #dc2626;

  /* Neutros */
  --gris-25:  #fafbfa;
  --gris-50:  #f4f6f5;
  --gris-100: #eef1ef;
  --gris-200: #e2e8e4;
  --gris-300: #cbd5cf;
  --gris-400: #9aa79f;
  --gris-500: #6b7a70;
  --gris-600: #4b5b50;
  --gris-700: #33413a;
  --gris-900: #16201a;

  --blanco: #ffffff;

  /* Layout */
  --sidebar-width: 260px;
  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 20px;
  --radius-pill: 999px;

  --shadow-card: 0 1px 2px rgba(16, 24, 20, 0.04), 0 8px 24px rgba(16, 24, 20, 0.06);
  --shadow-card-hover: 0 4px 10px rgba(16, 24, 20, 0.06), 0 14px 32px rgba(16, 24, 20, 0.09);

  --font-base: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: var(--font-base);
  background: var(--gris-50);
  color: var(--gris-900);
  -webkit-font-smoothing: antialiased;
}

a { text-decoration: none; color: inherit; }
button { font-family: inherit; cursor: pointer; }

/* ==========================================================================
   Layout general: sidebar + contenido
   ========================================================================== */

.app-shell {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: var(--sidebar-width);
  flex-shrink: 0;
  background: linear-gradient(180deg, var(--verde-900) 0%, #0a1a10 100%);
  color: rgba(255, 255, 255, 0.85);
  display: flex;
  flex-direction: column;
  padding: 22px 16px;
  position: sticky;
  top: 0;
  height: 100vh;
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0 6px 22px;
}

.sidebar-brand-icon {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-sm);
  background: var(--verde-500);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--verde-900);
  font-size: 18px;
}

.sidebar-brand-title {
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
}

.sidebar-brand-subtitle {
  font-size: 11px;
  color: rgba(255, 255, 255, 0.5);
}

.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin-top: 8px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 14px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.65);
  transition: background 0.15s ease, color 0.15s ease;
}

.sidebar-link i { width: 18px; text-align: center; font-size: 14px; }

.sidebar-link:hover {
  background: rgba(255, 255, 255, 0.06);
  color: #fff;
}

.sidebar-link.active {
  background: var(--verde-500);
  color: var(--verde-900);
  font-weight: 700;
}

.sidebar-footer {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 12px;
}

.sidebar-status,
.sidebar-user {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: var(--radius-sm);
  background: rgba(255, 255, 255, 0.05);
  font-size: 13px;
}

.sidebar-status .dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--verde-500);
}

.sidebar-user-avatar {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: var(--verde-500);
  color: var(--verde-900);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 13px;
}

/* ==========================================================================
   Contenido principal
   ========================================================================== */

.main-content {
  flex: 1;
  padding: 26px 32px 60px;
  max-width: 100%;
}

.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 26px;
}

.topbar h1 {
  font-size: 22px;
  font-weight: 700;
  margin: 0;
}

.topbar p {
  margin: 2px 0 0;
  font-size: 13px;
  color: var(--gris-500);
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 18px;
}

.topbar-clock {
  text-align: right;
  font-size: 12px;
  color: var(--gris-500);
}

.topbar-clock strong {
  display: block;
  font-size: 16px;
  color: var(--gris-900);
}

.topbar-user {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--verde-500);
  color: #fff;
  padding: 8px 16px;
  border-radius: var(--radius-pill);
  font-weight: 600;
  font-size: 14px;
}

/* Título de página + acción */

.page-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 24px;
}

.page-title-row h3 {
  margin: 0 0 4px;
  font-size: 20px;
  font-weight: 700;
  color: var(--gris-900);
}

.page-title-row > div > p {
  margin: 0;
  font-size: 13.5px;
  color: var(--gris-500);
}

/* ==========================================================================
   Botones
   ========================================================================== */

.btn-volver,
.btn-cancelar {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 600;
  color: var(--gris-600);
  background: var(--blanco);
  border: 1px solid var(--gris-200);
  transition: background 0.15s ease, border-color 0.15s ease;
}

.btn-volver:hover,
.btn-cancelar:hover {
  background: var(--gris-100);
  border-color: var(--gris-300);
}

.btn-guardar,
.btn-primario,
.btn-nueva-ficha {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border: none;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  background: var(--verde-500);
  box-shadow: 0 2px 6px rgba(34, 197, 94, 0.35);
  transition: background 0.15s ease, transform 0.05s ease;
}

.btn-guardar:hover,
.btn-primario:hover,
.btn-nueva-ficha:hover {
  background: var(--verde-600);
}

.btn-guardar:active,
.btn-primario:active,
.btn-nueva-ficha:active {
  transform: translateY(1px);
}

.btn-excel {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 600;
  color: var(--verde-700);
  background: var(--verde-100);
  border: 1px solid var(--verde-100);
}

.btn-excel:hover { background: #d3f8e0; }

/* ==========================================================================
   Tarjetas de estadísticas (stats)
   ========================================================================== */

.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
  margin-bottom: 24px;
}

.stat-card {
  background: var(--blanco);
  border-radius: var(--radius-lg);
  padding: 20px;
  box-shadow: var(--shadow-card);
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.stat-card-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.stat-card-icon {
  width: 42px;
  height: 42px;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
}

.stat-card-icon.blue   { background: var(--azul-100); color: var(--azul-600); }
.stat-card-icon.green  { background: var(--verde-100); color: var(--verde-600); }
.stat-card-icon.yellow { background: var(--amarillo-100); color: var(--amarillo-600); }
.stat-card-icon.purple { background: var(--morado-100); color: var(--morado-600); }

.stat-card-label {
  font-size: 13px;
  color: var(--gris-500);
}

.stat-card-value {
  font-size: 28px;
  font-weight: 800;
  color: var(--gris-900);
}

.stat-card-caption {
  font-size: 12px;
  color: var(--gris-400);
}

/* ==========================================================================
   Tarjeta contenedora (form-card, panel, listado)
   ========================================================================== */

.form-card,
.panel-card {
  background: var(--blanco);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-card);
  overflow: hidden;
}

.form-card {
  padding: 26px 28px 28px;
}

.form-card-header {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 22px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--gris-100);
}

.form-icon {
  width: 46px;
  height: 46px;
  border-radius: var(--radius-sm);
  background: var(--verde-100);
  color: var(--verde-600);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
}

.form-card-header h5 {
  margin: 0 0 2px;
  font-size: 16px;
  font-weight: 700;
  color: var(--gris-900);
}

.form-card-header p {
  margin: 0;
  font-size: 13px;
  color: var(--gris-500);
}

/* Banner verde de sección (listados) */

.panel-header {
  display: flex;
  align-items: center;
  gap: 12px;
  background: linear-gradient(90deg, var(--verde-700), var(--verde-600));
  color: #fff;
  padding: 16px 22px;
  font-weight: 700;
  font-size: 15px;
}

.panel-header .badge-count {
  background: rgba(255, 255, 255, 0.2);
  padding: 3px 12px;
  border-radius: var(--radius-pill);
  font-size: 12.5px;
  font-weight: 600;
}

/* ==========================================================================
   Formularios
   ========================================================================== */

.row.g-3 {
  display: flex;
  flex-wrap: wrap;
  gap: 18px 16px;
}

.row.g-3 > [class*="col-"] { flex: 1 1 100%; }

.col-md-4 { flex-basis: calc(33.333% - 11px); }
.col-md-6 { flex-basis: calc(50% - 8px); }
.col-md-8 { flex-basis: calc(66.666% - 5px); }
.col-md-12 { flex-basis: 100%; }

@media (max-width: 860px) {
  .col-md-4, .col-md-6, .col-md-8, .col-md-12 { flex-basis: 100%; }
  .stats-row { grid-template-columns: repeat(2, 1fr); }
}

.form-label {
  display: block;
  margin-bottom: 7px;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--gris-700);
}

.form-label .text-danger { color: var(--rojo-600); }

.form-control,
.form-select {
  width: 100%;
  padding: 10px 14px;
  font-size: 14px;
  color: var(--gris-900);
  background: var(--gris-25);
  border: 1px solid var(--gris-200);
  border-radius: var(--radius-sm);
  transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}

.form-control::placeholder { color: var(--gris-400); }

.form-control:focus,
.form-select:focus {
  outline: none;
  background: var(--blanco);
  border-color: var(--verde-500);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
}

.form-select {
  appearance: none;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' stroke='%236b7a70' stroke-width='1.6' fill='none' fill-rule='evenodd'/></svg>");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 34px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 26px;
  padding-top: 20px;
  border-top: 1px solid var(--gris-100);
}

/* ==========================================================================
   Tabla de listado (fichas, aprendices, instructores…)
   ========================================================================== */

.table-search-row {
  display: flex;
  gap: 14px;
  align-items: center;
  padding: 18px 22px;
  border-bottom: 1px solid var(--gris-100);
}

.table-search-row input[type="search"],
.table-search-row .search-input {
  flex: 1;
  padding: 10px 14px 10px 38px;
  border: 1px solid var(--gris-200);
  border-radius: var(--radius-sm);
  font-size: 14px;
  background: var(--gris-25) url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%239aa79f' stroke-width='1.6'><circle cx='7' cy='7' r='5.5'/><path d='M11 11l3.5 3.5'/></svg>") no-repeat 12px center;
}

.table-filter-select {
  padding: 10px 14px;
  border: 1px solid var(--gris-200);
  border-radius: var(--radius-sm);
  font-size: 14px;
  background: var(--gris-25);
  min-width: 160px;
}

table.data-table {
  width: 100%;
  border-collapse: collapse;
}

table.data-table thead th {
  text-align: left;
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: var(--gris-500);
  padding: 14px 22px;
  border-bottom: 1px solid var(--gris-100);
  background: var(--gris-25);
}

table.data-table tbody td {
  padding: 16px 22px;
  font-size: 14px;
  color: var(--gris-800, #263029);
  border-bottom: 1px solid var(--gris-100);
  vertical-align: middle;
}

table.data-table tbody tr:hover { background: var(--verde-50); }
table.data-table tbody tr:last-child td { border-bottom: none; }

.pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 12px;
  border-radius: var(--radius-pill);
  font-size: 12.5px;
  font-weight: 600;
}

.pill-ficha { background: var(--verde-50); color: var(--verde-700); border: 1px solid var(--verde-100); }
.pill-jornada { background: var(--azul-100); color: var(--azul-600); }

.badge-estado {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 12px;
  border-radius: var(--radius-pill);
  font-size: 12.5px;
  font-weight: 600;
}

.badge-estado.activa { background: var(--verde-100); color: var(--verde-700); }
.badge-estado.activa::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--verde-600); }

.badge-estado.suspendida { background: var(--amarillo-100); color: var(--amarillo-600); }
.badge-estado.suspendida::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--amarillo-600); }

.cupos-bar {
  margin-top: 6px;
  height: 4px;
  border-radius: var(--radius-pill);
  background: var(--gris-100);
  overflow: hidden;
  width: 100px;
}

.cupos-bar-fill {
  height: 100%;
  background: var(--verde-500);
  border-radius: var(--radius-pill);
}

.row-actions {
  display: flex;
  align-items: center;
  gap: 6px;
}

.row-actions button {
  width: 32px;
  height: 32px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--gris-200);
  background: var(--blanco);
  color: var(--gris-500);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
}

.row-actions button:hover { background: var(--gris-100); color: var(--gris-900); }
</style>

<main class="main-content">

    <div class="page-title-row">

        <div>
            <h3>Nueva ficha</h3>
            <p>Registra una nueva ficha de formación en el sistema.</p>
        </div>

        <a href="fichas.php" class="btn-volver">
            <i class="fas fa-arrow-left"></i>
            Volver a fichas
        </a>

    </div>

    <div class="form-card">

        <div class="form-card-header">
            <div class="form-icon">
                <i class="fas fa-layer-group"></i>
            </div>

            <div>
                <h5>Información de la ficha</h5>
                <p>Completa los datos académicos y administrativos.</p>
            </div>
        </div>

        <form action="../controllers/FichaController.php" method="POST">

            <input type="hidden" name="accion" value="crear">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Número de ficha <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="numero_ficha"
                        class="form-control"
                        placeholder="Ej: 2876543"
                        required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Programa de formación <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="programa"
                        class="form-control"
                        placeholder="Ej: Análisis y Desarrollo de Software"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jornada <span class="text-danger">*</span></label>

                    <select name="jornada" class="form-select" required>
                        <option value="">Selecciona una jornada</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                        <option value="Mixta">Mixta</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nivel de formación</label>

                    <select name="nivel_formacion" class="form-select">
                        <option value="">Selecciona un nivel</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Tecnólogo">Tecnólogo</option>
                        <option value="Especialización tecnológica">Especialización tecnológica</option>
                        <option value="Complementario">Complementario</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cupo máximo <span class="text-danger">*</span></label>

                    <input
                        type="number"
                        name="cupo_maximo"
                        class="form-control"
                        value="35"
                        min="1"
                        max="200"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha de inicio</label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha estimada de finalización</label>

                    <input
                        type="date"
                        name="fecha_fin"
                        class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Instructor responsable</label>

                    <input
                        type="text"
                        name="instructor"
                        class="form-control"
                        placeholder="Ej: Carlos Pérez">
                </div>

            </div>

            <div class="form-actions">

                <a href="fichas.php" class="btn-cancelar">
                    Cancelar
                </a>

                <button type="submit" class="btn-guardar">
                    <i class="fas fa-save"></i>
                    Guardar ficha
                </button>

            </div>

        </form>

    </div>

</main>

<?php require_once "layout/footer.php"; ?>