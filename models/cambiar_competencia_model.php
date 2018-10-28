<?php
require_once("../db/db.php");
class cambiar_competencia_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function cambiar_competencia(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$id = $_POST['ID'];
			$competencia = $_POST['competencia'];
			
			switch ($competencia){
				case "DESARROLLO" : $tipo = 5;
										//$comentario = "CAMBIO COMPETENCIA: DESARROLLO";
					break;
				case "TECNICA" : $tipo = 6;
								 //$comentario = "CAMBIO COMPETENCIA: TECNICA";
					break;
				case "COORDINACION" : $tipo = 4;
									  //$comentario = "CAMBIO COMPETENCIA: COORDINACION";
					break;
			}
			
			$fecha_peticion = getdate ();
			$fecha_peticion = $fecha_peticion[year]."-".$fecha_peticion[mon]."-".$fecha_peticion[mday]." ".$fecha_peticion[hours].":".$fecha_peticion[minutes].":".$fecha_peticion[seconds];
			$sql_update = "UPDATE peticiones SET COMPETENCIA = '".$competencia."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = '".$id."'";
			$sql_insert = "INSERT INTO historial (id_issue, author, tipo, date) values ('".$id."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')";
			$this->db->query($sql_update);
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