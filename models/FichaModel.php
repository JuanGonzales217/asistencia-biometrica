<?php

require_once __DIR__ . "/../config/conexion.php";

class FichaModel {

    /* Listar todas las fichas con cantidad de aprendices */
    public static function listar(){

        $sql = Conexion::conectar()->prepare("
            SELECT
                f.*,
                COUNT(a.id) AS total_aprendices
            FROM fichas f
            LEFT JOIN aprendices a
                ON a.ficha_id = f.id
            GROUP BY f.id
            ORDER BY f.id DESC
        ");

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    /* Listar solo fichas activas para selects */
    public static function listarActivas(){

        $sql = Conexion::conectar()->prepare("
            SELECT *
            FROM fichas
            WHERE estado = 'Activa'
            ORDER BY numero_ficha ASC
        ");

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    /* Obtener una ficha específica */
    public static function obtenerPorId($id){

        $sql = Conexion::conectar()->prepare("
            SELECT
                f.*,
                COUNT(a.id) AS total_aprendices
            FROM fichas f
            LEFT JOIN aprendices a
                ON a.ficha_id = f.id
            WHERE f.id = :id
            GROUP BY f.id
        ");

        $sql->bindParam(":id", $id);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    /* Crear ficha */
    public static function crear($data){

        $sql = Conexion::conectar()->prepare("
            INSERT INTO fichas(
                numero_ficha,
                programa,
                jornada,
                nivel_formacion,
                fecha_inicio,
                fecha_fin,
                instructor,
                cupo_maximo,
                estado
            ) VALUES (
                :numero_ficha,
                :programa,
                :jornada,
                :nivel_formacion,
                :fecha_inicio,
                :fecha_fin,
                :instructor,
                :cupo_maximo,
                'Activa'
            )
        ");

        $sql->bindParam(":numero_ficha", $data["numero_ficha"]);
        $sql->bindParam(":programa", $data["programa"]);
        $sql->bindParam(":jornada", $data["jornada"]);
        $sql->bindParam(":nivel_formacion", $data["nivel_formacion"]);
        $sql->bindParam(":fecha_inicio", $data["fecha_inicio"]);
        $sql->bindParam(":fecha_fin", $data["fecha_fin"]);
        $sql->bindParam(":instructor", $data["instructor"]);
        $sql->bindParam(":cupo_maximo", $data["cupo_maximo"]);

        return $sql->execute();
    }


    /* Actualizar ficha */
    public static function actualizar($data){

        $sql = Conexion::conectar()->prepare("
            UPDATE fichas SET
                numero_ficha = :numero_ficha,
                programa = :programa,
                jornada = :jornada,
                nivel_formacion = :nivel_formacion,
                fecha_inicio = :fecha_inicio,
                fecha_fin = :fecha_fin,
                instructor = :instructor,
                cupo_maximo = :cupo_maximo
            WHERE id = :id
        ");

        $sql->bindParam(":id", $data["id"]);
        $sql->bindParam(":numero_ficha", $data["numero_ficha"]);
        $sql->bindParam(":programa", $data["programa"]);
        $sql->bindParam(":jornada", $data["jornada"]);
        $sql->bindParam(":nivel_formacion", $data["nivel_formacion"]);
        $sql->bindParam(":fecha_inicio", $data["fecha_inicio"]);
        $sql->bindParam(":fecha_fin", $data["fecha_fin"]);
        $sql->bindParam(":instructor", $data["instructor"]);
        $sql->bindParam(":cupo_maximo", $data["cupo_maximo"]);

        return $sql->execute();
    }


    /* Cambiar estado: Activa, Suspendida o Finalizada */
    public static function cambiarEstado($id, $estado){

        $sql = Conexion::conectar()->prepare("
            UPDATE fichas
            SET estado = :estado
            WHERE id = :id
        ");

        $sql->bindParam(":id", $id);
        $sql->bindParam(":estado", $estado);

        return $sql->execute();
    }


    /* Eliminar solo si no tiene aprendices */
    public static function eliminar($id){

        $conexion = Conexion::conectar();

        $verificar = $conexion->prepare("
            SELECT COUNT(*) AS total
            FROM aprendices
            WHERE ficha_id = :id
        ");

        $verificar->bindParam(":id", $id);
        $verificar->execute();

        $resultado = $verificar->fetch(PDO::FETCH_ASSOC);

        if($resultado["total"] > 0){
            return "TIENE_APRENDICES";
        }

        $sql = $conexion->prepare("
            DELETE FROM fichas
            WHERE id = :id
        ");

        $sql->bindParam(":id", $id);

        if($sql->execute()){
            return "OK";
        }

        return "ERROR";
    }


    /* Aprendices de una ficha para la pantalla detalle */
    public static function aprendicesPorFicha($ficha_id){

        $sql = Conexion::conectar()->prepare("
            SELECT
                id,
                documento,
                nombres,
                apellidos,
                correo,
                telefono,
                huella_id,
                estado
            FROM aprendices
            WHERE ficha_id = :ficha_id
            ORDER BY nombres ASC
        ");

        $sql->bindParam(":ficha_id", $ficha_id);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    /* Tarjetas de resumen */
    public static function totalFichas(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM fichas
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    public static function totalActivas(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM fichas
            WHERE estado = 'Activa'
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    public static function totalSuspendidas(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM fichas
            WHERE estado = 'Suspendida'
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }


    public static function totalAprendicesAsignados(){

        $sql = Conexion::conectar()->prepare("
            SELECT COUNT(*) AS total
            FROM aprendices
            WHERE ficha_id IS NOT NULL
        ");

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }



public static function asistenciasRecientesPorFicha($ficha_id){

    $sql = Conexion::conectar()->prepare("
        SELECT
            a.fecha,
            a.hora,
            a.estado,
            ap.nombres,
            ap.apellidos
        FROM asistencia a
        INNER JOIN aprendices ap
            ON a.aprendiz_id = ap.id
        WHERE ap.ficha_id = :ficha_id
        ORDER BY a.fecha DESC, a.hora DESC
        LIMIT 8
    ");

    $sql->bindParam(":ficha_id", $ficha_id);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_ASSOC);
}


    

}