<?php

require_once __DIR__ . "/../config/conexion.php";

class AprendizModel {

    public static function crear($data){

        $sql = Conexion::conectar()->prepare(
            "INSERT INTO aprendices(
                documento,
                nombres,
                apellidos,
                correo,
                telefono,
                ficha_id,
                huella_id
            ) VALUES (
                :documento,
                :nombres,
                :apellidos,
                :correo,
                :telefono,
                :ficha_id,
                :huella_id
            )"
        );

        $sql->bindParam(":documento", $data["documento"]);
        $sql->bindParam(":nombres", $data["nombres"]);
        $sql->bindParam(":apellidos", $data["apellidos"]);
        $sql->bindParam(":correo", $data["correo"]);
        $sql->bindParam(":telefono", $data["telefono"]);
        $sql->bindParam(":ficha_id", $data["ficha_id"]);
        $sql->bindParam(":huella_id", $data["huella_id"]);

        return $sql->execute();
    }

    public static function listar(){

        $sql = Conexion::conectar()->prepare(
            "SELECT a.*, f.numero_ficha
             FROM aprendices a
             LEFT JOIN fichas f ON a.ficha_id = f.id"
        );

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

  public static function eliminar($id){

    $conexion = Conexion::conectar();

    /* Revisar si el aprendiz ya tiene asistencias */
    $verificar = $conexion->prepare("
        SELECT COUNT(*) AS total
        FROM asistencia
        WHERE aprendiz_id = :id
    ");

    $verificar->bindParam(":id", $id);
    $verificar->execute();

    $asistencias = $verificar->fetch(PDO::FETCH_ASSOC);

    /* No se elimina para no perder el historial */
    if($asistencias["total"] > 0){
        return "TIENE_ASISTENCIAS";
    }

    $sql = $conexion->prepare("
        DELETE FROM aprendices
        WHERE id = :id
    ");

    $sql->bindParam(":id", $id);

    if($sql->execute()){
        return "OK";
    }

    return "ERROR";
}
    public static function totalAprendices(){

    

    $sql = Conexion::conectar()->prepare(
        "SELECT COUNT(*) total FROM aprendices"
    );

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);

}

public static function totalActivos(){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(*) total
        FROM aprendices
        WHERE estado='Activo'
    ");

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function totalInactivos(){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(*) total
        FROM aprendices
        WHERE estado='Inactivo'
    ");

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function conHuella(){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(*) total
        FROM aprendices
        WHERE huella_id<>'SIN_HUELLA'
    ");

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function sinHuella(){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(*) total
        FROM aprendices
        WHERE huella_id='SIN_HUELLA'
    ");

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function totalFichas(){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(DISTINCT ficha_id) total
        FROM aprendices
    ");

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}

public static function obtenerPorId($id){

    $sql = Conexion::conectar()->prepare("
        SELECT
            a.*,
            f.numero_ficha
        FROM aprendices a
        LEFT JOIN fichas f
            ON a.ficha_id = f.id
        WHERE a.id = :id
    ");

    $sql->bindParam(":id",$id);

    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}




public static function actualizar($data){

    $sql = Conexion::conectar()->prepare("
        UPDATE aprendices SET
            documento = :documento,
            nombres = :nombres,
            apellidos = :apellidos,
            correo = :correo,
            telefono = :telefono,
            ficha_id = :ficha_id
        WHERE id = :id
    ");

    $sql->bindParam(":id", $data["id"]);
    $sql->bindParam(":documento", $data["documento"]);
    $sql->bindParam(":nombres", $data["nombres"]);
    $sql->bindParam(":apellidos", $data["apellidos"]);
    $sql->bindParam(":correo", $data["correo"]);
    $sql->bindParam(":telefono", $data["telefono"]);
    $sql->bindParam(":ficha_id", $data["ficha_id"]);

    return $sql->execute();
}

public static function cambiarEstado($id, $estado){

    $sql = Conexion::conectar()->prepare("
        UPDATE aprendices
        SET estado = :estado
        WHERE id = :id
    ");

    $sql->bindParam(":id", $id);
    $sql->bindParam(":estado", $estado);

    return $sql->execute();
}
}