<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de cambiar de estado a una incidencia
	* @version v1.1(0218)
	*/
	
require_once("../db/db.php");
class cambiar_estado_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function cambiar_estado(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$id = $_POST['ID'];
			$estado = $_POST['estado'];
			switch ($estado){
				case 0 : $tipo = 1;
						 //$comentario = "CAMBIO ESTADO: ABIERTA";
					break;
				case 1 : $tipo = 2; 
						 //$comentario = "CAMBIO ESTADO: EN TRAMITE";
					break;
			}
			
			$fecha_peticion = getdate ();
			$fecha_peticion = $fecha_peticion[year]."-".$fecha_peticion[mon]."-".$fecha_peticion[mday]." ".$fecha_peticion[hours].":".$fecha_peticion[minutes].":".$fecha_peticion[seconds];
			if ($nuevo_estado == 2){ //SI CERRAMOS LA INCIDENCIA
				$sql_update = "UPDATE peticiones SET STATE = ".$estado.", DATE_CLOSE = '".$fecha_peticion."', USER_CLOSE = '".$_SESSION['usuario']."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$id.""; //Ponemos Estado al nuevo estado	
				$this->db->query($sql_update);
			}else{
				$sql_update = "UPDATE peticiones SET STATE = ".$estado.", LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = ".$id.""; //Ponemos Estado al nuevo estado	
				$this->db->query($sql_update);
			}
			$sql_insert = "INSERT INTO historial (id_issue, author, tipo, date) values ('".$id."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')";
			$this->db->query($sql_insert);
		}else{
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->error = 99;
			return $this->error;
		}
	}
}
?>