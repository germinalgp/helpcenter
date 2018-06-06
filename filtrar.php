<?php
	/**
	* PHP para filtrar las incidencias por los grupos que se han definido para los administradores que resuelven incidencias.
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); 	
	if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
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
		
		mysqli_query($connection, "UPDATE users SET f_coordinacion = $f_coordinacion, f_desarrollo = ".$f_desarrollo.", f_tecnico = ".$f_tecnico." WHERE nick = '".$_SESSION['usuario']."'"); //Actualizamos el numero de intentos
		
		Header("Location: index.php");

	}else{ //GRABAMOS
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
		
?>