<?php
require_once "conexion.php";

class Usuario_Modelo{
    static public function mostrar_excepcion_modelo($tabla, $excepcion) {
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = $excepcion");
        $consulta ->  execute();
        return $consulta -> fetch();
        $consulta -> close();
        $consulta = null;
    }

    static public function mostrar_formulario_con_excepcion_modelo($tabla, $excepcion) {
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != $excepcion");
        $consulta ->  execute();
        return $consulta -> fetchAll();
        $consulta -> close();
        $consulta = null;
    }
    
    static public function formulario_rol_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != 1 ORDER BY id ASC");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function formularioEstadoModelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function lista_usuario_modelo($tabla){
        $consulta = Conexion::conectar()->prepare("SELECT usuario.id,
            usuario.nombres,
            rol.rol,
            usuario.avatar,
            estado.estado,
            usuario.ingreso
            FROM $tabla, rol, estado
            WHERE usuario.rol != 1
            AND usuario.estado != 3
            AND usuario.rol = rol.id
            AND usuario.estado = estado.id
            ORDER BY usuario.id ASC");
        $consulta->execute();
        return $consulta->fetchAll();
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_usuario_modelo($tabla, $id_usuario){
        $consulta = Conexion::conectar()->prepare("SELECT usuario.nombres,
            cliente.documento, 
            usuario.email,
            cliente.usuario_jd,
            cliente.telefono,
            empresa.empresa,
            usuario.rol AS idRol,
            cliente_area.area,
            rol.rol,
            cargo.cargo,
            cargo.id AS cargo_id,
            usuario.avatar,
            usuario.estado AS idEstado,
            estado.estado
            FROM $tabla, rol, estado, cargo, cliente, empresa, cliente_area
            WHERE usuario.id = :id_usuario
            AND cliente.id = :id_usuario
            AND usuario.rol = rol.id
            AND cliente.cargo = cargo.id
            AND cliente.empresa = empresa.id
            AND cliente.area = cliente_area.id
            AND usuario.estado = estado.id
            ORDER BY usuario.id ASC");
        $consulta->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_estado_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["estado_usuario_id"], PDO::PARAM_INT);
        $consulta->bindParam(":modificado", $datos["estado_usuario_modificado"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $datos["estado_usuario"], PDO::PARAM_INT);
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                email = :email,
                nombres = :nombres,
                rol = :rol,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["editar_id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":modificado", $datos["editar_actualizado_por"], PDO::PARAM_INT);
        $consulta->bindParam(":nombres", $datos["editar_nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":email", $datos["editar_email"], PDO::PARAM_STR);
        $consulta->bindParam(":rol", $datos["editar_rol"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function eliminar_usuario_modelo($tabla, $datos){
        $estado = 3;
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                estado = :estado,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["eliminar_id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
        $consulta->bindParam(":modificado", $datos["eliminar_actualizado_por"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function nueva_contrasena_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                contrasena = :contrasena,
                actualizado = :actualizado,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
        $consulta->bindParam(":actualizado", $datos["actualizado"], PDO::PARAM_STR);
        $consulta->bindParam(":modificado", $datos["id_usuario"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_solicitar_contrasena_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT nombres, email, contrasena_intento, estado FROM $tabla WHERE email = :email");
        $consulta->bindParam(":email", $datos["formulario_recuperar_contrasena_correo"] , PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function formulario_intentos_contrasena_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET contrasena_fecha = :contrasena_fecha, contrasena_intento = :contrasena_intento, contrasena_llave = :contrasena_llave WHERE email = :email");
        $consulta->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena_fecha", $datos["contrasena_fecha"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena_intento", $datos["contrasena_intento"], PDO::PARAM_INT);
        $consulta->bindParam(":contrasena_llave", $datos["contrasena_llave"], PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function desactivar_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET estado = 1, modificado = 0 WHERE email = :email");
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

    static public function formulario_cambio_contrasena_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT contrasena_fecha, contrasena_intento FROM $tabla WHERE contrasena_llave = :contrasena_llave");
        $consulta->bindParam(":contrasena_llave", $datos, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function actualizar_contrasena_modelo($tabla, $datos, $encriptar){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET contrasena = :contrasena, contrasena_intento = 0, modificado = 0 WHERE contrasena_llave = :contrasena_llave");
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

    static public function perfil_actualizar_usuario_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                nombres = :nombres,
                email = :email,
                modificado = :modificado
                WHERE id = :id");
        $consulta->bindParam(":id", $datos["perfil_editar_id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":nombres", $datos["perfil_editar_usuario_nombres"], PDO::PARAM_STR);
        $consulta->bindParam(":email", $datos["perfil_editar_usuario_email"], PDO::PARAM_STR);
        $consulta->bindParam(":modificado", $datos["perfil_editar_id_usuario"], PDO::PARAM_INT);
        $consulta->execute();
        if($consulta -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_editar_avatar_usuario_modelo($tabla, $datos, $avatarBase){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                avatar = :avatar,
                modificado = :usuario
                WHERE id = :usuario");
        $consulta->bindParam(":usuario", $datos["perfil_editar_avatar_id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":avatar", $avatarBase, PDO::PARAM_STR);
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function consultar_grupo_usuario_modelo($tabla, $usuario){
        $consulta = Conexion::conectar()->prepare("SELECT grupo_trabajo.grupo_trabajo 
                                FROM $tabla, grupo_trabajo 
                                WHERE usuario_grupo_trabajo.usuario = :usuario 
                                AND usuario_grupo_trabajo.grupo = grupo_trabajo.id");
        $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetchAll();
        $consulta->close();
        $consulta = null;
    }
}
