<?php
require_once("../db/db.php");
class busqueda_intrusiones_model{
    private $db;
	private $intrusion;
	
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function busqueda(){
		if ($_SESSION['level'] == 1){
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
		}else{
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			$IP = $_SERVER['REMOTE_ADDR'];
			$pagina = $_SERVER['PHP_SELF'];
			$sql_insert = "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ";
			$this->db->query($sql_insert);
			$this->intrusion = 99;
			return $this->intrusion; 

		}		
	}

			
				
	
}
?>