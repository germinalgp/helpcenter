<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de realizar el logout de la aplicacion
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/

require_once("../db/db.php");
class logout_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function logout(){
		if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2 || $_SESSION['level'] == 3 || $_SESSION['level'] == 4 || $_SESSION['level'] == 9) {
			//POR SI ES NECESARIO DESBLOQUEAR
			if ($_SESSION['block'] > 0){
				$sql_update = "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block']."";
				$this->db->query($sql_update);
				$_SESSION['block'] = 0;
			}
			if (isset($_SESSION['usuario'])){
				unset($_SESSION['usuario']);
				unset($_SESSION['level']);
				unset($_SESSION['block']);
			}
			$this->error = 30;
			return $this->error;
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