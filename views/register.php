<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Control de Asistencia SENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f2f5;
            padding: 30px 0;
        }

        /* ── Contenedor principal ── */
        .register-wrapper {
            display: flex;
            width: 960px;
            min-height: 600px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* ── Panel izquierdo ── */
        .register-left {
            width: 320px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #0a3d1a 0%, #1a6b2e 40%, #2d8f45 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            overflow: hidden;
        }

        .register-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600') center / cover;
            opacity: 0.2;
        }

        .left-content {
            position: relative;
            z-index: 1;
        }

        .sena-logo-left {
            text-align: center;
            margin-bottom: 30px;
        }

        .sena-logo-left img {
            width: 80px;
            filter: brightness(0) invert(1);
        }

        .bienvenido {
            color: #7fff7f;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .titulo {
            color: white;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 32px;
        }

        .titulo span {
            color: #7fff7f;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 13px;
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
        }

        /* ── Panel derecho ── */
        .register-right {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 50px;
        }

        .register-card {
            width: 100%;
        }

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            font-size: 12px;
            color: #aaa;
            margin-bottom: 24px;
        }

        .breadcrumb li a {
            color: #2d8f45;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb li a:hover {
            text-decoration: underline;
        }

        .breadcrumb li.active {
            color: #888;
        }

        .breadcrumb li:not(:last-child)::after {
            content: '/';
            margin-left: 6px;
            color: #ccc;
        }

        /* ── Cabecera del formulario ── */
        .logo-top {
            text-align: center;
            margin-bottom: 6px;
        }

        .logo-top img {
            width: 64px;
        }

        .register-card h2 {
            text-align: center;
            color: #1a1a1a;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-bottom: 24px;
        }

        /* ── Grid de campos ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 15px;
            pointer-events: none;
        }

        .input-wrap input,
        .input-wrap select {
            width: 100%;
            padding: 11px 12px 11px 38px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 13px;
            color: #333;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            background: #fafafa;
            appearance: none;
            -webkit-appearance: none;
        }

        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color: #2d8f45;
            background: white;
        }

        .input-wrap input::placeholder {
            color: #bbb;
        }

        .input-wrap select {
            cursor: pointer;
            color: #333;
        }

        .input-wrap select option[value=""] {
            color: #bbb;
        }

        /* Flecha del select */
        .select-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #aaa;
            font-size: 12px;
        }

        /* Ojo contraseña */
        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            font-size: 15px;
            padding: 0;
        }

        /* ── Botón principal ── */
        .btn-register {
            width: 100%;
            padding: 13px;
            background: #2d8f45;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }

        .btn-register:hover {
            background: #1a6b2e;
        }

        .btn-register:active {
            transform: scale(0.98);
        }

        /* ── Volver al login ── */
        .login-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #555;
        }

        .login-link a {
            color: #2d8f45;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 16px 0;
        }

        /* ── Nota de seguridad ── */
        .security-note {
            font-size: 11px;
            color: #bbb;
            text-align: center;
        }

        /* ── Responsive ── */
        @media (max-width: 750px) {
            .register-wrapper {
                flex-direction: column;
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
            }

            .register-left {
                width: 100%;
                min-height: 220px;
                justify-content: center;
            }

            .register-right {
                padding: 30px 24px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>

<div class="register-wrapper">

    <!-- Panel izquierdo -->
    <div class="register-left">
        <div class="left-content">

            <div class="sena-logo-left">
                <!-- Ajusta la ruta según tu proyecto -->
                <img src="../assets/img/logo-sena.png" alt="Logo SENA">
            </div>

            <div class="bienvenido">Bienvenido</div>
            <div class="titulo">
                Sistema Inteligente<br>
                de Control<br>
                de <span>Asistencia</span>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">🔒</div>
                    Registro rápido y seguro
                </div>
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    Reportes y estadísticas
                </div>
                <div class="feature">
                    <div class="feature-icon">✅</div>
                    Información confiable en tiempo real
                </div>
            </div>

        </div>

        <svg class="wave" viewBox="0 0 400 80" preserveAspectRatio="none">
            <path d="M0,40 C100,80 300,0 400,40 L400,80 L0,80 Z" fill="rgba(255,255,255,0.08)"/>
        </svg>
    </div>

    <!-- Panel derecho -->
    <div class="register-right">
        <div class="register-card">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="dashboard.php">Inicio</a></li>
                    <li class="active">Registro</li>
                </ol>
            </nav>

            <!-- Logo y título -->
            <div class="logo-top">
                <img src="../assets/img/logo-sena.png" alt="Logo SENA">
            </div>

            <h2>Crear Cuenta</h2>
            <p class="subtitle">Completa los datos para registrarte</p>

            <form action="../controllers/RegisterController.php" method="POST">

                <div class="form-grid">

                    <!-- Documento -->
                    <div class="form-group">
                        <label class="field-label">Documento</label>
                        <div class="input-wrap">
                            <span class="input-icon">🪪</span>
                            <input
                                type="text"
                                name="documento"
                                placeholder="Número de documento"
                                required
                            >
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="form-group">
                        <label class="field-label">Rol</label>
                        <div class="input-wrap">
                            <span class="input-icon">🛡️</span>
                            <select name="rol_id" required>
                                <option value="">Seleccione rol</option>
                                <option value="1">Administrador</option>
                                <option value="2">Instructor</option>
                            </select>
                            <span class="select-arrow">▼</span>
                        </div>
                    </div>

                    <!-- Nombres -->
                    <div class="form-group">
                        <label class="field-label">Nombres</label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input
                                type="text"
                                name="nombres"
                                placeholder="Nombres"
                                required
                            >
                        </div>
                    </div>

                    <!-- Apellidos -->
                    <div class="form-group">
                        <label class="field-label">Apellidos</label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input
                                type="text"
                                name="apellidos"
                                placeholder="Apellidos"
                                required
                            >
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="form-group">
                        <label class="field-label">Correo electrónico</label>
                        <div class="input-wrap">
                            <span class="input-icon">✉️</span>
                            <input
                                type="email"
                                name="correo"
                                placeholder="correo@sena.edu.co"
                                required
                            >
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="form-group">
                        <label class="field-label">Usuario</label>
                        <div class="input-wrap">
                            <span class="input-icon">🔑</span>
                            <input
                                type="text"
                                name="usuario"
                                placeholder="Nombre de usuario"
                                required
                            >
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group full">
                        <label class="field-label">Contraseña</label>
                        <div class="input-wrap">
                            <span class="input-icon">🔒</span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Crea una contraseña segura"
                                required
                            >
                            <button type="button" class="eye-btn" onclick="
                                var p = document.getElementById('password');
                                p.type = p.type === 'password' ? 'text' : 'password';
                            ">👁</button>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn-register">Crear cuenta</button>

            </form>

            <hr class="divider">

            <div class="security-note">
                🔒 Tus datos están protegidos y serán tratados de acuerdo con las políticas de privacidad del SENA.
            </div>

            <div class="login-link">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>