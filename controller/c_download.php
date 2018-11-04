<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo download
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/download_model.php");
$download=new download_model();
$mensaje = $download->download();

//Llamada a la vista
if ($mensaje == 99){
	$intrusion = 1;
	require_once("../index.php");
}else{	 
	require_once("../respuesta.php");
}

?>