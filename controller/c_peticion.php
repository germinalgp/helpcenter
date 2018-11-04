<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo peticion
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/peticion_model.php");
$gestionar_peticion=new peticion_model();
$mensaje = $gestionar_peticion->peticion();


//Llamada a la vista 
//Si es 1 llama a la vista pero sin login y si es 21 es vista con login
if ($mensaje == 99){ //Intento de intrusion
	$intrusion = 1;
	require_once("../index.php");
}else if (substr($mensaje,0,2) == 21){
	require_once("../index.php");
}else{
	require_once("../peticion.php");
}

?>