<?php

    namespace app\controllers;
    use app\models\MainModel;

	/*   Controller Instituto   */
    class InstitutoController extends MainModel{

		/* Metodo para registro Instituto */
		public function registrarInstitutoControlador() {
			# Almacenando datos y limpiar cadenas
			$nombre = $this->limpiarCadena($_POST['instituto_nombre']);
			$direccion = $this->limpiarCadena($_POST['instituto_direccion']);
			$asistencia_regular = $this->limpiarCadena($_POST['asistencia_regular']);		
			$asistencia_promocion = $this->limpiarCadena($_POST['asistencia_promocion']);		
			$nota_regular = $this->limpiarCadena($_POST['nota_regular']);		
			$nota_promocion = $this->limpiarCadena($_POST['nota_promocion']);		
				
			# Verificando integridad de los datos
			if ($this->verificarDatos("[0-9 a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El nombre no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}			
			if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ 0-9]{3,40}", $direccion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La dirección no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{1,2}", $asistencia_regular)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El numero de la asistencia regular no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{1,2}", $asistencia_promocion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El numero de la asistencia para promocionar no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{1,2}", $nota_regular)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El numero de la nota regular no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			if ($this->verificarDatos("[0-9]{1,2}", $nota_promocion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El numero de la nota promocion no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			# Verificando campos obligatorios
			if ($nombre == "" || $direccion == "" || $asistencia_regular == "" || $asistencia_promocion == "" || $nota_regular == "" || $nota_promocion == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No has llenado todos los campos que son obligatorios",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
		
			# Verificando si el instituto ya existe
			$consulta = "SELECT id FROM instituciones WHERE nombre = '$nombre'"; 
			$consulta_instituto = $this->ejecutarConsulta($consulta);
		
			if ($consulta_instituto->rowCount() > 0) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Instituto existente",
					"texto" => "El instituto ya está registrado en el sistema.",
					"icono" => "warning"
				];
				return json_encode($alerta);
			}
		
			# Preparando datos para el registro
			$instituto_datos_reg = [
				[
					"campo_nombre" => "nombre",
					"campo_marcador" => ":nombre",
					"campo_valor" => $nombre
				],
				[
					"campo_nombre" => "direccion",
					"campo_marcador" => ":direccion",
					"campo_valor" => $direccion
				]
			];
			
			# Registro de instituto
			$registrar_instituto = $this->guardarDatos("instituciones", $instituto_datos_reg);
			
			# Verifico si se registro el instituto y le registro los datos ram
			if ($registrar_instituto->rowCount() == 1) {
				
				$instituto_id = $this->obtenerUltimoID("instituciones");

				$ram_datos = [
					[
						"campo_nombre" => "asistencia_regular",
						"campo_marcador" => ":asistencia_regular",
						"campo_valor" => $asistencia_regular
					],
					[
						"campo_nombre" => "asistencia_promocion",
						"campo_marcador" => ":asistencia_promocion",
						"campo_valor" => $asistencia_promocion
					],
					[
						"campo_nombre" => "nota_regular",
						"campo_marcador" => ":nota_regular",
						"campo_valor" => $nota_regular
					],
					[
						"campo_nombre" => "nota_promocion",
						"campo_marcador" => ":nota_promocion",
						"campo_valor" => $nota_promocion
					],
					[
						"campo_nombre" => "id_institucion",
						"campo_marcador" => ":id_institucion",
						"campo_valor" => $instituto_id
					]
				];

				$consulta_ram = $this->guardarDatos("ram", $ram_datos);
				if ($consulta_ram->rowCount() == 1) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Éxito en registrar",
						"texto" => "El instituto " . $nombre . " se registró correctamente",
						"icono" => "success"
					];
				}
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No se pudo registrar el instituto",
					"icono" => "error"
				];
			}
		
			return json_encode($alerta);
		}

		/* Metodo para listar Institutos con Ram */
		public function listarInstitutoControlador() {
			$tabla = "";
		
			# Consulta para obtener los institutos con los datos de la tabla ram
			$consulta_datos = "SELECT i.id AS instituto_id, i.nombre, i.direccion, 
							   r.asistencia_regular, r.asistencia_promocion, 
							   r.nota_regular, r.nota_promocion 
							   FROM instituciones AS i
							   LEFT JOIN ram AS r ON i.id = r.id_institucion
							   ORDER BY i.nombre ASC";
		
			$datos = $this->ejecutarConsulta($consulta_datos);    
			$datos = $datos->fetchAll();
		
			$total = count($datos); // Obtener el total de institutos
		
			$tabla .= '
				<div class="table-container">
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<thead>
							<tr>
								<th class="has-text-centered">#</th>
								<th class="has-text-centered">Instituto</th>
								<th class="has-text-centered">Dirección</th>
								<th class="has-text-centered">Asistencia Regular</th>
								<th class="has-text-centered">Asistencia Promoción</th>
								<th class="has-text-centered">Nota Regular</th>
								<th class="has-text-centered">Nota Promoción</th>
								<th class="has-text-centered">Eliminar</th>
							</tr>
						</thead>
						<tbody>
			';
			# Se van insertando en el html cada instituto con sus datos de la ram
			if ($total >= 1) {
				$contador = 1; # Contador para numerar los institutos
		
				foreach ($datos as $rows) {
					$tabla .= '
						<tr class="has-text-centered">
							<td>' . $contador . '</td>
							<td>' . $rows['nombre'] . '</td>
							<td>' . $rows['direccion'] . '</td>
							<td>' . $rows['asistencia_regular'] . '</td>
							<td>' . $rows['asistencia_promocion'] . '</td>
							<td>' . $rows['nota_regular'] . '</td>
							<td>' . $rows['nota_promocion'] . '</td>
							<td>
								<form class="FormularioAjax" action="'.APP_URL.'app/ajax/institutoAjax.php" method="POST" autocomplete="off">
		
									<input type="hidden" name="modulo_instituto" value="eliminar">
									<input type="hidden" name="instituto_id" value="'.$rows['instituto_id'].'">
		
									<button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
								</form>
							</td>
						</tr>
					';
					$contador++;
				}
			} else {
				$tabla .= '
					<tr class="has-text-centered">
						<td colspan="8">
							No hay registros en el sistema
						</td>
					</tr>
				';
			}
		
			$tabla .= '
						</tbody>
					</table>
				</div>
			';
		
			return $tabla;
		}
				
		/* Metodo para Eliminar Instituto */
		public function eliminarInstitutoControlador(){
			# Almacenando datos y limpiar cadenas
			$id=$this->limpiarCadena($_POST['instituto_id']);

			# Verificando integridad de los datos
			if ($this->verificarDatos("[0-9]{1,40}", $id)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No coincide con el formato solicitado para eliminar",
					"icono" => "error"
				];
				return json_encode($alerta);
			}

			$datos=$this->ejecutarConsulta("SELECT * FROM instituciones WHERE id='$id'");

			# Verificar si esta en la bd
			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el instituto en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			} else {
				$datos=$datos->fetch();
			}
			# Se elimina instituto
			$eliminarInstituto=$this->eliminarRegistro("instituciones","id", $id);

			if ($eliminarInstituto->rowCount()==1) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Exito en eliminar",
					"texto"=>"El instituto ".$datos['nombre']." se elimino correctamente",
					"icono"=>"success"
				];
			} else {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"fallo",
					"texto"=>"No se pudo eliminar el instituto, ".$datos['nombre'],
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		
		}
		
		/*Metodo que devuelve Institutos Para el Select */
		public function selectInstitutoControlador() {
			$consulta_datos="SELECT * from instituciones";
			$datos = $this->ejecutarConsulta($consulta_datos);	
			$datos = $datos->fetchAll();


		    $tabla='';
				foreach ($datos as $rows) {
					$tabla.='
						<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>
					';
				}
			return $tabla;
		}
    }