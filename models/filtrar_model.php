<?php
require_once("../db/db.php");
class filtrar_model{
    private $db;
    private $model;
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function filtrar(){
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
	}
}
?>