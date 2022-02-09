<?php
require_once "conexion.php";

class Cliente_Modelo{
    
    static public function nueva_contrasena_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                contrasena = :contrasena,
                auditoria_modificado = :actualizado,
                auditoria_usuario = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["id_cliente"], PDO::PARAM_INT);
        $consulta->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
        $consulta->bindParam(":actualizado", $datos["actualizado"], PDO::PARAM_STR);
        $consulta->bindParam(":modificado", $datos["id_cliente"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_cliente_modelo($tabla, $cliente){
        $consulta = Conexion::conectar()->prepare("SELECT cliente.nombres,
            cliente.correo,
            cliente.documento,
            cliente.telefono,
            cliente.usuario_jd,
            cliente.area AS id_area,
            cliente_area.area,
            cargo.cargo,
            empresa.empresa,
            cliente.avatar,
            cliente.estado AS idEstado,
            estado.estado
            FROM $tabla, cliente_area, estado, cargo, empresa
            WHERE cliente.id = :cliente
            AND cliente.area = cliente_area.id
            AND cliente.estado = estado.id
            AND cliente.empresa = empresa.id
            AND cliente.cargo = cargo.id
            ORDER BY cliente.id ASC");
        $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function total_caso_resuelto_modelo($tabla, $estado){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(id) AS total FROM $tabla WHERE estado = :estado");
        $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_usuario_modelo($tabla, $id_usuario){
        $consulta = Conexion::conectar()->prepare("SELECT nombres, email FROM $tabla WHERE id = :id_usuario");
        $consulta->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_con_excepcion_modelo($tabla, $excepcion){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != :excepcion ORDER BY id ASC");
        $consulta->bindParam(":excepcion", $excepcion, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_excepcion_modelo($tabla, $excepcion){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :excepcion ORDER BY id ASC");
        $consulta->bindParam(":excepcion", $excepcion, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function area_cliente_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != 0 ORDER BY id ASC");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function lista_buscar_cliente_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT cliente.id, cliente.nombres, cliente.apellidos, cliente.telefono, cliente.correo, cliente.auditoria_creado, cliente_area.area, cliente_area.id AS id_area, cliente_estado.estado
        FROM $tabla, cliente_estado, cliente_area
        WHERE cliente.estado = cliente_estado.id
        AND cliente_area.id = cliente.area
        AND cliente.estado != 3");
        $consulta->execute();
        return $consulta->fetchAll();
        $consulta->close();
        $consulta = null;
    }


    static public function actualizar_estado_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["estado_id_cliente"], PDO::PARAM_INT);
        $consulta->bindParam(":modificado", $datos["estado_cliente_modificado"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["estado_cliente"], PDO::PARAM_INT);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_correo_cliente_modelo($tabla, $correo, $cliente){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                correo = :correo
                WHERE id = :id");
        $consulta->bindParam(":correo", $correo, PDO::PARAM_STR);
        $consulta->bindParam(":id", $cliente, PDO::PARAM_INT);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_editar_cliente_modelo($tabla, $cliente){
        $consulta = Conexion::conectar()->prepare("SELECT cliente.area,
            cliente.nombres,
            cliente.apellidos,
            cliente.documento,
            cliente.usuario_jd,
            cliente.telefono,
            cargo.cargo,
            cliente.correo
        FROM $tabla, cargo
        WHERE cliente.id = :id
        AND cliente.cargo = cargo.id");
        $consulta->bindParam(":id", $cliente, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function editar_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                nombres = :nombres,
                vip = :vip,
                apellidos = :apellidos,
                correo = :correo,
                telefono = :telefono,
                area = :area,
                auditoria_usuario = :auditoria_usuario
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["editar_cliente_id"], PDO::PARAM_INT);
        $consulta->bindParam(":nombres", $datos["editar_cliente_nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":vip", $datos["editar_cliente_vip"], PDO::PARAM_STR);
        $consulta->bindParam(":apellidos", $datos["editar_cliente_apellidos"], PDO::PARAM_STR);
        $consulta->bindParam(":correo", $datos["editar_cliente_correo"], PDO::PARAM_STR);
        $consulta->bindParam(":telefono", $datos["editar_cliente_telefono"], PDO::PARAM_INT);
        $consulta->bindParam(":area", $datos["editar_cliente_area"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["editar_cliente_auditoria_usuario"], PDO::PARAM_INT);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function eliminar_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado,
                auditoria_usuario = :auditoria_usuario
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["eliminar_id_cliente"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["eliminar_cliente_estado"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["eliminar_cliente_modificado"], PDO::PARAM_INT);
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_consecutivo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT COUNT(id) AS consecutivo FROM $tabla");
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function nuevo_cliente_modelo($tabla, $datos_base){
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (nombres, apellidos, correo, telefono, auditoria_usuario, auditoria_creado, area, estado, contrasena) VALUES (:nombres, :apellidos, :correo, :telefono, :auditoria_usuario, :auditoria_creado, :area, :estado, :contrasena)");
        $consulta->bindParam(":nombres", $datos_base["nuevo_cliente_nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":apellidos", $datos_base["nuevo_cliente_apellidos"], PDO::PARAM_STR);
        $consulta->bindParam(":correo", $datos_base["nuevo_cliente_correo"], PDO::PARAM_STR);
        $consulta->bindParam(":telefono", $datos_base["nuevo_cliente_telefono"], PDO::PARAM_STR);
        $consulta->bindParam(":auditoria_usuario", $datos_base["nuevo_cliente_auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_creado", $datos_base["nuevo_cliente_auditoria_creado"], PDO::PARAM_STR);
        $consulta->bindParam(":area", $datos_base["nuevo_cliente_area"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos_base["nuevo_cliente_estado"], PDO::PARAM_INT);
        $consulta->bindParam(":contrasena", $datos_base["nuevo_cliente_contrasena"], PDO::PARAM_STR);
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_editar_avatar_modelo($tabla, $datos, $avatarBase){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                avatar = :avatar,
                auditoria_usuario = :usuario
                WHERE id = :usuario");
        $consulta->bindParam(":usuario", $datos["perfil_editar_avatar_auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":avatar", $avatarBase, PDO::PARAM_STR);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_recuperar_contrasena_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT nombres, correo AS email, contrasena_intento, estado FROM $tabla WHERE correo = :email");
        $consulta->bindParam(":email", $datos["formulario_recuperar_contrasena_cliente_correo"], PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_intentos_cliente_contrasena_modelo($tabla, $datosIntentos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET contrasena_fecha = :contrasena_fecha, contrasena_intento = :contrasena_intento, contrasena_llave = :contrasena_llave WHERE correo = :email");
        $consulta->bindParam(":email", $datosIntentos["email"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena_fecha", $datosIntentos["contrasena_fecha"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena_intento", $datosIntentos["contrasena_intento"], PDO::PARAM_INT);
        $consulta->bindParam(":contrasena_llave", $datosIntentos["contrasena_llave"], PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function desactivar_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET estado = 1, auditoria_usuario = 0 WHERE correo = :email");
        $consulta->bindParam(":email", $datos, PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_cambio_contrasena_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT contrasena_fecha, contrasena_intento FROM $tabla WHERE contrasena_llave = :contrasena_llave");
        $consulta->bindParam(":contrasena_llave", $datos, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_contrasena_cliente_modelo($tabla, $datos, $encriptar){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET contrasena = :contrasena, contrasena_intento = 0, auditoria_usuario = 0 WHERE contrasena_llave = :contrasena_llave");
        $consulta->bindParam(":contrasena", $encriptar, PDO::PARAM_STR);
        $consulta->bindParam(":contrasena_llave", $datos["formulario_llave_contrasena"], PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_existencia_cliente_modelo($tabla, $id){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $consulta->bindParam(":id", $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_existencia_cargo_cliente_modelo($tabla, $id){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $consulta->bindParam(":id", $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function crear_cargo_cliente_modelo($tabla, $datos, $cargo) {  
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (id, cargo)
        VALUES (:cargo_id, :cargo)");
        $consulta->bindParam(":cargo_id", $datos["cargo"], PDO::PARAM_INT);
        $consulta->bindParam(":cargo", $cargo, PDO::PARAM_STR);
        if ($consulta -> execute()) {
            return 'Ok';
        }else {
            return 'Error';
        }
        $consulta -> close();
        $consulta = null;
    }

    static public function registar_cliente_modelo($tabla, $datos) {  
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (id, nombres, empresa, cargo, usuario_jd, telefono, documento, area, correo)
        VALUES (:id, :nombres, :empresa, :cargo, :usuario_jd, :telefono, :documento, :area, :correo)");
        $consulta->bindParam(":id", $datos["AN8"], PDO::PARAM_INT);
        $consulta->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":cargo", $datos["cargo"], PDO::PARAM_STR);
        $consulta->bindParam(":usuario_jd", $datos["usuario_jd"], PDO::PARAM_STR);
        $consulta->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $consulta->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
        $consulta->bindParam(":empresa", $datos["empresa"], PDO::PARAM_INT);
        $consulta->bindParam(":area", $datos["area"], PDO::PARAM_INT);
        $consulta->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        if ($consulta -> execute()) {
            return 'Ok';
        }else {
            return 'Error';
        }
        $consulta -> close();
        $consulta = null;
    }

    static public function crear_agente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (id, email, nombres, cargo) 
        VALUES (:AN8, :email, :nombres, :cargo)");
        $consulta->bindParam(":AN8", $datos["AN8"], PDO::PARAM_INT);
        $consulta->bindParam(":email", $datos["correo"], PDO::PARAM_STR);
        $consulta->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":cargo", $datos["cargo"], PDO::PARAM_STR);
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function desactivar_usuario_modelo($tabla, $datos){
        $estado = 1;
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["AN8"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function activar_usuario_modelo($tabla, $datos){
        $estado = 2;
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["AN8"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_data_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                    nombres = :nombres,
                                                    usuario_jd = :usuario_jd,
                                                    telefono = :telefono,
                                                    documento = :documento,
                                                    empresa = :empresa,
                                                    area = :area,
                                                    cargo = :cargo,
                                                    correo = :correo
                                                    WHERE id = :id");
        $consulta->bindParam(":id", $datos["AN8"], PDO::PARAM_INT);
        $consulta->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":cargo", $datos["cargo"], PDO::PARAM_INT);
        $consulta->bindParam(":usuario_jd", $datos["usuario_jd"], PDO::PARAM_STR);
        $consulta->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $consulta->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
        $consulta->bindParam(":empresa", $datos["empresa"], PDO::PARAM_INT);
        $consulta->bindParam(":area", $datos["area"], PDO::PARAM_INT);
        $consulta->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
            return "Ok";
        }else{
            return "Error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_notificacion_cliente_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT id, notificacion FROM $tabla");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function consulta_notificacion_cliente_modelo($tabla, $cliente, $notificacion){
        $consulta = Conexion::conectar()->prepare("SELECT id, notificacion, cliente, estado 
                        FROM $tabla 
                        WHERE cliente = :cliente 
                        AND notificacion = :notificacion");
        $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
        $consulta->bindParam(":notificacion", $notificacion, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function modificar_notificacion_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                    auditoria_usuario = :auditoria_usuario,
                                                    estado = :estado
                                                    WHERE cliente = :cliente
                                                    AND notificacion = :notificacion");
        $consulta->bindParam(":cliente", $datos["cliente"], PDO::PARAM_INT);
        $consulta->bindParam(":notificacion", $datos["notificacion"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
            return "Ok";
        }else{
            return "Error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function registrar_notificacion_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (cliente, notificacion, estado, auditoria_usuario, auditoria_creado) 
        VALUES (:cliente, :notificacion, :estado, :auditoria_usuario, :auditoria_creado)");
        $consulta->bindParam(":cliente", $datos["cliente"], PDO::PARAM_INT);
        $consulta->bindParam(":notificacion", $datos["notificacion"], PDO::PARAM_INT);
        $consulta->bindParam(":estado",  $datos["estado"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":auditoria_creado",  $datos["auditoria_creado"], PDO::PARAM_STR);
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }
}
