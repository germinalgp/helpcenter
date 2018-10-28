<?php
require_once("../db/db.php");
class busqueda_model{
    private $db;
    private $busqueda;
 
    public function __construct(){
        $this->db=Conectar::connection();
        $this->busqueda=array();
    }
    public function get_busqueda(){
		if ($_SESSION['level'] == 9 || $_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$user_open = "";
			$user_close = "";
			$n_serie = "";
			$fechainicial = "";
			$fechafinal = "";
			
			//MODIFICAMOS LAS VARIABLES $_POST PARA CREAR NUESTRA CONSULTA
			if ($_POST['user_open']!=""){
				$user_open = " AND USER_OPEN LIKE '".$_POST['user_open']."'";
			}
			if ($_POST['n_serie']!=""){
				$n_serie = " AND N_SERIE LIKE '".$_POST['n_serie']."'";
			}
			if ($_POST['fechainicial']!=""){
				$fechainicial = " AND DATE >= '".$_POST['fechainicial']." 00:00:00'";
			}
			if ($_POST['fechafinal']!=""){
				$fechafinal = " AND DATE <= '".$_POST['fechafinal']." 23:59:59'";
			}
			
			if ($_SESSION['level'] == 9){
				//SI NO ES ADMINISTRADOR SOLO PUEDE VER LAS INCIDENCIAS SUYAS
				$sql_busqueda="SELECT DISTINCT p.ID,p.USER_OPEN, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos c WHERE p.USER_OPEN = ".$_SESSION['usuario']." AND p.ISSUE_TYPE = c.ID_COMBO".$user_open.$n_serie.$fechainicial.$fechafinal." ORDER BY p.DATE";	
			}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ 
				//SI ES USUARIO ADMINISTRADOR PUEDE VER TODAS LAS INCIDENCIAS DE TODOS LOS USUARIOS
				if ($_POST['user_close']!=""){
					$sql_busqueda="(SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d, comentarios c WHERE p.ISSUE_TYPE = d.ID_COMBO AND p.ID = c.ID_ISSUE".$user_open.$n_serie.$fechainicial.$fechafinal." AND c.AUTHOR LIKE '".$_POST['user_close']."' ORDER BY p.DATE)
					UNION (SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d, historial h WHERE p.ISSUE_TYPE = d.ID_COMBO AND p.ID = h.ID_ISSUE".$user_open.$n_serie.$fechainicial.$fechafinal." AND h.AUTHOR LIKE '".$_POST['user_close']."' ORDER BY p.DATE)
					";
				}else{
					$sql_busqueda="SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d WHERE p.ISSUE_TYPE = d.ID_COMBO".$user_open.$n_serie.$fechainicial.$fechafinal." ORDER BY p.DATE";
				}
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
			$this->busqueda = 99;
			return $this->busqueda;	
		}
    }
}
?>