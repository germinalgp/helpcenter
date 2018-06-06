<?php
	/**
	* PHP para resetear las intrucciones que hemos analizado segun el tipo de la misma
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos

	if ($_SESSION['level'] == 1)
	{
		$tipo = "";
		$revisada = "";
		$fechainicial = "";
		$fechafinal = "";
		
		if ( isset ( $_GET['tipo'] ) ){
			$tipo = $_GET['tipo'];
		}
		if ( isset ( $_GET['revisada'] ) ){
			$revisada = $_GET['revisada'];
		}	
		if ( isset ( $_GET['fechainicial'] ) ){
			$fechainicial = $_GET['fechainicial'];
		}	
		if ( isset ( $_GET['fechafinal'] ) ){
			$fechafinal = $_GET['fechafinal'];
		}			
		
		

		if (($fechainicial=='') && ($fechafinal=='')){
			mysqli_query($connection, "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = ".$tipo."");	
		}else if (($fechainicial<>'') && ($fechafinal=='')){
			mysqli_query($connection, "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha >= '".$fechainicial."'");	
		}else if (($fechainicial=='') && ($fechafinal<>'')){
			mysqli_query($connection, "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha <= '".$fechafinal."'");	
		}else{
			mysqli_query($connection, "UPDATE intrusos SET revisado = 1 WHERE revisado = 0 AND tipo = $tipo AND fecha <= '".$fechafinal."' AND fecha >= '".$fechainicial."'");
		}
		Header("Location: intrusos.php");
	}else{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>
