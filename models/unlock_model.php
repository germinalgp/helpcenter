<?php
require_once("../db/db.php");
class unlock_model{
    private $db;
    private $model;
	
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function desbloquear(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){   
			$ID = $_GET['ID'];
			if ($ID > 0){
				$sql = "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$ID."";
				$this->db->query($sql);
			}
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