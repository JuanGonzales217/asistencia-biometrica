<?php

require_once __DIR__ . "/../config/conexion.php";

class ProgramaModel {

    public static function listar(){

        $sql = Conexion::conectar()->prepare("
            SELECT
                p.*,
                COUNT(DISTINCT f.id) AS total_fichas,
                COUNT(DISTINCT a.id) AS total_aprendices
            FROM programas p
            LEFT JOIN fichas f
                ON f.programa_id = p.id
            LEFT JOIN aprendices a
                ON a.ficha_id = f.id
            GROUP BY p.id
            ORDER BY p.nombre ASC
        ");

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerPorId($id){

        $sql = Conexion::conectar()->prepare("
            SELECT *
            FROM programas
            WHERE id = :id
        ");

        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    public static function crear($data){

        $sql = Conexion::conectar()->prepare("
            INSERT INTO programas (
                codigo,
                nombre,
                nivel,
                duracion,
                descripcion,
                estado
            ) VALUES (
                :codigo,
                :nombre,
                :nivel,
                :duracion,
                :descripcion,
                'Activo'
            )
        ");

        $sql->bindParam(":codigo", $data["codigo"]);
        $sql->bindParam(":nombre", $data["nombre"]);
        $sql->bindParam(":nivel", $data["nivel"]);
        $sql->bindParam(":duracion", $data["duracion"]);
        $sql->bindParam(":descripcion", $data["descripcion"]);

        return $sql->execute();
    }


    public static function actualizar($data){

        $sql = Conexion::conectar()->prepare("
            UPDATE programas SET
                codigo = :codigo,
                nombre = :nombre,
                nivel = :nivel,
                duracion = :duracion,
                descripcion = :descripcion
            WHERE id = :id
        ");

        $sql->bindParam(":id", $data["id"], PDO::PARAM_INT);
        $sql->bindParam(":codigo", $data["codigo"]);
        $sql->bindParam(":nombre", $data["nombre"]);
        $sql->bindParam(":nivel", $data["nivel"]);
        $sql->bindParam(":duracion", $data["duracion"]);
        $sql->bindParam(":descripcion", $data["descripcion"]);

        return $sql->execute();
    }


    public static function cambiarEstado($id, $estado){

        $sql = Conexion::conectar()->prepare("
            UPDATE programas
            SET estado = :estado
            WHERE id = :id
        ");

        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->bindParam(":estado", $estado);

        return $sql->execute();
    }


    public static function eliminar($id){

        $conexion = Conexion::conectar();

        $verificar = $conexion->prepare("
            SELECT COUNT(*) AS total
            FROM fichas
            WHERE programa_id = :id
        ");

        $verificar->bindParam(":id", $id, PDO::PARAM_INT);
        $verificar->execute();

        $resultado = $verificar->fetch(PDO::FETCH_ASSOC);

        if($resultado["total"] > 0){
            return "TIENE_FICHAS";
        }

        $sql = $conexion->prepare("
            DELETE FROM programas
            WHERE id = :id
        ");

        $sql->bindParam(":id", $id, PDO::PARAM_INT);

        if($sql->execute()){
            return "OK";
        }

        return "ERROR";
    }


    public static function totalProgramas(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM programas
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    public static function totalActivos(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM programas
            WHERE estado = 'Activo'
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerFichasPorPrograma($programa_id){

    $sql = Conexion::conectar()->prepare("
        SELECT 
            f.*,
            COUNT(a.id) AS total_aprendices
        FROM fichas f
        LEFT JOIN aprendices a 
            ON a.ficha_id = f.id
        WHERE f.programa_id = :programa_id
        GROUP BY f.id
        ORDER BY f.numero_ficha ASC
    ");

    $sql->bindParam(":programa_id", $programa_id);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}


public static function totalAprendicesPorPrograma($programa_id){

    $sql = Conexion::conectar()->prepare("
        SELECT COUNT(a.id) AS total
        FROM aprendices a
        INNER JOIN fichas f 
            ON a.ficha_id = f.id
        WHERE f.programa_id = :programa_id
    ");

    $sql->bindParam(":programa_id", $programa_id);
    $sql->execute();

    return $sql->fetch(PDO::FETCH_ASSOC);
}
}