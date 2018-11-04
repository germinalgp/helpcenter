<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de filtrar en las pasarelas de incidencias en funcion del TIER
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	
require_once("../db/db.php");
class filtrar_model{
    private $db;
    private $model;
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function filtrar(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$f_coordinacion = 0;
			$f_desarrollo = 0;
			$f_tecnico = 0;
			
			if ( isset ( $_POST['f_coordinacion'] ) ){
				$f_coordinacion = 1;
			}
			if ( isset ( $_POST['f_desarrollo'] ) ){
				$f_desarrollo = 1;
			}
			if ( isset ( $_POST['f_tecnico'] ) ){
				$f_tecnico = 1;
			}
			$sql_update = "UPDATE users SET f_coordinacion = $f_coordinacion, f_desarrollo = ".$f_desarrollo.", f_tecnico = ".$f_tecnico." WHERE nick = '".$_SESSION['usuario']."'";
			$this->db->query($sql_update);
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