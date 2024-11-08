<?php

    namespace app\controllers;
    use app\models\MainModel;


	/* Controlador Materia   */
    class MateriaController extends MainModel{

		/*  Metodo para registrar materia  */
		public function registrarMateriaControlador(){

			# Almacenando y limpiando datos
		    $nombre=$this->limpiarCadena($_POST['materia_nombre']);
		    $id_institucion=$this->limpiarCadena($_POST['instituto_id']);

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
			if ($this->verificarDatos("[0-9]{1,40}", $id_institucion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El id instituto no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			
		    # Verificando campos obligatorios 
		    if($nombre=="" || $id_institucion==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }
		
			# Verificar si la materia ya existe en la institución 
			$consulta_existencia = $this->ejecutarConsulta("SELECT nombre FROM materias 
														WHERE nombre = '$nombre' AND id_institucion = '$id_institucion'");
		
			if ($consulta_existencia->rowCount() > 0) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La materia ya existe en esta institución",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			# Preparando datos para el registro
			$materias_datos_reg=[
				[
					"campo_nombre"=>"nombre",
					"campo_marcador"=>":nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"id_institucion",
					"campo_marcador"=>":id_institucion",
					"campo_valor"=>$id_institucion
				]
				

			];

			# Registro de materia
			$registrar_materias=$this->guardarDatos("materias",$materias_datos_reg);

			# Verifico si se registro la materia 
			if ($registrar_materias->rowCount()==1) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Exito en registrar",
					"texto"=>"La materia ".$nombre." se registro correctamente",
					"icono"=>"success"
				];
				
			} else {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la materia",
					"icono"=>"error"
				];
			}
			
        	return json_encode($alerta);
		}
		/*  Metodo para listar materias  */
		public function listarMateriaControlador() { 
			
			$tabla = "";
		
			# Consulta para obtener las materias y sus institutos
			$consulta_datos = "SELECT Materias.id, Materias.nombre AS nombre_materia,
								Instituciones.nombre AS nombre_institucion
								FROM Materias
								JOIN Instituciones ON Materias.id_institucion = Instituciones.id
								ORDER BY nombre_institucion ASC";
		
			# Ejecucion de la consulta
			$datos = $this->ejecutarConsulta($consulta_datos);    
			$datos = $datos->fetchAll();
		
			# Creacion de la tabla HTML para mostrar los resultados
			$tabla .= '
				<div class="table-container">
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<thead>
							<tr>
								<th class="has-text-centered">#</th>
								<th class="has-text-centered">Materia</th>
								<th class="has-text-centered">Instituto</th>
								<th class="has-text-centered"></th>
							</tr>
						</thead>
						<tbody>
			';
		
			# Comprobacion si hay datos de la consulta y los añadimos a la tabla
			if (!empty($datos)) {
				$contador = 1;
				foreach ($datos as $rows) {
					$tabla .= '
						<tr class="has-text-centered">
							<td>' . $contador . '</td>
							<td>' . $rows['nombre_materia'] . '</td>
							<td>' . $rows['nombre_institucion'] . '</td>
							<td>
								<form class="FormularioAjax" action="' . APP_URL . 'app/ajax/materiaAjax.php" method="POST" autocomplete="off">
									<input type="hidden" name="modulo_materia" value="eliminar">
									<input type="hidden" name="materia_id" value="' . $rows['id'] . '">
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
						<td colspan="4">
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
		
		/*   Metodo para eliminar instituto   */
		public function eliminarMateriaControlador(){
			$id=$this->limpiarCadena($_POST['materia_id']);

			if ($this->verificarDatos("[0-9]{1,40}", $id)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El id materia no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}
			# Hago la consulta para saber si la materia existe 
			$datos=$this->ejecutarConsulta("SELECT * FROM materias WHERE id='$id'");

			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado la materia en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
			} else {
				$datos=$datos->fetch();
			}

			# Ejectuto eliminacion
			$eliminarInstituto=$this->eliminarRegistro("materias","id", $id);

			# Verifico si se elimino la materia
			if ($eliminarInstituto->rowCount()==1) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Exito en eliminar",
					"texto"=>"La materia ".$datos['nombre']." se  elimino correctamente",
					"icono"=>"success"
				];
			} else {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"fallo",
					"texto"=>"No se pudo eliminar la materia, ".$datos['nombre'],
					"icono"=>"success"
				];
			}
			return json_encode($alerta);
		
		}
		
		/* Función que obtendrá las materias de una institución */
		public function obtenerMateriasPorInstitucion($institucionId) {
			# Almacenando datos#
		    $id_institucion=$this->limpiarCadena($institucionId);

			if ($this->verificarDatos("[0-9]{1,40}", $id_institucion)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El id instituto no coincide con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
			}

			# Consulta para obtener materias
			$consulta = "SELECT id, nombre FROM materias WHERE id_institucion = '$id_institucion'";			
			try {
				$materias = $this->ejecutarConsulta($consulta);
						
				# Retornar los resultados en formato array
				return $materias->fetchAll(\PDO::FETCH_ASSOC);
				
			} catch (\PDOException $e) {
				return ['error' => $e->getMessage()];
			}
		}			
	}