<?php
	/**
	* PHP para cambiar la competencia o grupo al que pertenece una determinada incidencia.
	* Con esta opcion podemos escalar incidencias o enviarlas a otro grupo.
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	*/
	
	require('conexion.php');
	if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
		
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
		mysql_query("UPDATE peticiones SET COMPETENCIA = '".$competencia."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = '".$id."'"); //Ponemos Estado al nuevo estado
		mysql_query ("INSERT INTO historial (id_issue, author, tipo, date) values ('".$id."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')");
		Header("Location: index.php");

	}else{ //GRABAMOS
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysql_query("INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
	
?>