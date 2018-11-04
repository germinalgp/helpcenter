<?php
	/**
	* PHP para desbloquear una incidencia que estaba previamente bloqueada. Bloqueamos las incidencias para que un administrador tenga 
	* conocimiento que ya hay otro compañero dentro de la misma
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos



	if (($_SESSION['level'] == 4) || ($_SESSION['level'] == 3) OR ($_SESSION['level'] == 2) or ($_SESSION['level'] == 1))
	{	
	//POR SI ES NECESARIO DESBLOQUEAR
		$ID = $_GET['ID'];
		if ($ID > 0){
			mysqli_query($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$ID.""); //DESBLOQUEAMOS
		}
		Header("Location: index.php");
	} 
	else 
	{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>