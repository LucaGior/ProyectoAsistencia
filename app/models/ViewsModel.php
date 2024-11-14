<?php
    namespace app\models;


	/*    Modelo obtener vista    */
	class ViewsModel{

		/* Metodo para verificar vista */
		protected function obtenerVistasModelo($vista){

			$listaBlanca=["dashboard","alumnoNew","alumnoList","alumnoActualizar",
			"institutoNew","institutoList","materiaNew","materiaList","asistencia","notas","logOut"];

			if(in_array($vista, $listaBlanca)){
				if(is_file("./app/views/content/".$vista."-view.php")){
					$contenido="./app/views/content/".$vista."-view.php";
				}else{
					$contenido="404";
				}
			}elseif($vista=="login" || $vista=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}
			return $contenido;
		}

	}