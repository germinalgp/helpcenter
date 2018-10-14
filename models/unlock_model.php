<?php
require_once("../db/db.php");
class unlock_model{
    private $db;
    private $model;
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function desbloquear(){
		$ID = $_GET['ID'];
		if ($ID > 0){
			$sql = "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$ID."";
			$this->db->query($sql);
		}
		$this->model = 1;
		return $this->model;
	}
}
?>