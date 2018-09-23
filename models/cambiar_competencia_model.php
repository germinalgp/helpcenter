<?php
require_once("../db/db.php");
class cambiar_competencia_model{
    private $db;
    private $cambiar_competencia;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function cambiar_competencia(){
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
		$consulta=$this->db->query($sql_update);
		$consulta=$this->db->query($sql_insert);
	}
}
?>