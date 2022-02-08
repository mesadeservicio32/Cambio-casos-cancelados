<?php 
require_once "conexion.php";
    class Pqr_Modelo {
        static public function mostrar_formulario_con_excepcion_modelo($tabla, $excepcion) {
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != $excepcion");
            $consulta ->  execute();
            return $consulta -> fetchAll();
            $consulta -> close();
            $consulta = null;
        }

        static public function mostrar_formulario_con_excepcion_ordenado_modelo($tabla, $excepcion) {
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id != $excepcion AND estado = 2 ORDER BY nombres");
            $consulta ->  execute();
            return $consulta -> fetchAll();
            $consulta -> close();
            $consulta = null;
        }

        static public function listado_solicitudes_mensual_modelo($tabla, $fecha_primer_dia_mes){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.prioridad, pqr_solicitud.tema, pqr_solicitud.auditoria_creado, cliente.nombres AS cliente, pqr_estado.estado
                        FROM pqr_solicitud, cliente, pqr_estado
                        WHERE pqr_solicitud.auditoria_creado > :fecha_primer_dia_mes
                        AND pqr_solicitud.cliente = cliente.id
                        AND pqr_solicitud.estado = pqr_estado.id");
            $consulta->bindParam(":fecha_primer_dia_mes", $fecha_primer_dia_mes, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_casos_usuario_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.asignado) AS total, usuario.nombres, usuario.id, pqr_solicitud.estado FROM pqr_solicitud, usuario WHERE pqr_solicitud.asignado = usuario.id AND (pqr_solicitud.estado = 2 OR pqr_solicitud.estado = 3 OR pqr_solicitud.estado = 6) GROUP BY pqr_solicitud.asignado ORDER BY total DESC");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_casos_activos_usuario_modelo($tabla, $usuario){
            $consulta = Conexion::conectar()->prepare("SELECT COUNT(pqr_solicitud.asignado) AS total FROM $tabla WHERE pqr_solicitud.asignado = :usuario AND pqr_solicitud.estado = 6");
            $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_casos_asignados_usuario_modelo($tabla, $usuario){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.tema, pqr_solicitud.auditoria_creado, cliente.nombres AS cliente 
                            FROM $tabla, cliente    
                            WHERE pqr_solicitud.asignado = :usuario 
                            AND pqr_solicitud.cliente = cliente.id
                            AND pqr_solicitud.estado != 5 AND pqr_solicitud.estado != 4 AND pqr_solicitud.estado != 7
                            ORDER BY pqr_solicitud.auditoria_creado ASC");
            $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_casos_usuario_en_espera_modelo($tabla, $usuario){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.tema, pqr_solicitud.auditoria_creado, cliente.nombres AS cliente 
                            FROM $tabla, cliente    
                            WHERE pqr_solicitud.asignado = :usuario 
                            AND pqr_solicitud.cliente = cliente.id
                            AND pqr_solicitud.estado = 6
                            ORDER BY pqr_solicitud.auditoria_creado ASC");
            $consulta->bindParam(":usuario", $usuario, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }
        
        static public function mostrar_excepcion_modelo($tabla, $excepcion) {
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = $excepcion");
            $consulta ->  execute();
            return $consulta -> fetch();
            $consulta -> close();
            $consulta = null;
        }

        static public function formulario_pqr_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function formulario_pqr_subcategoria_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY subcategoria");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function formulario_pqr_incidente_modelo($tabla, $subcategoria){
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE subcategoria = :subcategoria");
            $consulta->bindParam(":subcategoria", $subcategoria, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_colaborador_modelo($tabla, $rol){
            $consulta = Conexion::conectar()->prepare("SELECT id, nombres FROM $tabla");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }
        // $consulta = Conexion::conectar()->prepare("SELECT id, nombres FROM $tabla WHERE rol = :rol");
        // $consulta->bindParam(":rol", $rol, PDO::PARAM_INT);
        
        static public function consulta_consecutivo_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT COUNT(id) AS consecutivo FROM $tabla");
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_cliente_contratante_modelo($tabla, $id){
            $consulta = Conexion::conectar()->prepare("SELECT nombres, apellidos, id AS id_cliente
                                                            FROM $tabla
                                                            WHERE id = :id");
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);                                            
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_datos_pqr_modelo($tabla, $id){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.reabierto, pqr_solicitud.cliente, pqr_solicitud.tema, pqr_solicitud.asignado, usuario.nombres AS asignado_nombres, cliente.id AS cliente_id, cliente.nombres AS cliente_nombres, cliente.apellidos AS cliente_apellidos, cliente.correo AS cliente_correo, pqr_solicitud.fecha_estimada_resuelto
                                                            FROM $tabla, usuario, cliente
                                                            WHERE pqr_solicitud.id = :id
                                                            AND pqr_solicitud.asignado = usuario.id
                                                            AND pqr_solicitud.cliente = cliente.id");
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);                                            
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_pqr_no_asignado_modelo($tabla, $id){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.cliente, pqr_solicitud.tema, pqr_solicitud.asignado, cliente.nombres AS cliente_nombres, cliente.apellidos AS cliente_apellidos, cliente.correo AS cliente_correo
                                                            FROM $tabla, cliente
                                                            WHERE pqr_solicitud.id = :id
                                                            AND pqr_solicitud.cliente = cliente.id");
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);                                            
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function nueva_solicitud_pqr_modelo($tabla, $datos, $nombre_archivo) {  
            $estado = 1;
            $asignado = 0;
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (id_solicitante, tema, mensaje, archivo, asignado, estado, auditoria_creado, fecha_solicitud, auditoria_usuario) 
            VALUES (:id_solicitante, :tema, :mensaje, :archivo, :asignado, :estado, :auditoria_creado, :fecha_solicitud, :auditoria_usuario)");
            $consulta->bindParam(":id_solicitante", $datos["nueva_solicitud_id_solicitante"], PDO::PARAM_INT);
            $consulta->bindParam(":tema", $datos["nueva_solicitud_asunto"], PDO::PARAM_STR);
            $consulta->bindParam(":mensaje", $datos["nueva_solicitud_mensaje"], PDO::PARAM_STR);
            $consulta->bindParam(":archivo", $nombre_archivo, PDO::PARAM_STR);
            $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
            $consulta->bindParam(":asignado", $asignado, PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_creado", $datos["nueva_solicitud_auditoria_creado"], PDO::PARAM_STR);
            $consulta->bindParam(":fecha_solicitud", $datos["nueva_solicitud_auditoria_creado"], PDO::PARAM_STR);
            $consulta->bindParam(":auditoria_usuario", $datos["nueva_solicitud_id_solicitante"], PDO::PARAM_INT);
            if ($consulta -> execute()) {
                return 'Ok';
            }else {
                return 'Error';
            }
            $consulta -> close();
            $consulta = null;
        }

        static public function fecha_historial_solicitud_modelo($tabla, $id_solicitante){
            $consulta = Conexion::conectar()->prepare("SELECT DISTINCT(fecha_solicitud) 
                                                       FROM $tabla 
                                                       WHERE estado = 2 
                                                       AND id_solicitante = :id_solicitante
                                                       ORDER BY id DESC");
            $consulta->bindParam(":id_solicitante", $id_solicitante, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function lista_mis_solicitudes_modelo($tabla, $auditoria_usuario){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, pqr_solicitud.cliente, pqr_solicitud.archivo, pqr_solicitud.tema, pqr_solicitud.mensaje, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.auditoria_usuario
                                                        FROM $tabla, pqr_estado
                                                        WHERE pqr_solicitud.cliente = $auditoria_usuario
                                                        AND pqr_solicitud.estado != 3
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        ORDER BY pqr_solicitud.id");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_solicitudes_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, cliente.apellidos, pqr_prioridad.prioridad, pqr_categoria.categoria, pqr_subcategoria.subcategoria, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado, pqr_incidente.incidente
                                                        FROM $tabla, cliente, pqr_categoria, pqr_subcategoria, pqr_prioridad, pqr_estado, pqr_incidente
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.falla = pqr_incidente.id 
                                                        AND pqr_solicitud.categoria = pqr_categoria.id
                                                        AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                                                        AND pqr_solicitud.prioridad = pqr_prioridad.id
                                                        AND pqr_solicitud.estado != 4
                                                        AND pqr_solicitud.estado != 5
                                                        AND pqr_solicitud.estado != 7");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_solicitudes_en_espera_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, cliente.apellidos, pqr_prioridad.prioridad, pqr_categoria.categoria, pqr_subcategoria.subcategoria, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado, pqr_incidente.incidente
                                                        FROM $tabla, cliente, pqr_categoria, pqr_subcategoria, pqr_prioridad, pqr_estado, pqr_incidente
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.falla = pqr_incidente.id 
                                                        AND pqr_solicitud.categoria = pqr_categoria.id
                                                        AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                                                        AND pqr_solicitud.prioridad = pqr_prioridad.id
                                                        AND pqr_solicitud.estado = 6");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_solicitudes_resueltas_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, cliente.apellidos, pqr_prioridad.prioridad, pqr_categoria.categoria, pqr_subcategoria.subcategoria, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado, pqr_incidente.incidente
                                                        FROM $tabla, cliente, pqr_categoria, pqr_subcategoria, pqr_prioridad, pqr_estado, pqr_incidente
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.falla = pqr_incidente.id 
                                                        AND pqr_solicitud.categoria = pqr_categoria.id
                                                        AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                                                        AND pqr_solicitud.prioridad = pqr_prioridad.id
                                                        AND pqr_solicitud.estado = 4");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_solicitudes_sin_asignar_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado
                                                        FROM $tabla, cliente, pqr_estado
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.estado = 1");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_solicitudes_cerrado_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado
                                                        FROM $tabla, cliente, pqr_estado
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.estado = 5");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_asignado_modelo($tabla, $asignado){
            $consulta = Conexion::conectar()->prepare("SELECT nombres 
                                                        FROM $tabla
                                                        WHERE id = :asignado");
            $consulta->bindParam(":asignado", $asignado, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_asignado_solicitud_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.asignado AS id_asignado, usuario.nombres AS asignado, cliente_contacto.nombres AS cliente, cliente_contacto.email AS cliente_email
                                                        FROM $tabla, usuario, cliente_contacto
                                                        WHERE pqr_solicitud.asignado = usuario.id
                                                        AND pqr_solicitud.id_solicitante = cliente_contacto.id
                                                        AND pqr_solicitud.id = :solicitud");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function lista_historial_modelo($tabla, $auditoria_usuario, $fecha_solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT id, id_solicitante, tema, mensaje, archivo, estado, auditoria_creado, auditoria_usuario
                                                        FROM $tabla
                                                        WHERE id_solicitante = :auditoria_usuario
                                                        AND fecha_solicitud LIKE :fecha_solicitud
                                                        AND estado != 3
                                                        ORDER BY auditoria_creado DESC");
            $consulta->bindParam(":auditoria_usuario", $auditoria_usuario, PDO::PARAM_INT);
            $consulta->bindParam(":fecha_solicitud", $fecha_solicitud, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_respuesta_solicitud_modelo($tabla, $id_solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT id, respuesta, archivo, auditoria_creado
                                                        FROM $tabla
                                                        WHERE solicitud = $id_solicitud
                                                        ORDER BY auditoria_creado DESC");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function datos_correo_crear_solicitud_modelo($tabla, $asignado, $cliente, $categoria){
            $consulta = Conexion::conectar()->prepare("SELECT categoria.nombre AS categoria, usuario.nombres AS asignado_nombres, usuario.email AS asignado_email, cliente.nombres AS cliente_nombres, cliente.email AS cliente_email 
                                                        FROM $tabla, categoria, cliente 
                                                        WHERE cliente.id = :cliente 
                                                        AND usuario.id = :asignado 
                                                        AND categoria.id_categoria = :categoria");
            $consulta->bindParam(":asignado", $asignado, PDO::PARAM_INT);
            $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
            $consulta->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function fecha_listado_solicitud_asignada_modelo($tabla, $colaborador){
            $consulta = Conexion::conectar()->prepare("SELECT DISTINCT(fecha_solicitud) 
                                                       FROM $tabla 
                                                       WHERE asignado = :colaborador
                                                       AND pqr_solicitud.estado != 5
                                                       AND pqr_solicitud.estado != 4
                                                       AND pqr_solicitud.estado != 7
                                                       ORDER BY id DESC");
            $consulta->bindParam(":colaborador", $colaborador, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function descripcion_listado_solicitud_asignada_modelo($tabla, $dia, $colaborador){
            $dia = $dia.'%';
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id AS identificador,  
                                                        pqr_prioridad.prioridad, 
                                                        pqr_solicitud.informador, 
                                                        pqr_categoria.categoria, 
                                                        pqr_subcategoria.subcategoria, 
                                                        pqr_incidente.incidente AS falla, 
                                                        pqr_estado.estado, 
                                                        pqr_solicitud.tema, 
                                                        pqr_solicitud.mensaje, 
                                                        pqr_solicitud.archivo,
                                                        pqr_solicitud.asignado, 
                                                        pqr_solicitud.auditoria_creado,
                                                        cliente.nombres AS cliente_nombres,
                                                        cliente.apellidos AS cliente_apellidos,
                                                        cliente.telefono AS cliente_telefono,
                                                        cliente.correo AS cliente_correo,
                                                        cliente_area.area AS cliente_area
                                                    FROM $tabla, pqr_prioridad, cliente, pqr_categoria, pqr_subcategoria, pqr_incidente, pqr_estado, cliente_area
                                                    WHERE pqr_solicitud.auditoria_creado LIKE :dia
                                                    AND pqr_solicitud.cliente = cliente.id
                                                    AND cliente.area = cliente_area.id
                                                    AND pqr_solicitud.prioridad = pqr_prioridad.id
                                                    AND pqr_solicitud.categoria = pqr_categoria.id
                                                    AND pqr_solicitud.subcategoria = pqr_subcategoria.id
                                                    AND pqr_solicitud.falla = pqr_incidente.id
                                                    AND pqr_solicitud.estado = pqr_estado.id
                                                    AND pqr_solicitud.estado != 5
                                                    AND pqr_solicitud.estado != 7
                                                    AND pqr_solicitud.estado != 4
                                                    AND pqr_solicitud.asignado = :colaborador
                                                    ORDER BY pqr_solicitud.auditoria_creado DESC");
            $consulta->bindParam(":dia", $dia, PDO::PARAM_STR);
            $consulta->bindParam(":colaborador", $colaborador, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }
    
        static public function fecha_listado_solicitud_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT DISTINCT(fecha_solicitud) FROM $tabla WHERE estado = 2 ORDER BY id DESC");
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }
    
        static public function descripcion_listado_solicitud_modelo($tabla, $dia){
            $dia = $dia.'%';
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id AS identificador,  
                                                        pqr_solicitud.tema, 
                                                        pqr_solicitud.mensaje, 
                                                        pqr_solicitud.archivo,
                                                        pqr_solicitud.asignado, 
                                                        pqr_solicitud.auditoria_creado,
                                                        cliente_contacto.nombres,
                                                        cliente_contacto.telefono,
                                                        cliente_contacto.email,
                                                        cliente_contacto.direccion
                                                    FROM $tabla, cliente_contacto
                                                    WHERE pqr_solicitud.auditoria_creado LIKE :dia
                                                    AND pqr_solicitud.id_solicitante = cliente_contacto.id
                                                    AND pqr_solicitud.estado = 2
                                                    ORDER BY pqr_solicitud.auditoria_creado DESC");
            $consulta->bindParam(":dia", $dia, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function asignar_solicitud_modelo($tabla, $datos){
            $estado = 2;
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        asignado = :colaborador,
                                                        estado = :estado,
                                                        prioridad = :prioridad,
                                                        categoria = :categoria,
                                                        subcategoria = :subcategoria,
                                                        falla = :incidente,
                                                        auditoria_usuario = :auditoria_usuario
                                                        WHERE id = :id_solicitud");
            $consulta->bindParam(":id_solicitud", $datos["id_solicitud"], PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
            $consulta->bindParam(":colaborador", $datos["colaborador"], PDO::PARAM_INT);
            $consulta->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
            $consulta->bindParam(":categoria", $datos["categoria"], PDO::PARAM_INT);
            $consulta->bindParam(":subcategoria", $datos["subcategoria"], PDO::PARAM_INT);
            $consulta->bindParam(":incidente", $datos["incidente"], PDO::PARAM_INT);
            $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function fecha_historial_respuesta_modelo($tabla, $caso){
            $consulta = Conexion::conectar()->prepare("SELECT DISTINCT (fecha) FROM $tabla WHERE solicitud = :caso ORDER BY fecha DESC");
            $consulta->bindParam(":caso", $caso, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_respuesta_modelo($tabla, $solicitud, $fecha){
            $fecha = $fecha.'%';
            $consulta = Conexion::conectar()->prepare("SELECT pqr_respuesta.id AS identificador,
                    pqr_respuesta.respuesta,
                    pqr_respuesta.tipo,
                    pqr_respuesta.archivo,
                    pqr_respuesta.auditoria_creado,
                    pqr_respuesta.auditoria_usuario
                FROM $tabla
                WHERE pqr_respuesta.solicitud = :solicitud
                AND auditoria_creado LIKE :fecha
                ORDER BY auditoria_creado DESC");
            $consulta->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_caso_respuesta_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_respuesta.id AS identificador,  
                    pqr_respuesta.auditoria_usuario AS empleado,
                    pqr_respuesta.respuesta,
                    pqr_respuesta.tipo,
                    pqr_respuesta.archivo,
                    pqr_respuesta.auditoria_creado
                FROM $tabla
                WHERE pqr_respuesta.solicitud = :solicitud
                ORDER BY auditoria_creado DESC");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function respuesta_solicitud_modelo($tabla, $datos_base){
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (solicitud, tipo, respuesta, archivo, fecha, auditoria_creado, auditoria_usuario) 
            VALUES (:solicitud, :tipo, :mensaje, :archivo, :auditoria_creado, :auditoria_creado, :auditoria_usuario)");
            $consulta -> bindParam(":solicitud", $datos_base["solicitud"], PDO::PARAM_INT);
            $consulta -> bindParam(":mensaje", $datos_base["mensaje"], PDO::PARAM_STR);
            $consulta -> bindParam(":auditoria_creado", $datos_base["auditoria_creado"], PDO::PARAM_STR);
            $consulta -> bindParam(":auditoria_usuario", $datos_base["auditoria_usuario"], PDO::PARAM_INT);
            $consulta -> bindParam(":tipo", $datos_base["tipo"], PDO::PARAM_INT);
            $consulta -> bindParam(":archivo", $datos_base["archivo"], PDO::PARAM_STR);
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }
        
        static public function finalizar_solicitud_modelo($tabla, $datos){
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (solicitud, observacion, calificacion_calidad, calificacion_cumplimiento, auditoria_usuario, auditoria_creado) 
            VALUES (:solicitud, :observacion, :calificacion_calidad, :calificacion_cumplimiento, :auditoria_usuario, :auditoria_creado)");
            $consulta -> bindParam(":solicitud", $datos["finalizar_id_solicitud"], PDO::PARAM_INT);
            $consulta -> bindParam(":observacion", $datos["finalizar_observacion"], PDO::PARAM_STR);
            $consulta -> bindParam(":calificacion_calidad", $datos["finalizar_calificacion_calidad"], PDO::PARAM_INT);
            $consulta -> bindParam(":calificacion_cumplimiento", $datos["finalizar_calificacion_cumplimiento"], PDO::PARAM_INT);
            $consulta -> bindParam(":auditoria_usuario", $datos["finalizar_auditoria_usuario"], PDO::PARAM_INT);
            $consulta -> bindParam(":auditoria_creado", $datos["finalizar_auditoria_modificado"], PDO::PARAM_STR);
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function cambiar_estado_solicitud_soporte_modelo($tabla, $datos){
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        estado = :estado,
                                                        auditoria_usuario = :auditoria_usuario
                                                        WHERE id = :id_solicitud");
            $consulta->bindParam(":id_solicitud", $datos["cambiar_estado_solicitud_soporte_id"], PDO::PARAM_INT);
            $consulta->bindParam(":estado", $datos["cambiar_estado_solicitud_soporte_estado"], PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_usuario", $datos["cambiar_estado_solicitud_soporte_auditoria_usuario"], PDO::PARAM_INT);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_reapertura_solicitud_modelo($tabla, $datos){
            $consulta = Conexion::conectar()->prepare(" SELECT reabierto
                                                            FROM $tabla
                                                            WHERE id = :id_solicitud");
            $consulta->bindParam(":id_solicitud", $datos["reabrir_id_solicitud"], PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function reabrir_solicitud_modelo($tabla, $datos, $reabierto){
            $estado = 2;
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        estado = :estado,
                                                        reabierto = :reabierto,
                                                        auditoria_usuario = :auditoria_usuario
                                                        WHERE id = :id_solicitud");
            $consulta->bindParam(":id_solicitud", $datos["reabrir_id_solicitud"], PDO::PARAM_INT);
            $consulta->bindParam(":estado", $estado, PDO::PARAM_INT);
            $consulta->bindParam(":reabierto", $reabierto, PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_usuario", $datos["reabrir_auditoria_usuario"], PDO::PARAM_INT);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function registrar_fecha_reapertura_modelo($tabla, $datos){
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (solicitud, fecha_reapertura, fecha_resuelto, justificacion) 
            VALUES (:solicitud, :fecha_reapertura, :fecha_resuelto, :justificacion)");
            $consulta -> bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
            $consulta -> bindParam(":fecha_reapertura", $datos["fecha_reapertura"], PDO::PARAM_STR);
            $consulta -> bindParam(":fecha_resuelto", $datos["fecha_resuelto"], PDO::PARAM_STR);
            $consulta -> bindParam(":justificacion", $datos["justificacion"], PDO::PARAM_STR);
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }
    
        static public function datos_correo_solicitud_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare(" SELECT usuario.email, usuario.nombres, pqr_solicitud.tema
                                                            FROM $tabla, usuario
                                                            WHERE pqr_solicitud.id = :id_solicitud
                                                            AND usuario.id = pqr_solicitud.id_solicitante");
            $consulta->bindParam(":id_solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function crear_tabla_modelo($nombre_tabla) {  
            $crear = Conexion::conectar()->prepare("CREATE TABLE $nombre_tabla(id INT PRIMARY KEY, nombre VARCHAR(45), auditoria_usuario DATE, auditoria_creado TIMESTAMP)");
            $crear->bindParam(":nombre_tabla", $nombre_tabla, PDO::PARAM_INT);
            $crear -> execute();
            echo $crear;
            if ($crear -> execute()) {
                return 'Ok';
            }
            else {
                return 'Error';
            }
            $crear -> close();
            $crear = null;
        }

        static public function actualizar_tabla_modelo($nombre_tabla) {  
            $crear = Conexion::conectar()->prepare("ALTER TABLE $nombre_tabla ADD Email varchar(255)");
            $crear -> execute();
            $crear = "Ok";
            if ($crear == "Ok") {
                return 'Ok';
            }
            else {
                return 'Error';
            }
            $crear -> close();
            $crear = null;
        }

        static public function borrar_tabla_modelo($nombre_tabla) {  
            $crear = Conexion::conectar()->prepare("DROP TABLE $nombre_tabla");
            $crear->bindParam(":nombre_tabla", $nombre_tabla, PDO::PARAM_INT);
            $crear -> execute();
            echo $crear;
            if ($crear -> execute()) {
                return 'Ok';
            }
            else {
                return 'Error';
            }
            $crear -> close();
            $crear = null;
        }

        static public function consultar_identificador_mayor_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT max(id) AS id
                                                        FROM $tabla");
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_correo_modelo($tabla, $id){
            $consulta = Conexion::conectar()->prepare("SELECT email AS correo, nombres
                                                        FROM $tabla
                                                        WHERE id = :id");
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);                                            
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_correo_cliente_modelo($tabla, $id){
            $consulta = Conexion::conectar()->prepare("SELECT correo, nombres
                                                        FROM $tabla
                                                        WHERE id = :id");
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);                                            
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function listado_cliente_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT cliente.id, cliente.nombres, cliente.apellidos, cliente.telefono, cliente.correo, cliente.auditoria_creado, cliente_area.area, cliente_estado.estado
            FROM $tabla, cliente_estado, cliente_area
            WHERE cliente.estado = cliente_estado.id
            AND cliente_area.id = cliente.area");
            // AND cliente.estado != 3
            $consulta->execute();
            return $consulta->fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_contacto_modelo($tabla, $cliente){
            $consulta = Conexion::conectar()->prepare("SELECT id, nombres, telefono, email, direccion FROM $tabla WHERE cliente = :cliente");
            $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_cliente_modelo($tabla, $cliente){
            $consulta = Conexion::conectar()->prepare("SELECT cliente.id, cliente.nombres, cargo.cargo, cliente.telefono, cliente.correo, cliente.area, cliente.vip 
                                    FROM $tabla, cargo
                                    WHERE cliente.id = :cliente
                                    AND cliente.cargo = cargo.id");
            $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_casos_por_calificar_cliente_modelo($tabla, $cliente){
            $consulta = Conexion::conectar()->prepare("SELECT id
                                    FROM $tabla
                                    WHERE cliente = :cliente
                                    AND estado = 4
                                    AND id > 11500");
            $consulta->bindParam(":cliente", $cliente, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_detalle_solicitud_modelo($tabla, $caso){
            $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :caso");
            $consulta->bindParam(":caso", $caso, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function crear_nueva_solicitud_modelo($tabla, $datos) {  
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (informador, prioridad, categoria, subcategoria, falla, cliente, tema, mensaje, archivo, asignado, area, estado, auditoria_creado, fecha_solicitud, auditoria_usuario) 
            VALUES (:informador, :prioridad, :categoria, :subcategoria, :incidente, :solicitante, :tema, :mensaje, :archivo, :asignado, :area, :estado, :auditoria_creado, :fecha_solicitud, :auditoria_usuario)");
            $consulta->bindParam(":solicitante", $datos["crear_solicitud_id_solicitante"], PDO::PARAM_INT);
            $consulta->bindParam(":prioridad", $datos["crear_solicitud_prioridad"], PDO::PARAM_INT);
            $consulta->bindParam(":categoria", $datos["crear_solicitud_categoria"], PDO::PARAM_INT);
            $consulta->bindParam(":subcategoria", $datos["crear_solicitud_subcategoria"], PDO::PARAM_INT);
            $consulta->bindParam(":incidente", $datos["crear_solicitud_incidente"], PDO::PARAM_INT);
            $consulta->bindParam(":tema", $datos["crear_solicitud_asunto"], PDO::PARAM_STR);
            $consulta->bindParam(":mensaje", $datos["crear_solicitud_mensaje"], PDO::PARAM_STR);
            $consulta->bindParam(":archivo", $datos["crear_solicitud_archivo"], PDO::PARAM_STR);
            $consulta->bindParam(":asignado", $datos["crear_solicitud_id_asignado"], PDO::PARAM_INT);
            $consulta->bindParam(":area", $datos["crear_solicitud_area"], PDO::PARAM_INT);
            $consulta->bindParam(":estado", $datos["crear_solicitud_estado"], PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_creado", $datos["crear_solicitud_auditoria_creado"], PDO::PARAM_STR);
            $consulta->bindParam(":fecha_solicitud", $datos["crear_solicitud_auditoria_creado"], PDO::PARAM_STR);
            $consulta->bindParam(":auditoria_usuario", $datos["crear_solicitud_auditoria_usuario"], PDO::PARAM_STR);
            $consulta->bindParam(":informador", $datos["crear_solicitud_auditoria_usuario"], PDO::PARAM_STR);
            if ($consulta -> execute()) {
                return 'Ok';
            }else {
                return 'Error';
            }
            $consulta -> close();
            $consulta = null;
        }

        static public function consulta_maximo_id_existente_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT max(id) AS maximo_identificador_existente FROM $tabla");
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_calificacion_modelo($tabla, $caso){
            $consulta = Conexion::conectar()->prepare("SELECT observacion, calificacion_calidad, calificacion_cumplimiento, auditoria_creado AS fecha
                                                        FROM $tabla
                                                        WHERE solicitud = :caso
                                                        ORDER BY auditoria_creado DESC");
            $consulta->bindParam(":caso", $caso, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_opcion_calificacion_modelo($tabla){
            $consulta = Conexion::conectar()->prepare("SELECT id, color, icono
                                                        FROM $tabla");
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

        static public function consulta_identificador_notificacion_cliente_modelo($tabla, $nombre_notificacion){
            $consulta = Conexion::conectar()->prepare("SELECT id 
                            FROM $tabla 
                            WHERE notificacion = :notificacion");
            $consulta->bindParam(":nombre_notificacion", $nombre_notificacion, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function crear_solicitud_registro_archivo_adicional_modelo($tabla, $archivo, $solicitud){
            $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla (solicitud, archivo) 
            VALUES (:solicitud, :archivo)");
            $consulta -> bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta -> bindParam(":archivo", $archivo, PDO::PARAM_STR);
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_archivo_adjunto_solicitud_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud_archivo.archivo 
                            FROM $tabla
                            WHERE pqr_solicitud_archivo.solicitud = :solicitud");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_archivo_principal_adjunto_solicitud_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT archivo AS archivo_principal
                            FROM $tabla
                            WHERE pqr_solicitud.id = :solicitud");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
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

        static public function registrar_fecha_resuelto_caso_reabierto_modelo($tabla, $solicitud, $fecha_resuelto){
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        fecha_resuelto = :fecha_resuelto
                                                        WHERE solicitud = :solicitud
                                                        AND fecha_resuelto IS NULL");
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

        static public function modificar_solicitud_modelo($tabla, $datos){
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        categoria = :categoria,
                                                        auditoria_usuario = :auditoria_usuario
                                                        WHERE id = :solicitud");
            $consulta->bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
            $consulta->bindParam(":categoria", $datos["categoria"], PDO::PARAM_INT);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function modificar_fecha_estimada_solicitud_modelo($tabla, $datos, $fecha_estimado){
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        fecha_estimada_resuelto = :fecha_estimada_resuelto,
                                                        auditoria_usuario = :auditoria_usuario
                                                        WHERE id = :solicitud");
            $consulta->bindParam(":solicitud", $datos["solicitud"], PDO::PARAM_INT);
            $consulta->bindParam(":auditoria_usuario", $datos["auditoria_usuario"], PDO::PARAM_INT);
            $consulta->bindParam(":fecha_estimada_resuelto", $fecha_estimado, PDO::PARAM_STR);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function motor_busqueda_modelo($tabla, $datos){
            $estado = '%'.$datos["estado"].'%';
            $id = '%'.$datos["solicitud"].'%';
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, pqr_solicitud.tema, pqr_solicitud.archivo, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado
                                                        FROM $tabla, cliente, pqr_estado
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.estado LIKE :estado
                                                        AND pqr_solicitud.id LIKE :id
                                                        AND pqr_solicitud.auditoria_creado 
                                                        BETWEEN :fecha_inicial AND :fecha_final");
            $consulta->bindParam(":fecha_inicial", $datos["fecha_inicial"], PDO::PARAM_STR);
            $consulta->bindParam(":fecha_final", $datos["fecha_final"], PDO::PARAM_STR);
            $consulta->bindParam(":estado", $estado, PDO::PARAM_STR);                                                        
            $consulta->bindParam(":id", $id, PDO::PARAM_STR);                                                        
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function motor_busqueda_completo_modelo($tabla, $datos){
            $id = '%'.$datos["solicitud"].'%';
            $consulta = Conexion::conectar()->prepare("SELECT pqr_solicitud.id, cliente.nombres, pqr_solicitud.archivo, pqr_solicitud.tema, pqr_estado.estado, pqr_solicitud.auditoria_creado, pqr_solicitud.asignado
                                                        FROM $tabla, cliente, pqr_estado
                                                        WHERE pqr_solicitud.cliente = cliente.id
                                                        AND pqr_solicitud.estado = pqr_estado.id
                                                        AND pqr_solicitud.id LIKE :id
                                                        AND pqr_solicitud.auditoria_creado 
                                                        BETWEEN :fecha_inicial AND :fecha_final");
            $consulta->bindParam(":fecha_inicial", $datos["fecha_inicial"], PDO::PARAM_STR);
            $consulta->bindParam(":fecha_final", $datos["fecha_final"], PDO::PARAM_STR);
            $consulta->bindParam(":id", $id, PDO::PARAM_STR);                                                        
            $consulta->execute();
            return $consulta-> fetchAll();
            $consulta->close();
            $consulta = null;
        }

        static public function consulta_cantidad_respuesta_agente_solicitud_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT COUNT(id) AS contador
                                                        FROM $tabla
                                                        WHERE solicitud = :solicitud
                                                        AND tipo = 2");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

        static public function registrar_fecha_estimada_resuelto_modelo($tabla, $fecha_estimado, $solicitud){
            $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET
                                                        fecha_estimada_resuelto = :fecha_estimada_resuelto
                                                        WHERE id = :solicitud");
            $consulta->bindParam(":fecha_estimada_resuelto", $fecha_estimado, PDO::PARAM_STR);
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            if($consulta -> execute()){
                return "Ok";
            }else{
                return "Error";
            }
            $consulta->close();
            $consulta = null;
        }

        static public function consultar_fecha_estimada_resuelto_modelo($tabla, $solicitud){
            $consulta = Conexion::conectar()->prepare("SELECT fecha_resuelto
                                                        FROM $tabla
                                                        WHERE id = :solicitud");
            $consulta->bindParam(":solicitud", $solicitud, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta-> fetch();
            $consulta->close();
            $consulta = null;
        }

    }