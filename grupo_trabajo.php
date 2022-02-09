<?php
require_once "conexion.php";

class Grupo_Trabajo_Modelo{

    static public function lista_grupo_trabajo_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT grupo_trabajo.id, grupo_trabajo.grupo_trabajo, grupo_trabajo_tipo.tipo, estado.estado
            FROM $tabla, grupo_trabajo_tipo, estado
            WHERE grupo_trabajo.estado = 2
            AND grupo_trabajo.tipo = grupo_trabajo_tipo.id
            AND grupo_trabajo.estado = estado.id");
        $consulta->execute();
        return $consulta->fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_listado_grupo_usuario_modelo($tabla, $agente){
        $consulta = Conexion::conectar()->prepare("SELECT grupo_trabajo.id AS id_grupo, grupo_trabajo.grupo_trabajo 
                        FROM $tabla, grupo_trabajo
                        WHERE usuario_grupo_trabajo.usuario = :agente 
                        AND usuario_grupo_trabajo.estado = 2
                        AND usuario_grupo_trabajo.grupo = grupo_trabajo.id");
        $consulta->bindParam(":agente", $agente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_grupo_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT id, grupo_trabajo FROM $tabla WHERE estado = 2");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function lista_usuario_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT $tabla.id,
            $tabla.nombres,
            cargo.cargo,
            $tabla.avatar,
            estado.estado,
            $tabla.ingreso
            FROM $tabla, cargo, estado
            WHERE $tabla.estado != 3
            AND $tabla.cargo = cargo.id
            AND $tabla.estado = estado.id
            ORDER BY $tabla.id ASC");
        $consulta->execute();
        return $consulta->fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_grupo_usuario_modelo($tabla, $usuario, $grupo){
        $consulta = Conexion::conectar()->prepare("SELECT id, grupo, estado
                        FROM $tabla
                        WHERE usuario_grupo_trabajo.usuario = :usuario 
                        AND usuario_grupo_trabajo.grupo = :grupo");
        $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
        $consulta->bindParam(":grupo", $grupo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function registro_agente_grupo_modelo($tabla, $datos, $auditoria_creado) {  
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (usuario, grupo, auditoria_creado, auditoria_usuario) 
        VALUES (:usuario, :grupo, :auditoria_creado, :auditoria_usuario)");
        $consulta->bindParam(":usuario", $datos["agente"], PDO::PARAM_INT);
        $consulta->bindParam(":grupo", $datos["grupo"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_creado", $datos["auditoria_creado"], PDO::PARAM_STR);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        if ($consulta -> execute()) {
            return 'Ok';
        }else {
            return 'Error';
        }
        $consulta -> close();
        $consulta = null;
    }

    static public function actualizar_estado_grupo_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                    estado = :estado,
                                                    auditoria_usuario = :auditoria_usuario
                                                    WHERE grupo = :grupo
                                                    AND usuario = :agente");
        $consulta->bindParam(":agente", $datos["agente"], PDO::PARAM_INT);
        $consulta->bindParam(":grupo", $datos["grupo"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
            return "Ok";
        }else{
            return "Error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function crear_grupo_trabajo_modelo($tabla, $datos) {  
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (grupo_trabajo, tipo, estado, auditoria_creado, auditoria_usuario) 
        VALUES (:grupo_trabajo, :tipo, :estado, :auditoria_creado, :auditoria_usuario)");
        $consulta->bindParam(":grupo_trabajo", $datos["grupo_trabajo"], PDO::PARAM_STR);
        $consulta->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_creado", $datos["auditoria_creado"], PDO::PARAM_STR);
        if ($consulta -> execute()) {
            return 'Ok';
        }else {
            return 'Error';
        }
        $consulta -> close();
        $consulta = null;
    }

    static public function eliminar_grupo_trabajo_modelo($tabla, $datos){
        $estado = 3;
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado,
                auditoria_usuario = :auditoria_usuario
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["eliminar_grupo_trabajo_id"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["eliminar_grupo_trabajo_auditoria_usuario"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function establecer_supervisor_grupo_trabajo_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                supervisor = :supervisor,
                auditoria_usuario = :auditoria_usuario
                WHERE id = :id");
        $consulta->bindParam(":supervisor", $datos["establecer_supervisor_grupo_trabajo_id_supervisor"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["establecer_supervisor_grupo_trabajo_auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":id", $datos["establecer_supervisor_grupo_trabajo_id"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_supervisor_grupo_trabajo_modelo($tabla, $grupo){
        $consulta = Conexion::conectar()->prepare("SELECT grupo_trabajo.supervisor AS id_supervisor, cliente.nombres, grupo_trabajo.tipo
                        FROM $tabla, cliente
                        WHERE grupo_trabajo.id = :grupo 
                        AND grupo_trabajo.supervisor = cliente.id");
        $consulta->bindParam(":grupo", $grupo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_grupo_trabajo_sin_supervisor_modelo($tabla, $grupo){
        $consulta = Conexion::conectar()->prepare("SELECT grupo_trabajo.supervisor AS id_supervisor, grupo_trabajo.tipo
                        FROM $tabla
                        WHERE grupo_trabajo.id = :grupo");
        $consulta->bindParam(":grupo", $grupo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_integrante_grupo_trabajo_modelo($tabla, $grupo){
        $consulta = Conexion::conectar()->prepare("SELECT usuario_grupo_trabajo.usuario AS id_usuario, cliente.nombres, cliente.avatar, cargo.cargo, grupo_trabajo.tipo
                    FROM $tabla, cliente, cargo, grupo_trabajo
                    WHERE usuario_grupo_trabajo.grupo = :grupo 
                    AND cliente.cargo = cargo.id
                    AND grupo_trabajo.id = usuario_grupo_trabajo.grupo
                    AND usuario_grupo_trabajo.estado = 2
                    AND usuario_grupo_trabajo.usuario = cliente.id");
        $consulta->bindParam(":grupo", $grupo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_detalle_grupo_trabajo_modelo($tabla, $grupo){
        $consulta = Conexion::conectar()->prepare("SELECT id, grupo_trabajo, supervisor, tipo
                        FROM $tabla
                        WHERE id = :grupo");
        $consulta->bindParam(":grupo", $grupo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function editar_grupo_trabajo_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                grupo_trabajo = :grupo_trabajo,
                tipo = :tipo,
                auditoria_usuario = :auditoria_usuario
                WHERE id = :id");
        $consulta->bindParam(":grupo_trabajo", $datos["grupo_trabajo"], PDO::PARAM_STR);
        $consulta->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "Ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    
    static public function consultar_nombre_usuario_modelo($tabla, $supervisor){
        $consulta = Conexion::conectar()->prepare("SELECT nombres
                        FROM $tabla
                        WHERE id = :supervisor");
        $consulta->bindParam(":supervisor", $supervisor, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_mi_grupo_trabajo_modelo($tabla, $supervisor){
        $consulta = Conexion::conectar()->prepare("SELECT id, grupo_trabajo
                        FROM $tabla
                        WHERE supervisor = :supervisor
                        AND estado = 2");
        $consulta->bindParam(":supervisor", $supervisor, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_cantidad_casos_creados_modelo($tabla, $cliente){
        $consulta = Conexion::conectar()->prepare("SELECT id, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE cliente = :cliente AND (estado = 2 OR estado = 3)) AS casos_creados, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE cliente = :cliente AND estado = 4) AS casos_resueltos, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE cliente = :cliente AND estado = 5) AS casos_cerrados,  
                nombres, avatar 
                FROM $tabla
                WHERE id = :cliente");
        $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_cantidad_casos_asignados_modelo($tabla, $usuario){
        $consulta = Conexion::conectar()->prepare("SELECT id, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE asignado = :usuario AND (estado = 2 OR estado = 3)) AS casos_asignados, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE asignado = :usuario AND estado = 4) AS casos_resueltos, 
                (SELECT count(pqr_solicitud.id) FROM pqr_solicitud WHERE asignado = :usuario AND estado = 5) AS casos_cerrados,  
                nombres, avatar
                FROM $tabla
                WHERE id = :usuario");
        $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function listado_solicitudes_creadas_activas_grupo_modelo($tabla, $identificador_integrantes){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.auditoria_creado, pqr_solicitud.id, pqr_solicitud.tema AS asunto, cliente.nombres AS solicitante, usuario.nombres AS asignado, pqr_estado.estado, pqr_solicitud.archivo, , pqr_solicitud.fecha_estimada_resuelto
                FROM $tabla, cliente, pqr_estado, usuario
                WHERE pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND pqr_solicitud.cliente IN ($identificador_integrantes)");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function listado_solicitudes_activas_grupo_modelo($tabla, $identificador_integrantes){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.auditoria_creado, pqr_solicitud.id, pqr_solicitud.tema AS asunto, cliente.nombres AS solicitante, usuario.nombres AS asignado, pqr_estado.estado, pqr_solicitud.archivo, pqr_solicitud.fecha_estimada_resuelto
                FROM $tabla, cliente, pqr_estado, usuario
                WHERE pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND (pqr_solicitud.estado = 2 OR pqr_solicitud.estado = 3)
                AND pqr_solicitud.asignado IN ($identificador_integrantes)");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_casos_asignados_agente_modelo($tabla, $agente){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.auditoria_creado, pqr_solicitud.id, pqr_solicitud.tema AS asunto, cliente.nombres AS solicitante, usuario.nombres AS asignado, pqr_estado.estado, pqr_solicitud.archivo
                FROM $tabla, cliente, pqr_estado, usuario
                WHERE pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND asignado = :agente 
                AND (pqr_estado.id = 2 OR pqr_estado.id = 3)");
        $consulta->bindParam(":agente", $agente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_casos_gestionados_agente_modelo($tabla, $agente, $estado){
        $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.auditoria_creado, pqr_solicitud.id, pqr_solicitud.tema AS asunto, cliente.nombres AS solicitante, usuario.nombres AS asignado, pqr_estado.estado, pqr_solicitud.fecha_resuelto, pqr_solicitud.archivo
                FROM $tabla, cliente, pqr_estado, usuario
                WHERE pqr_solicitud.cliente = cliente.id
                AND pqr_solicitud.asignado = usuario.id
                AND pqr_solicitud.estado = pqr_estado.id
                AND asignado = :agente 
                AND pqr_estado.id = $estado");
        $consulta->bindParam(":agente", $agente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }
}