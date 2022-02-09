<?php
class Ruta_Modelo{

    static public function fecha_modelo(){
        date_default_timezone_set("America/Bogota");
        $fechaServidor = date("Y-m-d H:i:s");
        return  $fechaServidor;
	}

    static public function raiz_modelo(){
        return  "http://mesadeservicio.croydon.com.co/";
	}

    static public function ruta_actual_modelo($enlace){
        if ($enlace == "inicio" ||
            $enlace == "login-cliente" ||
            $enlace == "perfil-usuario" ||
            $enlace == "salir") {
            $modulo = "vista/modulos/core/".$enlace.".php";
        }else if ($enlace == "usuario" ||
            $enlace == "cambio-contrasena" ||
            $enlace == "recuperar-contrasena" ||
            $enlace == "nueva-contrasena" ||
            $enlace == "detalle-perfil-usuario") {
            $modulo = "vista/modulos/usuario/".$enlace.".php";
        }else if ($enlace == "gestion-cliente"  ||  
            $enlace == "cambio-contrasena-cliente" ||  
            $enlace == "nueva-contrasena-cliente" ||
            $enlace == "detalle-perfil-cliente" ||    
            $enlace == "carga-masiva-cliente" ||    
            $enlace == "recuperar-contrasena-cliente") {
            $modulo = "vista/modulos/cliente/".$enlace.".php";
        }else if ($enlace == "crear-solicitud" || 
            $enlace == "listado-solicitud" ||
            $enlace == "gestor-solicitud" ||
            $enlace == "caso-no-resuelto" ||
            $enlace == "caso-cerrado" ||
            $enlace == "caso-no-asignado" ||
            $enlace == "buscador-caso" ||
            $enlace == "buscador-cliente") {
            $modulo = "vista/modulos/pqr/".$enlace.".php";
        }else if ($enlace == "index") {
            $modulo = "vista/modulos/core/login.php";
        }else if ($enlace == "grafica-informe" ||
            $enlace == "informe-exportacion"||
            $enlace == "exportacion-caso-cerrado") {
            $modulo = "vista/modulos/informe/".$enlace.".php";
        }else if ($enlace == "mi-grupo-trabajo" ||
            $enlace == "gestor-grupo-trabajo" ||
            $enlace == "grupo-trabajo") {
            $modulo = "vista/modulos/grupo_trabajo/".$enlace.".php";
        }else {
            $modulo = "vista/modulos/core/login.php";
        }
        return $modulo;
    }
}
