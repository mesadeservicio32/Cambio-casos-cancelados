<?php
require_once "conexion.php";

class Informe_Modelo{
    static public function consulta_casos_cliente($tabla, $fecha_primer_dia_mes, $fecha_actual){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.cliente) AS total, cliente.nombres FROM pqr_solicitud, cliente WHERE pqr_solicitud.cliente = cliente.id AND pqr_solicitud.auditoria_creado BETWEEN :fecha_primer_dia_mes AND :fecha_actual GROUP BY pqr_solicitud.cliente ORDER BY total DESC LIMIT 3");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_actual", $fecha_actual, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_casos_incidencia($tabla, $fecha_primer_dia_mes, $fecha_actual){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.falla) AS total, pqr_incidente.incidente FROM pqr_solicitud, pqr_incidente WHERE pqr_solicitud.falla = pqr_incidente.id AND pqr_solicitud.auditoria_creado BETWEEN :fecha_primer_dia_mes AND :fecha_actual GROUP BY pqr_solicitud.falla ORDER BY total DESC LIMIT 3");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_actual", $fecha_actual, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_casos_asignado($tabla, $fecha_primer_dia_mes, $fecha_actual){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.asignado) AS total, usuario.nombres FROM pqr_solicitud, usuario WHERE pqr_solicitud.asignado = usuario.id AND (pqr_solicitud.estado = 2 OR pqr_solicitud.estado = 3) AND pqr_solicitud.auditoria_creado BETWEEN :fecha_primer_dia_mes AND :fecha_actual GROUP BY pqr_solicitud.asignado ORDER BY total DESC");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_actual", $fecha_actual, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_casos_resuelto($tabla, $fecha_primer_dia_mes, $fecha_actual){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.asignado) AS total, usuario.nombres FROM pqr_solicitud, usuario WHERE pqr_solicitud.asignado = usuario.id AND pqr_solicitud.estado = 4 AND pqr_solicitud.auditoria_creado BETWEEN :fecha_primer_dia_mes AND :fecha_actual GROUP BY pqr_solicitud.asignado ORDER BY total DESC");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_actual", $fecha_actual, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_casos_asignado_panel_inicio($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.asignado) AS total, usuario.nombres FROM pqr_solicitud, usuario WHERE pqr_solicitud.asignado = usuario.id AND (pqr_solicitud.estado = 2 OR pqr_solicitud.estado = 3 OR pqr_solicitud.estado = 6) GROUP BY pqr_solicitud.asignado ORDER BY total DESC");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function generar_grafico_area_caso_reporte_modelo($tabla, $fecha_primer_dia_mes, $fecha_actual){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.area) AS total, cliente_area.area FROM pqr_solicitud, cliente_area WHERE pqr_solicitud.area = cliente_area.id AND  pqr_solicitud.auditoria_creado > :fecha_primer_dia_mes GROUP BY pqr_solicitud.area ORDER BY total DESC LIMIT 3");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        // $consulta->bindParam(":fecha_actual", $fecha_actual, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function generar_grafico_encuesta_satisfaccion_calificacion_modelo($tabla, $fecha_primer_dia_mes, $item){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT($item) AS total, $item AS calificacion FROM $tabla WHERE auditoria_creado > :fecha_primer_dia_mes GROUP BY $item ORDER BY $item DESC");
        $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_exporte_informe_modelo($tabla, $fecha_inicial, $fecha_final){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.reabierto, usuario.nombres AS agente, pqr_prioridad.prioridad, pqr_estado.estado, pqr_categoria.categoria, pqr_solicitud.auditoria_creado AS fecha_creado, pqr_solicitud.auditoria_modificado AS fecha_actualizado, pqr_solicitud.tema AS asunto, pqr_solicitud.mensaje AS descripcion, cliente.nombres AS cliente, cliente_area.area, pqr_subcategoria.subcategoria, pqr_incidente.incidente AS Error_falla, pqr_solicitud.auditoria_modificado,  pqr_solicitud.fecha_estimada_resuelto
                FROM $tabla, cliente, usuario, pqr_prioridad, pqr_estado, pqr_categoria, cliente_area, pqr_subcategoria, pqr_incidente
                WHERE pqr_solicitud.prioridad = pqr_prioridad.id
                AND pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                AND pqr_solicitud.falla = pqr_incidente.id
                AND pqr_solicitud.area = cliente_area.id
                AND pqr_solicitud.categoria = pqr_categoria.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.auditoria_creado BETWEEN :fecha_inicial AND :fecha_final");
        $consulta->bindParam(":fecha_inicial", $fecha_inicial, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_final", $fecha_final, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_exporte_casos_resueltos_informe_modelo($tabla, $fecha_inicial, $fecha_final){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.reabierto, usuario.nombres AS agente, pqr_prioridad.prioridad, pqr_estado.estado, pqr_categoria.categoria, pqr_solicitud.auditoria_creado AS fecha_creado, pqr_solicitud.auditoria_modificado AS fecha_actualizado, pqr_solicitud.tema AS asunto, pqr_solicitud.mensaje AS descripcion, cliente.nombres AS cliente, cliente_area.area, pqr_subcategoria.subcategoria, pqr_incidente.incidente AS Error_falla, pqr_solicitud.fecha_resuelto,  pqr_solicitud.fecha_estimada_resuelto
                FROM $tabla, cliente, usuario, pqr_prioridad, pqr_estado, pqr_categoria, cliente_area, pqr_subcategoria, pqr_incidente
                WHERE pqr_solicitud.prioridad = pqr_prioridad.id
                AND pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                AND pqr_solicitud.falla = pqr_incidente.id
                AND pqr_solicitud.area = cliente_area.id
                AND pqr_solicitud.categoria = pqr_categoria.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.auditoria_creado >= :fecha_inicial
                AND pqr_solicitud.auditoria_creado <= :fecha_final");
        $consulta->bindParam(":fecha_inicial", $fecha_inicial, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_final", $fecha_final, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_exporte_casos_cerrados_informe_modelo($tabla, $fecha_inicial, $fecha_final){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, usuario.nombres AS agente, pqr_prioridad.prioridad, pqr_estado.estado, pqr_categoria.categoria, pqr_solicitud.auditoria_creado AS fecha_creado, pqr_solicitud.auditoria_modificado AS fecha_actualizado, pqr_solicitud.tema AS asunto, pqr_solicitud.mensaje AS descripcion, cliente.nombres AS cliente, cliente_area.area, pqr_subcategoria.subcategoria, pqr_incidente.incidente AS Error_falla, pqr_cerrado.auditoria_creado AS fecha_cierre, pqr_cerrado.calificacion_calidad, pqr_cerrado.calificacion_cumplimiento, pqr_cerrado.observacion, pqr_solicitud.fecha_resuelto,  pqr_solicitud.fecha_estimada_resuelto
                FROM $tabla, cliente, usuario, pqr_prioridad, pqr_estado, pqr_categoria, cliente_area, pqr_subcategoria, pqr_incidente, pqr_cerrado
                WHERE pqr_solicitud.prioridad = pqr_prioridad.id
                AND pqr_solicitud.cliente = cliente.id 
                AND pqr_solicitud.estado = pqr_estado.id 
                AND pqr_solicitud.subcategoria = pqr_subcategoria.id 
                AND pqr_solicitud.falla = pqr_incidente.id 
                AND pqr_solicitud.id = pqr_cerrado.solicitud
                AND pqr_solicitud.area = cliente_area.id 
                AND pqr_solicitud.categoria = pqr_categoria.id 
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.auditoria_creado >= :fecha_inicial
                AND pqr_solicitud.auditoria_creado <= :fecha_final");
        $consulta->bindParam(":fecha_inicial", $fecha_inicial, PDO::PARAM_STR);
        $consulta->bindParam(":fecha_final", $fecha_final, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function registrar_fecha_resuelto_modelo($tabla, $solicitud, $fecha_resuelto){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                    fecha_resuelto = :fecha_resuelto
                                                    WHERE id = :solicitud");
        $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
        $consulta->bindParam(":fecha_resuelto", $fecha_resuelto, PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
            return "Ok";
        }else{
            return "Error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_caso_veces_reabierto_modelo($tabla, $solicitud){
        $consulta = Conexion::conectar()->prepare("SELECT id, fecha_reapertura, fecha_resuelto
                FROM $tabla
                WHERE solicitud = :solicitud");
        $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_fecha_resuelto_modelo($tabla, $solicitud){
        $respuesta = '%El estado fue cambiado a Resuelto%';
        $consulta = Conexion::conectar()->prepare("SELECT respuesta, auditoria_creado
                FROM $tabla 
                WHERE solicitud = :solicitud
                AND respuesta LIKE :respuesta
                ORDER BY auditoria_creado DESC 
                LIMIT 1");
        $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
        $consulta->bindParam(":respuesta", $respuesta, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_ultima_respuesta_agente_modelo($tabla, $solicitud){
        $consulta = Conexion::conectar()->prepare("SELECT respuesta, auditoria_creado 
            FROM $tabla 
            WHERE solicitud = :solicitud 
            AND tipo = 2 
            ORDER BY auditoria_creado DESC 
            LIMIT 1");
        $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }
}