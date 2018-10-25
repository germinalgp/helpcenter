<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/peticion_model.php");
$gestionar_peticion=new peticion_model();
$mensaje = $gestionar_peticion->peticion();

//Llamada a la vista 
//Si es 1 llama a la vista pero sin login y si es 21 es vista con login
if ($mensaje == 1 || substr($mensaje,0,2) == 21){ //Intento de intrusion
	require_once("../index.php");
}else{
	require_once("../peticion.php");
}

?>