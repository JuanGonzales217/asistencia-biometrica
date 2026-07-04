<?php

require_once __DIR__ . "/../config/conexion.php";

class AsistenciaModel {

    public static function registrar($aprendiz_id){

    $existe = self::verificarAsistenciaHoy($aprendiz_id);

    if($existe){
        return "EXISTE";
    }

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $estado = ($hora <= "07:00:00")
        ? "Puntual"
        : "Tarde";

    $sql = Conexion::conectar()->prepare(
        "INSERT INTO asistencia(
            aprendiz_id,
            fecha,
            hora,
            estado
        ) VALUES (
            :aprendiz_id,
            :fecha,
            :hora,
            :estado
        )"
    );

    $sql->bindParam(":aprendiz_id",$aprendiz_id);
    $sql->bindParam(":fecha",$fecha);
    $sql->bindParam(":hora",$hora);
    $sql->bindParam(":estado",$estado);

    if($sql->execute()){
        return "OK";
    }

    return "ERROR";
}
    public static function listarHoy(){

        $fecha = date("Y-m-d");

        $sql = Conexion::conectar()->prepare(
            "SELECT
                a.*,
                ap.nombres,
                ap.apellidos
             FROM asistencia a
             INNER JOIN aprendices ap
             ON a.aprendiz_id = ap.id
             WHERE fecha = :fecha
             ORDER BY hora DESC"
        );

        $sql->bindParam(":fecha",$fecha);

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function verificarAsistenciaHoy($aprendiz_id){

    $fecha = date("Y-m-d");

    $sql = Conexion::conectar()->prepare(
        "SELECT id
         FROM asistencia
         WHERE aprendiz_id = :aprendiz_id
         AND fecha = :fecha"
    );

    $sql->bindParam(":aprendiz_id", $aprendiz_id);
    $sql->bindParam(":fecha", $fecha);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function totalAprendices(){

    $sql = Conexion::conectar()->prepare(
        "SELECT COUNT(*) total
         FROM aprendices
         WHERE estado='Activo'"
    );

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function presentesHoy(){

    $fecha = date("Y-m-d");

    $sql = Conexion::conectar()->prepare(
        "SELECT COUNT(*) total
         FROM asistencia
         WHERE fecha=:fecha"
    );

    $sql->bindParam(":fecha",$fecha);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function tardanzasHoy(){

    $fecha = date("Y-m-d");

    $sql = Conexion::conectar()->prepare(
        "SELECT COUNT(*) total
         FROM asistencia
         WHERE fecha=:fecha
         AND estado='Tarde'"
    );

    $sql->bindParam(":fecha",$fecha);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function ausentesHoy(){

    $fecha = date("Y-m-d");

    $sql = Conexion::conectar()->prepare(
        "SELECT COUNT(*) total
         FROM aprendices
         WHERE id NOT IN(
             SELECT aprendiz_id
             FROM asistencia
             WHERE fecha=:fecha
         )
         AND estado='Activo'"
    );

    $sql->bindParam(":fecha",$fecha);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function historial(){

    $sql = Conexion::conectar()->prepare(
        "SELECT
            a.id,
            a.fecha,
            a.hora,
            a.estado,
            ap.documento,
            ap.nombres,
            ap.apellidos
        FROM asistencia a
        INNER JOIN aprendices ap
            ON a.aprendiz_id = ap.id
        ORDER BY a.fecha DESC, a.hora DESC"
    );

    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public static function historialPorFecha($fecha){

    $sql = Conexion::conectar()->prepare(
        "SELECT
            a.*,
            ap.documento,
            ap.nombres,
            ap.apellidos
        FROM asistencia a
        INNER JOIN aprendices ap
            ON a.aprendiz_id = ap.id
        WHERE a.fecha = :fecha
        ORDER BY a.hora DESC"
    );

    $sql->bindParam(":fecha", $fecha);

    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public static function historialPorAprendiz($id){

    $sql = Conexion::conectar()->prepare(
        "SELECT *
         FROM asistencia
         WHERE aprendiz_id = :id
         ORDER BY fecha DESC"
    );

    $sql->bindParam(":id", $id);

    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public static function asistenciaUltimos7Dias(){

    $sql = Conexion::conectar()->prepare(
        "SELECT DATE(fecha) as dia,
                COUNT(*) as total
         FROM asistencia
         WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
         GROUP BY DATE(fecha)
         ORDER BY dia ASC"
    );

    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

public static function actividadTiempoReal()
{

    $sql = Conexion::conectar()->prepare("

        SELECT

            ap.nombres,
            ap.apellidos,
            a.estado,
            a.hora,
            a.fecha

        FROM asistencia a

        INNER JOIN aprendices ap

            ON ap.id = a.aprendiz_id

        ORDER BY a.id DESC

        LIMIT 8

    ");

    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);

}
public static function estadisticasAprendiz($id){

    $conexion = Conexion::conectar();

    // Total asistencias
    $sql = $conexion->prepare("
        SELECT COUNT(*) total
        FROM asistencia
        WHERE aprendiz_id = :id
    ");

    $sql->bindParam(":id",$id);
    $sql->execute();

    $total = $sql->fetch(PDO::FETCH_ASSOC)["total"];

    // Puntuales
    $sql = $conexion->prepare("
        SELECT COUNT(*) total
        FROM asistencia
        WHERE aprendiz_id = :id
        AND estado='Puntual'
    ");

    $sql->bindParam(":id",$id);
    $sql->execute();

    $puntuales = $sql->fetch(PDO::FETCH_ASSOC)["total"];

    // Tardanzas
    $sql = $conexion->prepare("
        SELECT COUNT(*) total
        FROM asistencia
        WHERE aprendiz_id = :id
        AND estado='Tarde'
    ");

    $sql->bindParam(":id",$id);
    $sql->execute();

    $tardes = $sql->fetch(PDO::FETCH_ASSOC)["total"];

    // Última asistencia
    $sql = $conexion->prepare("
        SELECT fecha,hora
        FROM asistencia
        WHERE aprendiz_id = :id
        ORDER BY fecha DESC,hora DESC
        LIMIT 1
    ");

    $sql->bindParam(":id",$id);
    $sql->execute();

    $ultima = $sql->fetch(PDO::FETCH_ASSOC);

    $porcentaje = 0;

if($total>0){

    $porcentaje = round(($puntuales/$total)*100);

}

return [

    "total"=>$total,

    "puntuales"=>$puntuales,

    "tardes"=>$tardes,

    "ultima"=>$ultima,

    "porcentaje"=>$porcentaje

];

}

public static function historialFiltrado($id,$desde,$hasta,$estado){

    $conexion = Conexion::conectar();

    $sql = "
        SELECT *
        FROM asistencia
        WHERE aprendiz_id = :id
    ";

    if($desde!=""){

        $sql.=" AND fecha>=:desde";

    }

    if($hasta!=""){

        $sql.=" AND fecha<=:hasta";

    }

    if($estado!=""){

        $sql.=" AND estado=:estado";

    }

    $sql.=" ORDER BY fecha DESC,hora DESC";

    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(":id",$id);

    if($desde!=""){

        $stmt->bindParam(":desde",$desde);

    }

    if($hasta!=""){

        $stmt->bindParam(":hasta",$hasta);

    }

    if($estado!=""){

        $stmt->bindParam(":estado",$estado);

    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public static function resumenPorAprendiz($aprendiz_id){

    $sql = Conexion::conectar()->prepare("
        SELECT
            COUNT(*) AS total_asistencias,
            SUM(CASE WHEN estado = 'Puntual' THEN 1 ELSE 0 END) AS puntuales,
            SUM(CASE WHEN estado = 'Tarde' THEN 1 ELSE 0 END) AS tardanzas,
            MAX(fecha) AS ultima_fecha
        FROM asistencia
        WHERE aprendiz_id = :aprendiz_id
    ");

    $sql->bindParam(":aprendiz_id", $aprendiz_id);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

}