<?php
	/**
	* En nuestro MVC es el modelo que representa la funcionalidad de descargar archivos que hemos subido
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	
require_once("../db/db.php");
class download_model{
    private $db;
	private $error;
 
    public function __construct(){
        $this->db=Conectar::connection();
    }
    public function download(){
		if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
			$sql_select = "SELECT date_format(DATE, '%Y-%m-%d %H:%i:%s' ) as DATE FROM peticiones WHERE ID = ".$_GET['ID'];
			$query = $this->db->query($sql_select);
			$resultado = mysqli_fetch_array($query);
			$fecha_peticion = date_parse($resultado[0]);

			$path = "../smb/incidencias/".$fecha_peticion["year"]."/".str_pad($fecha_peticion["month"], 2, "0", STR_PAD_LEFT)."/".str_pad($fecha_peticion["day"], 2, "0", STR_PAD_LEFT)."/".$_GET['ID']."/";
			$fullPath = $path.$_GET['download_file'];
			
			if ($fd = fopen ($fullPath, "r")) {
				
				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);
				switch ($ext) {
					case "pdf":
					header("Content-type: application/pdf");
					break;
					default;
					header("Content-type: application/octet-stream");
				}
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
				while(!feof($fd)) {
					$buffer = fread($fd, 2048);
					echo $buffer;
				}
			}
			fclose ($fd);
			$this->error = 0;
			return $this->error;
		}else{ //GRABAMOS
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