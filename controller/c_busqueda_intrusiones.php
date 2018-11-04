<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo busqueda_intrusiones
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/busqueda_intrusiones_model.php");
$busqueda=new busqueda_intrusiones_model();
$datos=$busqueda->busqueda();

if ($datos == 99){
	$intrusion = 1;
	require_once("../index.php");
}else{
	//Llamada a la vista
	require_once("../intrusos.php");
}


?>