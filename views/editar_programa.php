<?php

session_start();

if(!isset($_SESSION["nombre"])){
    header("Location: ../index.php");
    exit();
}

require_once "../models/ProgramaModel.php";

if(!isset($_GET["id"]) || empty($_GET["id"])){
    header("Location: programas.php");
    exit();
}

$programa = ProgramaModel::obtenerPorId($_GET["id"]);

if(!$programa){
    header("Location: programas.php?mensaje=no_encontrado");
    exit();
}

require_once "layout/header.php";
?>

<style>
    body{
        background:#f4f6f9;
        font-family:'Segoe UI', Roboto, Arial, sans-serif;
    }

    .form-container{
        max-width:850px;
        margin:35px auto;
        padding:0 20px;
    }

    .form-card{
        background:#fff;
        border-radius:14px;
        overflow:hidden;
        box-shadow:0 3px 15px rgba(0,0,0,.08);
        border:1px solid #e8eee7;
    }

    .form-header{
        padding:22px 26px;
        background:linear-gradient(90deg,#1f6b00,#39A900);
        color:#fff;
        display:flex;
        align-items:center;
        gap:13px;
    }

    .form-header .icon{
        width:44px;
        height:44px;
        border-radius:11px;
        background:rgba(255,255,255,.18);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:19px;
    }

    .form-header h3{
        margin:0;
        font-size:19px;
        font-weight:700;
    }

    .form-header p{
        margin:3px 0 0;
        font-size:12px;
        opacity:.9;
    }

    .form-body{
        padding:28px;
    }

    .form-label{
        font-size:13px;
        font-weight:600;
        color:#303030;
        margin-bottom:7px;
    }

    .form-control,
    .form-select{
        border:1px solid #dce4db;
        border-radius:9px;
        padding:10px 12px;
        font-size:13px;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:#39A900;
        box-shadow:0 0 0 3px rgba(57,169,0,.12);
    }

    .input-icon{
        position:relative;
    }

    .input-icon i{
        position:absolute;
        left:13px;
        top:50%;
        transform:translateY(-50%);
        color:#8b9688;
        z-index:2;
    }

    .input-icon input{
        padding-left:37px;
    }

    .acciones-form{
        display:flex;
        justify-content:flex-end;
        gap:10px;
        padding-top:22px;
        border-top:1px solid #edf0ed;
        margin-top:25px;
    }

    .btn-cancelar,
    .btn-guardar{
        border:none;
        border-radius:9px;
        padding:10px 18px;
        font-size:13px;
        font-weight:600;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }

    .btn-cancelar{
        background:#f1f3f1;
        color:#555;
    }

    .btn-cancelar:hover{
        background:#e5e8e5;
        color:#333;
    }

    .btn-guardar{
        background:#39A900;
        color:#fff;
        cursor:pointer;
    }

    .btn-guardar:hover{
        background:#1f6b00;
    }
</style>

<div class="form-container">

    <div class="mb-3">
        <a href="programas.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
            Volver a programas
        </a>
    </div>

    <div class="form-card">

        <div class="form-header">
            <div class="icon">
                <i class="fas fa-pen-to-square"></i>
            </div>

            <div>
                <h3>Editar programa de formación</h3>
                <p>Actualiza la información académica del programa.</p>
            </div>
        </div>

        <form action="../controllers/ProgramaController.php" method="POST">

            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id" value="<?= $programa["id"] ?>">

            <div class="form-body">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Código del programa</label>

                        <div class="input-icon">
                            <i class="fas fa-hashtag"></i>
                            <input
                                type="text"
                                name="codigo"
                                class="form-control"
                                value="<?= htmlspecialchars($programa["codigo"]) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nombre del programa</label>

                        <div class="input-icon">
                            <i class="fas fa-book"></i>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="<?= htmlspecialchars($programa["nombre"]) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nivel de formación</label>

                        <select name="nivel" class="form-select" required>
                            <option value="Técnico" <?= $programa["nivel"] === "Técnico" ? "selected" : "" ?>>Técnico</option>
                            <option value="Tecnólogo" <?= $programa["nivel"] === "Tecnólogo" ? "selected" : "" ?>>Tecnólogo</option>
                            <option value="Operario" <?= $programa["nivel"] === "Operario" ? "selected" : "" ?>>Operario</option>
                            <option value="Auxiliar" <?= $programa["nivel"] === "Auxiliar" ? "selected" : "" ?>>Auxiliar</option>
                            <option value="Especialización" <?= $programa["nivel"] === "Especialización" ? "selected" : "" ?>>Especialización</option>
                            <option value="Complementaria" <?= $programa["nivel"] === "Complementaria" ? "selected" : "" ?>>Complementaria</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Duración en meses</label>

                        <div class="input-icon">
                            <i class="fas fa-calendar-alt"></i>
                            <input
                                type="number"
                                name="duracion"
                                class="form-control"
                                value="<?= htmlspecialchars($programa["duracion"]) ?>"
                                min="1"
                                max="120"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción</label>

                        <textarea
                            name="descripcion"
                            class="form-control"
                            rows="4"
                            placeholder="Describe brevemente el objetivo o enfoque del programa..."
                        ><?= htmlspecialchars($programa["descripcion"] ?? "") ?></textarea>
                    </div>

                </div>

                <div class="acciones-form">
                    <a href="programas.php" class="btn-cancelar">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i>
                        Guardar cambios
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<?php require_once "layout/footer.php"; ?>