<?php
require_once "conexion.php";

class Core_Modelo{

    static public function administrar_ingreso_agente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT id, rol, cargo FROM $tabla WHERE email = :correo AND contrasena = :contrasena AND estado = 2");
        $consulta->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function administrar_ingreso_cliente_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("SELECT id, cargo FROM $tabla WHERE correo = :correo AND contrasena = :contrasena AND estado = 2");
        $consulta->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $consulta->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function administrar_registro_ingreso_modelo($tabla, $datos){
        $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET ingreso = :ingreso WHERE id = :id");
        $consulta->bindParam(":id", $datos["id_usuario"], PDO::PARAM_INT);
        $consulta->bindParam(":ingreso", $datos["fecha"], PDO::PARAM_STR);
        $consulta->execute();
        if($consulta -> execute()){
		    return "ok";
		}else{
			return "error";
		}
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_agente_modelo($tabla, $perfil){
        $consulta = Conexion::conectar()->prepare("SELECT usuario.nombres,
            usuario.avatar,
            rol.rol
        FROM $tabla, rol
        WHERE usuario.id = :id
        AND usuario.rol = rol.id");
        $consulta->bindParam(":id", $perfil, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }

    static public function perfil_cliente_modelo($tabla, $perfil){
        $consulta = Conexion::conectar()->prepare("SELECT cliente.nombres,
            cliente_area.area AS rol,
            cliente.avatar
        FROM $tabla, cliente_area
        WHERE cliente.id = :id
        AND cliente.area = cliente_area.id");
        //  
        $consulta->bindParam(":id", $perfil, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta-> fetch();
        $consulta->close();
        $consulta = null;
    }
}
