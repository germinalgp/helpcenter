<?php
require_once("../db/db.php");
class tracing_model{
    private $db;
    private $model;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function activar_tracing(){
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
	}
}
?>