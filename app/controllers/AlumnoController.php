<?php

    namespace app\controllers;
    use app\models\MainModel;

	/*     Controlador Alumno      */
    class AlumnoController extends MainModel{
		
		/*  Metodo para registrar alumno */
		public function registrarAlumnoControlador() {
			# Limpiando y almacenando datos
			$nombre = $this->limpiarCadena($_POST['alumno_nombre']);
			$apellido = $this->limpiarCadena($_POST['alumno_apellido']);
			$email = $this->limpiarCadena($_POST['alumno_email']);
			$telefono = $this->limpiarCadena($_POST['alumno_telefono']);
			$fecha_nac = $this->limpiarCadena($_POST['alumno_nac']);
			$dni = $this->limpiarCadena($_POST['alumno_dni']);
			$materia_id = $this->limpiarCadena($_POST['materia_id']);
			
			# Verificando integridad de los datos
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,40}", $nombre)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El nombre no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}			
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,40}", $apellido)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El apellido no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}					
			if ($this->verificarDatos("[0-9]{4,20}", $telefono)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El numero telefonico no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{4,20}", $dni)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El dni no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{1,20}", $materia_id)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El id materia no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}

			# Verificando campos obligatorios
			if ($nombre == "" || $apellido == "" || $fecha_nac == "" || $materia_id == "" || $dni == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No has llenado todos los campos que son obligatorios",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
		
			# Verificar si el alumno ya existe por su DNI
			$consulta_dni = "SELECT id FROM alumnos WHERE dni = '$dni'";
			$verificar_alumno = $this->ejecutarConsulta($consulta_dni);
		
			if ($verificar_alumno->rowCount() == 1) {
				$datos = $verificar_alumno->fetch();
				$id_alumno = $datos['id'];
		
				# Verificar si ya está inscrito en la materia
				$consulta_materia = "SELECT * FROM inscripciones WHERE id_alumno = '$id_alumno' AND id_materia = '$materia_id'";
				$verificar_materia = $this->ejecutarConsulta($consulta_materia);
		
				if ($verificar_materia->rowCount() > 0) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "El alumno ya está inscrito en esa materia",
						"icono" => "error"
					];
					return json_encode($alerta);
				}
		
				# Si no está inscrito en la materia, registrar la inscripción
				$inscripcion_datos_reg = [
					["campo_nombre" => "id_materia", "campo_marcador" => ":id_materia", "campo_valor" => $materia_id],
					["campo_nombre" => "id_alumno", "campo_marcador" => ":id_alumno", "campo_valor" => $id_alumno]
				];
				
				# Ejecucion guardar datos
				$inscribir_alumno = $this->guardarDatos("inscripciones", $inscripcion_datos_reg);
				
				# Verificar si se inscribio el alumno
				if ($inscribir_alumno->rowCount() == 1) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Éxito en registrar",
						"texto" => "El alumno $nombre se inscribió correctamente en la materia.",
						"icono" => "success"
					];
				}
			} else {
				# Registro del nuevo alumno
				$alumno_datos_reg = [
					["campo_nombre" => "nombre", "campo_marcador" => ":nombre", "campo_valor" => $nombre],
					["campo_nombre" => "apellido", "campo_marcador" => ":apellido", "campo_valor" => $apellido],
					["campo_nombre" => "email", "campo_marcador" => ":email", "campo_valor" => $email],
					["campo_nombre" => "telefono", "campo_marcador" => ":telefono", "campo_valor" => $telefono],
					["campo_nombre" => "fecha_nac", "campo_marcador" => ":fecha_nac", "campo_valor" => $fecha_nac],
					["campo_nombre" => "dni", "campo_marcador" => ":dni", "campo_valor" => $dni]
				];
		
				$registrar_alumno = $this->guardarDatos("alumnos", $alumno_datos_reg);
		
				if ($registrar_alumno->rowCount() == 1) {
					# Obtener el id del alumno recién registrado
					$datos = $this->ejecutarConsulta($consulta_dni)->fetch();
					$id_alumno = $datos['id'];
		
					# Insertar la inscripción
					$inscripcion_datos_reg = [
						["campo_nombre" => "id_materia", "campo_marcador" => ":id_materia", "campo_valor" => $materia_id],
						["campo_nombre" => "id_alumno", "campo_marcador" => ":id_alumno", "campo_valor" => $id_alumno]
					];
		
					$inscribir_alumno = $this->guardarDatos("inscripciones", $inscripcion_datos_reg);
		
					if ($inscribir_alumno->rowCount() == 1) {
						$alerta = [
							"tipo" => "simple",
							"titulo" => "Éxito en registrar",
							"texto" => "El alumno $nombre se registró e inscribió correctamente.",
							"icono" => "success"
						];
					}
				}
			}
		
			return json_encode($alerta);
		}

		/* Metodo para actualizar los datos de los alumnos */
		public function actualizarAlumnoControlador($datos) {

			# Limpiando y almacenando datos
			$nombre = $this->limpiarCadena($datos['nombre']);
			$apellido = $this->limpiarCadena($datos['apellido']);
			$email = $this->limpiarCadena($datos['email']);
			$fecha_nac = $this->limpiarCadena($datos['fecha_nac']);
			$dni = $this->limpiarCadena($datos['dni']);
			$alumno_id = $this->limpiarCadena($datos['alumno_id']);
			
			# Verificando integridad de los datos
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El nombre no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}			
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,40}", $apellido)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El apellido no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}					
			if ($this->verificarDatos("[0-9]{4,20}", $dni)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El dni no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}

			# Asignando valores
			$datosActualizar = [
				["campo_nombre" => "nombre","campo_marcador" => ":nombre" ,"campo_valor" => $nombre ],
				["campo_nombre" => "apellido", "campo_marcador" => ":apellido","campo_valor" => $apellido ],
				["campo_nombre" => "fecha_nac", "campo_marcador" => ":fecha_nac","campo_valor" => $fecha_nac ],
				["campo_nombre" => "email", "campo_marcador" => ":email","campo_valor" => $email ],
				["campo_nombre" => "dni", "campo_marcador" => ":dni", "campo_valor" => $dni]
			];
		
			# Definiendo la condicion por id
			$condicion = [
				"condicion_campo" => "id",
				"condicion_marcador" => ":alumno_id",
				"condicion_valor" => $alumno_id				
			];
		
			# Llamo a la funcion del main para actualizar datos
			$result = $this->actualizarDatos("alumnos", $datosActualizar, $condicion);
		
			if ($result) {
				$alerta = [
					"tipo" => "recargar",
					"titulo" => "Actualizacion",
					"texto" => "Se actualizo el alumno " . $nombre . " correctamente",
					"icono" => "success"
				];
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "error",
					"texto" => "No se pudo actualizar el alumno",
					"icono" => "error"
				];
			}			
			return json_encode($alerta);
		}
			
		/*  Metodo para eliminar alumno */
		public function eliminarAlumnoControlador(){
			# Limpio y almaceno cadena
			$id=$this->limpiarCadena($_POST['alumno_id']);

			# Verifico que exista el alumno por id
			$datos=$this->ejecutarConsulta("SELECT * FROM alumnos WHERE id ='$id'");

			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el alumno en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
			}
			# Elimino alumno
			$eliminarAlumno=$this->eliminarRegistro("alumnos","id", $id);

			if ($eliminarAlumno->rowCount()==1) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Exito en eliminar",
					"texto"=>"El alumno se elimino correctamente",
					"icono"=>"success"
				];
				
			} else {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"fallo",
					"texto"=>"No se pudo eliminar el alumno",
					"icono"=>"success"
				];
				
			}
			return json_encode($alerta);
	
		}
		
		/*  Metodo para obtener alumnos por Materia */
		public function obtenerAlumnosPorMateria($materia_id) {
			# Almacenando y limpiando cadena
			$materia_id = $this->limpiarCadena($materia_id);

			# Consulta para obtener los alumnos según la materia seleccionada
			$consulta = "SELECT 
							alumnos.id,
							alumnos.nombre,
							alumnos.apellido,
							alumnos.fecha_nac,
							alumnos.email,
							alumnos.dni
						FROM alumnos
						JOIN inscripciones ON inscripciones.id_alumno = alumnos.id
						WHERE inscripciones.id_materia = '$materia_id'
						ORDER BY alumnos.apellido ASC";
		
			try {
				$alumnos = $this->ejecutarConsulta($consulta);
								
				# Retorno los resultados en formato array
				return $alumnos->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}

		/*  Metodo para obtener alumnos por materia con su asistencia(si la tiene o no) */
		public function obtenerAlumnosPorMateriaAsistencia($materia_id, $fecha) {
			# Almacenando y limpiando cadena
			$materia_id = $this->limpiarCadena($materia_id);
			$fecha = $this->limpiarCadena($fecha);
		
			# Extrae día y mes de la fecha seleccionada
			$fechaDia = date('d', strtotime($fecha));
			$fechaMes = date('m', strtotime($fecha));
			
			# Consulta para obtener alumnos por materia, estado de asistencia y cumpleaños
			$consulta = "SELECT a.id, a.nombre, a.apellido, i.id AS id_inscripcion,
								IFNULL(asistencias.estado, 'ausente') AS estado,
								CASE 
									WHEN DAY(a.fecha_nac) = '$fechaDia' AND MONTH(a.fecha_nac) = '$fechaMes'
									THEN 'si' 
									ELSE 'no' 
								END AS cumple
						 FROM alumnos AS a
						 JOIN inscripciones AS i ON a.id = i.id_alumno
						 LEFT JOIN asistencias ON i.id = asistencias.id_inscripcion 
							 AND asistencias.fecha = '$fecha'
						 WHERE i.id_materia = '$materia_id'";
		
			try {
				# Ejecuto consulta
				$alumnos = $this->ejecutarConsulta($consulta);
				
				# Retornar los resultados en formato array
				return $alumnos->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}
		

		/*  Metodo para obtener alumnos por materia con sus notas(si la tiene o no) */
		public function obtenerAlumnosPorMateriaNotas($materia_id) {
			# Almacenando y limpiando cadena
			$materia_id = $this->limpiarCadena($materia_id);
			# Consulta para obtener alumnos por materia con sus notas
			$consulta = "SELECT 
							inscripciones.id AS id_inscripcion, 
							alumnos.id AS alumno_id,
							alumnos.nombre,
							alumnos.apellido,
							notas.nota1,
							notas.nota2,
							notas.nota3
						FROM inscripciones
						JOIN alumnos ON inscripciones.id_alumno = alumnos.id
						LEFT JOIN notas ON inscripciones.id = notas.id_inscripcion
						WHERE inscripciones.id_materia = '$materia_id';
						";
			
			try {
				# Ejecuto consulta
				$alumnos = $this->ejecutarConsulta($consulta);
				
				# Retornar los resultados en formato array
				return $alumnos->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}

		/*  Metodo para obtener alumnos por materia con sus condiciones */
		public function obtenerAlumnosCondiciones($materia_id) {
			# Almacenando y limpiando cadena
			$materia_id = $this->limpiarCadena($materia_id);
			# Consulta para obtener los alumnos con su promedio de notas, asistencia y condición
			$consulta = "SELECT 
							alumnos.id AS alumno_id,
							alumnos.nombre,
							alumnos.apellido,
							CAST(AVG((IFNULL(notas.nota1, 0) + IFNULL(notas.nota2, 0) + IFNULL(notas.nota3, 0)) / 3) AS DECIMAL(10,2)) AS promedio_notas,
							CAST((COUNT(CASE WHEN asistencias.estado = 'presente' THEN 1 END) / COUNT(asistencias.estado)) * 100 AS DECIMAL(10,2)) AS porcentaje_asistencia,
							CASE 
								WHEN (AVG((IFNULL(notas.nota1, 0) + IFNULL(notas.nota2, 0) + IFNULL(notas.nota3, 0)) / 3) >= (
									SELECT nota_promocion FROM ram WHERE id_institucion = (
										SELECT id_institucion FROM materias WHERE id = '$materia_id')) 
								AND (COUNT(CASE WHEN asistencias.estado = 'presente' THEN 1 END) / COUNT(asistencias.estado)) * 100 >= (
									SELECT asistencia_promocion FROM ram WHERE id_institucion = (
										SELECT id_institucion FROM materias WHERE id = '$materia_id')))
								THEN 'Promocionado'
								
								WHEN (AVG((IFNULL(notas.nota1, 0) + IFNULL(notas.nota2, 0) + IFNULL(notas.nota3, 0)) / 3) >= (
									SELECT nota_regular FROM ram WHERE id_institucion = (
										SELECT id_institucion FROM materias WHERE id = '$materia_id')) 
								AND (COUNT(CASE WHEN asistencias.estado = 'presente' THEN 1 END) / COUNT(asistencias.estado)) * 100 >= (
									SELECT asistencia_regular FROM ram WHERE id_institucion = (
										SELECT id_institucion FROM materias WHERE id = '$materia_id')))
								THEN 'Regular'
								ELSE 'Libre'
							END AS condicion
						FROM alumnos
						JOIN inscripciones ON inscripciones.id_alumno = alumnos.id
						LEFT JOIN notas ON inscripciones.id = notas.id_inscripcion
						LEFT JOIN asistencias ON inscripciones.id = asistencias.id_inscripcion AND asistencias.fecha IS NOT NULL
						WHERE inscripciones.id_materia = '$materia_id'
						GROUP BY alumnos.id
					";
		
			try {
				$stmt = $this->ejecutarConsulta($consulta);
				
				# Retornar los resultados en formato array
				return $stmt->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}					
	}