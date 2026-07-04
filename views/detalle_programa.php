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

$id = $_GET["id"];

$programa = ProgramaModel::obtenerPorId($id);

if(!$programa){
    header("Location: programas.php?mensaje=no_encontrado");
    exit();
}

$fichas = ProgramaModel::obtenerFichasPorPrograma($id);
$totalAprendices = ProgramaModel::totalAprendicesPorPrograma($id);

include "layout/header.php";
include "layout/sidebar.php";
?>

<style>
    .detalle-wrap{
        padding:28px;
        background:#f4f6f9;
        min-height:100vh;
    }

    .detalle-top{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:22px;
    }

    .btn-volver{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:#fff;
        border:1px solid #dfe5df;
        color:#4d5949;
        text-decoration:none;
        padding:10px 14px;
        border-radius:9px;
        font-size:13px;
        font-weight:600;
    }

    .btn-volver:hover{
        color:#1f6b00;
        border-color:#39A900;
    }

    .btn-editar-programa{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:#39A900;
        color:#fff;
        text-decoration:none;
        padding:10px 15px;
        border-radius:9px;
        font-size:13px;
        font-weight:600;
    }

    .btn-editar-programa:hover{
        background:#1f6b00;
        color:#fff;
    }

    .programa-hero{
        background:linear-gradient(135deg,#1f6b00,#39A900);
        border-radius:16px;
        padding:26px;
        color:#fff;
        display:flex;
        align-items:center;
        gap:18px;
        margin-bottom:20px;
        box-shadow:0 8px 22px rgba(31,107,0,.18);
    }

    .programa-hero-icon{
        width:64px;
        height:64px;
        border-radius:15px;
        background:rgba(255,255,255,.18);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:28px;
    }

    .programa-hero h2{
        margin:0;
        font-size:23px;
        font-weight:700;
    }

    .programa-hero p{
        margin:5px 0 0;
        opacity:.9;
        font-size:13px;
    }

    .programa-hero .codigo{
        display:inline-block;
        margin-top:9px;
        background:rgba(255,255,255,.18);
        padding:4px 10px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
    }

    .estado-hero{
        margin-left:auto;
        background:rgba(255,255,255,.18);
        padding:9px 13px;
        border-radius:20px;
        font-size:13px;
        font-weight:600;
    }

    .estado-hero i{
        font-size:9px;
        margin-right:5px;
    }

    .resumen-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:16px;
        margin-bottom:20px;
    }

    .resumen-card{
        background:#fff;
        border:1px solid #e8eee7;
        border-radius:13px;
        padding:17px;
        display:flex;
        align-items:center;
        gap:13px;
    }

    .resumen-card .icono{
        width:44px;
        height:44px;
        border-radius:11px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
    }

    .icono.verde{
        background:#eaf7e0;
        color:#39A900;
    }

    .icono.azul{
        background:#e9f3ff;
        color:#2979c9;
    }

    .icono.naranja{
        background:#fff3dd;
        color:#dc9400;
    }

    .icono.morado{
        background:#f2ebff;
        color:#7a4fc4;
    }

    .resumen-card span{
        display:block;
        font-size:12px;
        color:#7c867a;
        margin-bottom:3px;
    }

    .resumen-card strong{
        display:block;
        color:#1a1a1a;
        font-size:21px;
    }

    .panel-detalle{
        background:#fff;
        border:1px solid #e8eee7;
        border-radius:14px;
        padding:20px;
        margin-bottom:20px;
    }

    .panel-detalle h5{
        margin:0 0 18px;
        font-size:15px;
        color:#222;
        display:flex;
        align-items:center;
        gap:8px;
    }

    .panel-detalle h5 i{
        color:#39A900;
    }

    .datos-grid{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:16px;
    }

    .dato-item{
        background:#f8faf8;
        border:1px solid #edf1ed;
        border-radius:10px;
        padding:13px;
    }

    .dato-item span{
        display:block;
        color:#849083;
        font-size:11px;
        margin-bottom:5px;
    }

    .dato-item strong{
        color:#252525;
        font-size:13px;
    }

    .descripcion-programa{
        grid-column:span 3;
    }

    .tabla-responsive{
        overflow-x:auto;
    }

    .tabla-fichas{
        width:100%;
        border-collapse:collapse;
    }

    .tabla-fichas th{
        background:#f7f9f7;
        color:#697467;
        font-size:11px;
        text-transform:uppercase;
        letter-spacing:.3px;
        padding:12px;
        text-align:left;
        border-bottom:1px solid #e8eee7;
    }

    .tabla-fichas td{
        padding:13px 12px;
        font-size:13px;
        color:#444;
        border-bottom:1px solid #f0f2f0;
    }

    .tabla-fichas tr:last-child td{
        border-bottom:none;
    }

    .ficha-numero{
        font-weight:700;
        color:#1f6b00;
    }

    .badge-aprendices{
        background:#eaf7e0;
        color:#1f6b00;
        padding:4px 9px;
        border-radius:15px;
        font-size:12px;
        font-weight:600;
    }

    .sin-fichas{
        text-align:center;
        padding:35px 15px;
        color:#7b8679;
    }

    .sin-fichas i{
        font-size:32px;
        color:#b6c0b4;
        margin-bottom:10px;
    }

    .sin-fichas p{
        margin:0;
        font-size:13px;
    }

    @media(max-width:900px){
        .resumen-grid{
            grid-template-columns:repeat(2,1fr);
        }

        .datos-grid{
            grid-template-columns:repeat(2,1fr);
        }

        .descripcion-programa{
            grid-column:span 2;
        }
    }

    @media(max-width:600px){
        .detalle-wrap{
            padding:16px;
        }

        .detalle-top{
            align-items:flex-start;
            gap:10px;
            flex-direction:column;
        }

        .programa-hero{
            align-items:flex-start;
            flex-wrap:wrap;
        }

        .estado-hero{
            margin-left:0;
        }

        .resumen-grid,
        .datos-grid{
            grid-template-columns:1fr;
        }

        .descripcion-programa{
            grid-column:span 1;
        }
    }

    .btn-nueva-ficha{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:#ffffff;
    color:#1f6b00;
    text-decoration:none;
    padding:10px 15px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
    border:1px solid #39A900;
}

