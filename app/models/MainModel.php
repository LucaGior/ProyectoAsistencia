<?php

    namespace app\models;
    use \PDO;
    /* verificacion de existencia archivo server.php */
    if (file_exists(__DIR__."/../../config/server.php")) {
        require_once __DIR__."/../../config/server.php";
    }
    /*  Modelo General   */
    class MainModel {
        private $server=DB_SERVER;
        private $db=DB_NAME;
        private $user=DB_USER;
        private $pass=DB_PASS;

        /*  Metodo conexion base de datos  */
        protected function conectar(){
            $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->db,$this->user,$this->pass);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }

        /*  Metodo para realizar consulta a la base de datos  */
        protected function ejecutarConsulta($consulta){
            $sql=$this->conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        /* Metodo para limpiar texto (para inputs) */
        public function limpiarCadena($cadena){

			$palabras=["<script>","</script>","<script src","<script type=",
            "SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO",
            "DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES"
            ,"<?php","?>","--","^","<",">","==","=",";","::"];

            $cadena=trim($cadena);
            $cadena=stripcslashes($cadena);

            foreach($palabras as $palabra){
                $cadena=str_ireplace($palabra, "", $cadena);
            }
            $cadena=trim($cadena);
            $cadena=stripcslashes($cadena);
            return $cadena;
        }

        /* Metodo para verificar si el texto cumple con las condiciones */
        protected function verificarDatos($filtro,$cadena){
            if (preg_match("/^".$filtro."$/", $cadena)) {
                return false;
            } else {
                return true;
            }
        }
        
        /* Metodo para obtener el ultimo id ingresado en una tabla */
        protected function obtenerUltimoID($tabla) {
            
            $consulta = "SELECT id FROM $tabla ORDER BY id DESC LIMIT 1";
            $resultado = $this->ejecutarConsulta($consulta);
        
            
            if ($resultado->rowCount() > 0) {
                $registro = $resultado->fetch(PDO::FETCH_ASSOC);
                return $registro['id'];
            } else {
                return null; 
            }
        }

        /* Metodo para insertar datos en una tabla */
        protected function guardarDatos($tabla,$datos){

			$query="INSERT INTO $tabla (";

			$C=0;
			foreach ($datos as $clave){
				if($C>=1){ $query.=","; }
				$query.=$clave["campo_nombre"];
				$C++;
			}
			
			$query.=") VALUES(";

			$C=0;
			foreach ($datos as $clave){
				if($C>=1){ $query.=","; }
				$query.=$clave["campo_marcador"];
				$C++;
			}

			$query.=")";
			$sql=$this->conectar()->prepare($query);

			foreach ($datos as $clave){
				$sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
			}

			$sql->execute();

			return $sql;
		}

        /* Metodo para actualizar un registro */
        protected function actualizarDatos($tabla,$datos,$condicion){
            $query="UPDATE $tabla SET ";
            $c=0;
            
            foreach($datos as $clave){
                if($c>=1){
                    $query.=",";
                    }
                $query.=$clave["campo_nombre"]."=".$clave["campo_marcador"];
                $c++;
            }
            $query.=" WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"]; 

            $sql=$this->conectar()->prepare($query);

            foreach($datos as $clave){
                $sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
            }
            $sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);

            $sql->execute();
            return $sql;
        }

        /* Metodo para eliminar un registro  */
        protected function eliminarRegistro($tabla,$campo,$id){
            $sql=$this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
            $sql->bindParam(":id",$id);
            $sql->execute();
            
            return $sql;
        }

    }