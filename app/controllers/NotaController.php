<?php

    namespace app\controllers;
    use app\models\MainModel;

    /*  Controller Nota */
    class NotaController extends MainModel {

        /*  Metodo para registrar nota o actualizar */
        public function registrarNotas($id_inscripcion, $nota1, $nota2, $nota3) {
            # Consulta para verificar si ya existen notas para esta inscripción
            $consultaVerificacion = "SELECT * FROM notas 
                                     WHERE id_inscripcion = '$id_inscripcion'";
            
            try {
                # Verificar si ya existen notas para esta inscripción
                $stmtVerificacion = $this->ejecutarConsulta($consultaVerificacion);
                
                if ($stmtVerificacion->rowCount() > 0) {
                    # Actualiza notas existentes
                    $datosActualizar = [
                        ["campo_nombre" => "nota1", "campo_marcador" => ":nota1", "campo_valor" => $nota1],
                        ["campo_nombre" => "nota2", "campo_marcador" => ":nota2", "campo_valor" => $nota2],
                        ["campo_nombre" => "nota3", "campo_marcador" => ":nota3", "campo_valor" => $nota3],
                    ];
                    $condicion = [
                        "condicion_campo" => "id_inscripcion",
                        "condicion_marcador" => ":id_inscripcion",
                        "condicion_valor" => $id_inscripcion
                    ];
                    
                    # Llama funcion para actualizar del main 
                    $this->actualizarDatos("notas", $datosActualizar, $condicion);
                    return "Notas actualizadas correctamente para el ID de inscripción $id_inscripcion.";
                    
                } else {
                    # Insertar nuevas notas
                    $datosInsertar = [
                        ["campo_nombre" => "id_inscripcion", "campo_marcador" => ":id_inscripcion", "campo_valor" => $id_inscripcion],
                        ["campo_nombre" => "nota1", "campo_marcador" => ":nota1", "campo_valor" => $nota1],
                        ["campo_nombre" => "nota2", "campo_marcador" => ":nota2", "campo_valor" => $nota2],
                        ["campo_nombre" => "nota3", "campo_marcador" => ":nota3", "campo_valor" => $nota3],
                    ];
                    
                    # Ejecuta gurdar nota
                    $this->guardarDatos("notas", $datosInsertar);
                }
                
            } catch (\PDOException $e) {
                return "Error al registrar notas: " . $e->getMessage();
            }
        }
    
        /*  Metodo para iterar las notas de los alumnos*/
        public function registrarNotasControlador() {
            if (isset($_POST['modulo_notas']) && $_POST['modulo_notas'] == 'registrar' && isset($_POST['id_inscripcion'])) {
                $id_inscripcion_list = $_POST['id_inscripcion']; # Array de IDs de inscripción
                $resultados = [];
                
                foreach ($id_inscripcion_list as $id_inscripcion) {
                    # Obtener notas desde el formulario
                    $nota1 = isset($_POST["nota1_$id_inscripcion"]) && $_POST["nota1_$id_inscripcion"] !== '' ? $_POST["nota1_$id_inscripcion"] : null;
                    $nota2 = isset($_POST["nota2_$id_inscripcion"]) && $_POST["nota2_$id_inscripcion"] !== '' ? $_POST["nota2_$id_inscripcion"] : null;
                    $nota3 = isset($_POST["nota3_$id_inscripcion"]) && $_POST["nota3_$id_inscripcion"] !== '' ? $_POST["nota3_$id_inscripcion"] : null;

    
                    # Registrar o actualizar notas de cada alumno
                    $resultados[] = $this->registrarNotas($id_inscripcion, $nota1, $nota2, $nota3);
                }
    
                $alerta = [
                    "tipo" => "recargar",
                    "titulo" => "Registro exitoso",
                    "texto" => "Las notas se han guardado correctamente.",
                    "icono" => "success"
                ];
                
                return json_encode($alerta);
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Error",
                    "texto" => "Parámetros incompletos.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }
    }