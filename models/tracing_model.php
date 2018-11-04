<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de activar el especial seguimiento de una incidencia
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/

require_once("../db/db.php");
class tracing_model{
    private $db;
    private $model;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function activar_tracing(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$id = "";
			$tracing = "";
			
			if ( isset ( $_POST['tracing'] ) ){
				$tracing = $_POST['tracing'];
			}
			
			if ( isset ( $_POST['ID'] ) ){
				$id = $_POST['ID'];
			}

			
			if ($tracing == 0){
				$tipo=25;
			}else{
				$tipo=26;
			}
			
			$fecha_peticion = getdate ();
			$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
			$sql_update = "UPDATE peticiones SET TRACING = '".$tracing."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = '".$id."'";
			$sql_insert = "INSERT INTO historial (id_issue, author, tipo, date) values ('".$id."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')";
			$this->db->query($sql_update);
			$this->db->query($sql_insert);
			$this->model = 1;
			return $this->model;
		}else{
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->model = 99;
			return $this->model;
		}
	}
}
?>