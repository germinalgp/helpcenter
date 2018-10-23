<?php
require_once("../db/db.php");
class busqueda_intrusiones_model{
    private $db;
    private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function busqueda(){
		if (($_POST['fechainicial']=='') && ($_POST['fechafinal']=='')){
			$sql_busqueda = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." order by fecha DESC";	
		}else if (($_POST['fechainicial']<>'') && ($_POST['fechafinal']=='')){
			$sql_busqueda = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha >= '".$_POST['fechainicial']."' order by fecha DESC";	
		}else if (($_POST['fechainicial']=='') && ($_POST['fechafinal']<>'')){
			$sql_busqueda = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha <= '".$_POST['fechafinal']."' order by fecha DESC";	
		}else{
			$sql_busqueda = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha <= '".$_POST['fechafinal']."' AND fecha >= '".$_POST['fechainicial']."' order by fecha DESC";	
		}
		
		$consulta=$this->db->query($sql_busqueda);
        while($filas=$consulta->fetch_assoc()){
            $this->busqueda[]=$filas;
        }
        return $this->busqueda;									
	}

			
				
	
}
?>