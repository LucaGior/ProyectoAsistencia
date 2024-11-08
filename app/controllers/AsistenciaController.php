<?php

namespace app\controllers;
use app\models\MainModel;

    /*  Controller Asistencia  */
    class AsistenciaController extends MainModel{

        /*  Metodo para registrar asistencia */
        public function registrarAsistencia($id_inscripcion, $fecha, $estado){
            # Limpiando y almacenando datos
                $id_inscripcion = $this->limpiarCadena($id_inscripcion);
                $fecha = $this->limpiarCadena($fecha);
                $estado = $this->limpiarCadena($estado);				
                
            
            # Datos para verificación
            $consultaVerificacion = "SELECT * FROM asistencias 
                                    WHERE id_inscripcion = '$id_inscripcion' AND fecha = '$fecha'";
            
            try {
                # Verifica si ya existe una asistencia en la fecha para esta inscripción
                $verificacion = $this->ejecutarConsulta($consultaVerificacion);
                
                if ($verificacion->rowCount() > 0) {
                    
                    # Actualizar asistencia existente
                    $datosActualizar = [
                        ["campo_nombre" => "estado", "campo_marcador" => ":estado", "campo_valor" => $estado],
                    ];
                    $condicion = [
                        "condicion_campo" => "id_inscripcion",
                        "condicion_marcador" => ":id_inscripcion",
                        "condicion_valor" => $id_inscripcion
                    ];
                    
                    # Llama funcion del main para actualizar
                    $this->actualizarDatos("asistencias", $datosActualizar, $condicion);

                } else {
                    # Sino Inserta nueva asistencia
                    $datosInsertar = [
                        ["campo_nombre" => "id_inscripcion", "campo_marcador" => ":id_inscripcion", "campo_valor" => $id_inscripcion],
                        ["campo_nombre" => "fecha", "campo_marcador" => ":fecha", "campo_valor" => $fecha],
                        ["campo_nombre" => "estado", "campo_marcador" => ":estado", "campo_valor" => $estado],
                    ];
                    
                    $this->guardarDatos("asistencias", $datosInsertar);               
                }           
            } catch (\PDOException $e) {
                return "Error al registrar asistencia: " . $e->getMessage();
            }
        }

        /*  Metodo para iterar cada alumno y llamar a registrarAsistencia */
        public function registrarAsistenciaControlador() {
            # Almaceno
                $materia_id = $_POST['materia_id'];
                $fecha = $_POST['fecha'];
                $asistencia = $_POST['asistencia']; # Array de estados de asistencia por inscripción

                # Bucle para iterar sobre cada registro de asistencia de cada alumno
                foreach ($asistencia as $id_inscripcion => $estado) {
                    # Registrar o actualizar asistencia de cada alumno según el estado enviado
                    $this->registrarAsistencia($id_inscripcion, $fecha, $estado);
                }   
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Registro exitoso",
                    "texto" => "Las asistencias se han guardado correctamente.",
                    "icono" => "success"
                ];   
                return json_encode($alerta);        
        }
    }
