<?php
	/**
	* PHP para descargar un fichero que haya sido previamente subido a la incidencia
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); 
	if ($_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
	$sql = "SELECT date_format(DATE, '%Y-%m-%d %H:%i:%s' ) as DATE FROM peticiones WHERE ID = ".$_GET['ID'];
	$resultado=mysqli_fetch_array(mysqli_query($connection, $sql));
	$fecha_peticion = date_parse($resultado[0]);

	$path = "smb/incidencias/".$fecha_peticion["year"]."/".str_pad($fecha_peticion["month"], 2, "0", STR_PAD_LEFT)."/".str_pad($fecha_peticion["day"], 2, "0", STR_PAD_LEFT)."/".$_GET['ID']."/";
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
	exit;
	}else{ //GRABAMOS
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysql_query("INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>
