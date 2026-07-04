<?php

require_once "../controllers/LoginController.php";

LoginController::login();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control de Asistencia SENA</title>
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
        }

        /* ── Contenedor principal ── */
        .login-wrapper {
            display: flex;
            width: 900px;
            min-height: 560px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* ── Panel izquierdo ── */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #0a3d1a 0%, #1a6b2e 40%, #2d8f45 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            overflow: hidden;
        }

        .login-left::before {
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

        /* Logo izquierdo: se invierte a blanco para verse sobre fondo verde */
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
            font-size: 32px;
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
            font-size: 14px;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
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
        .login-right {
            width: 380px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
        }

        .logo-top {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Logo derecho: se ve normal sobre fondo blanco */
        .logo-top img {
            width: 90px;
        }

        .login-card h2 {
            text-align: center;
            color: #1a1a1a;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-bottom: 28px;
        }

        /* ── Campos ── */
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 18px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            background: #fafafa;
        }

        .input-wrap input:focus {
            border-color: #2d8f45;
            background: white;
        }

        .input-wrap input::placeholder {
            color: #bbb;
        }

        .eye-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            font-size: 16px;
            padding: 0;
        }

        /* ── Recordarme ── */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
            margin-bottom: 22px;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2d8f45;
            cursor: pointer;
        }

        /* ── Botón principal ── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #2d8f45;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: #1a6b2e;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        /* ── Olvidaste contraseña ── */
        .forgot {
            display: block;
            text-align: center;
            color: #2d8f45;
            font-size: 13px;
            font-weight: 600;
            margin-top: 16px;
            text-decoration: none;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 18px 0;
        }

        /* ── Nota de seguridad ── */
        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: #aaa;
            text-align: center;
            justify-content: center;
        }

        /* ── Registro ── */
        .register-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #555;
        }

        .register-link a {
            color: #2d8f45;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .login-wrapper {
                flex-direction: column;
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
            }

            .login-left {
                min-height: 260px;
                justify-content: center;
            }

            .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Panel izquierdo -->
    <div class="login-left">
        <div class="left-content">

            <div class="sena-logo-left">
                <!--
                    Cambia la ruta según donde tengas el logo en tu proyecto.
                    Ejemplos:
                      src="../assets/img/logo-sena.png"
                      src="img/logo-sena.png"
                -->
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
    <div class="login-right">
        <div class="login-card">

            <div class="logo-top">
                <img src="../assets/img/logo-sena.png" alt="Logo SENA">
            </div>

            <h2>Control de Asistencia</h2>
            <p class="subtitle">Inicia sesión para continuar</p>

            <form method="POST">

                <label class="field-label">Usuario</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input
                        type="text"
                        name="usuario"
                        placeholder="ingresa tu usuario"
                        required
                    >
                </div>

                <label class="field-label">Contraseña</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Ingresa tu contraseña"
                        required
                    >
                    <button type="button" class="eye-btn" onclick="
                        var p = document.getElementById('password');
                        p.type = p.type === 'password' ? 'text' : 'password';
                    ">👁</button>
                </div>

                <label class="remember">
                    <input type="checkbox" name="recordarme"> Recordarme
                </label>

                <button type="submit" class="btn-login">Iniciar sesión</button>

                <a href="#" class="forgot">¿Olvidaste tu contraseña?</a>

                <hr class="divider">

                <div class="security-note">
                    🔒 Tus datos están protegidos y serán tratados
                    de acuerdo con las políticas de privacidad del SENA.
                </div>

                <div class="register-link">
                    ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
                </div>

            </form>
        </div>
    </div>

</div>

</body>
</html>