.btn-nueva-ficha:hover{
    background:#eaf7e0;
    color:#1f6b00;
}
</style>

<div class="main-content">

    <div class="detalle-wrap">

      <div class="detalle-top">

    <a href="programas.php" class="btn-volver">
        <i class="fas fa-arrow-left"></i>
        Volver a programas
    </a>

    <div class="d-flex gap-2">

        <a href="crear_ficha.php?programa_id=<?= $programa["id"] ?>" class="btn-nueva-ficha">
            <i class="fas fa-plus"></i>
            Nueva ficha
        </a>

        <a href="editar_programa.php?id=<?= $programa["id"] ?>" class="btn-editar-programa">
            <i class="fas fa-pen"></i>
            Editar programa
        </a>

    </div>
</div>

        <section class="programa-hero">
            <div class="programa-hero-icon">
                <i class="fas fa-book-open"></i>
            </div>

            <div>
                <h2><?= htmlspecialchars($programa["nombre"]) ?></h2>
                <p><?= htmlspecialchars($programa["nivel"]) ?> · Duración: <?= htmlspecialchars($programa["duracion"]) ?> meses</p>
                <span class="codigo">Código: <?= htmlspecialchars($programa["codigo"]) ?></span>
            </div>

            <div class="estado-hero">
                <i class="fas fa-circle"></i>
                <?= htmlspecialchars($programa["estado"]) ?>
            </div>
        </section>

        <section class="resumen-grid">
            <div class="resumen-card">
                <div class="icono azul">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <span>Fichas asociadas</span>
                    <strong><?= count($fichas) ?></strong>
                </div>
            </div>

            <div class="resumen-card">
                <div class="icono verde">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <span>Aprendices vinculados</span>
                    <strong><?= $totalAprendices["total"] ?? 0 ?></strong>
                </div>
            </div>

            <div class="resumen-card">
                <div class="icono naranja">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <span>Duración</span>
                    <strong><?= htmlspecialchars($programa["duracion"]) ?> meses</strong>
                </div>
            </div>

            <div class="resumen-card">
                <div class="icono morado">
                    <i class="fas fa-signal"></i>
                </div>
                <div>
                    <span>Estado actual</span>
                    <strong><?= htmlspecialchars($programa["estado"]) ?></strong>
                </div>
            </div>
        </section>

        <section class="panel-detalle">
            <h5>
                <i class="fas fa-circle-info"></i>
                Información del programa
            </h5>

            <div class="datos-grid">
                <div class="dato-item">
                    <span>Código</span>
                    <strong><?= htmlspecialchars($programa["codigo"]) ?></strong>
                </div>

                <div class="dato-item">
                    <span>Nivel de formación</span>
                    <strong><?= htmlspecialchars($programa["nivel"]) ?></strong>
                </div>

                <div class="dato-item">
                    <span>Duración</span>
                    <strong><?= htmlspecialchars($programa["duracion"]) ?> meses</strong>
                </div>

                <div class="dato-item descripcion-programa">
                    <span>Descripción</span>
                    <strong>
                        <?= !empty($programa["descripcion"])
                            ? htmlspecialchars($programa["descripcion"])
                            : "Este programa no tiene descripción registrada." ?>
                    </strong>
                </div>
            </div>
        </section>

        <section class="panel-detalle">
            <h5>
                <i class="fas fa-layer-group"></i>
                Fichas asociadas al programa
            </h5>

            <?php if(empty($fichas)): ?>

                <div class="sin-fichas">
                    <i class="fas fa-folder-open"></i>
                    <p>Aún no hay fichas asociadas a este programa.</p>
                </div>

            <?php else: ?>

                <div class="tabla-responsive">
                    <table class="tabla-fichas">
                        <thead>
                            <tr>
                                <th>Ficha</th>
                                <th>Jornada</th>
                                <th>Fecha inicio</th>
                                <th>Fecha final</th>
                                <th>Aprendices</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($fichas as $f): ?>
                                <tr>
                                    <td>
                                        <span class="ficha-numero">
                                            <?= htmlspecialchars($f["numero_ficha"]) ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($f["jornada"] ?? "Sin definir") ?></td>

                                    <td><?= !empty($f["fecha_inicio"]) ? date("d/m/Y", strtotime($f["fecha_inicio"])) : "Sin definir" ?></td>

                                    <td><?= !empty($f["fecha_fin"]) ? date("d/m/Y", strtotime($f["fecha_fin"])) : "Sin definir" ?></td>

                                    <td>
                                        <span class="badge-aprendices">
                                            <i class="fas fa-users"></i>
                                            <?= $f["total_aprendices"] ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($f["estado"] ?? "Activo") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </section>

    </div>
</div>

<?php include "layout/footer.php"; ?>