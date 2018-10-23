<?php
require_once("../db/db.php");
class resetIntrusiones_model{
    private $db;
    private $intrusion;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function resetear(){
		if ($_SESSION['level'] == 1){
		$tipo = "";
		$revisada = "";
		$fechainicial = "";
		$fechafinal = "";
		
		if ( isset ( $_GET['tipo'] ) ){
			$tipo = $_GET['tipo'];
		}
		if ( isset ( $_GET['revisada'] ) ){
			$revisada = $_GET['revisada'];
		}	
		if ( isset ( $_GET['fechainicial'] ) ){
			$fechainicial = $_GET['fechainicial'];
		}	
		if ( isset ( $_GET['fechafinal'] ) ){
			$fechafinal = $_GET['fechafinal'];
		}			
		
		

		if (($fechainicial=='') && ($fechafinal=='')){
			$sql_update = "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = ".$tipo."";
			$this->db->query($sql_update);
		}else if (($fechainicial<>'') && ($fechafinal=='')){
			$sql_update = "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha >= '".$fechainicial."'";
			$this->db->query($sql_update);
		}else if (($fechainicial=='') && ($fechafinal<>'')){
			$sql_update = "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha <= '".$fechafinal."'";
			$this->db->query($sql_update);
		}else{
			$sql_update = "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha <= '".$fechafinal."' AND fecha >= '".$fechainicial."'";
			$this->db->query($sql_update);
		}
		$this->intrusion = 0;
		return $this->intrusion; 
		
		}else{
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->intrusion = 1;
			return $this->intrusion; 
		}
	}
}
?>