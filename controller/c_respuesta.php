<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/respuesta_model.php");
$respuesta=new respuesta_model();
$error=$respuesta->responder();

//Llamada a la vista
if ($error == 99){
	$intrusion = 1;
	require_once("../index.php");
}else{
	$desbloquear = substr($error,1);
	require_once("../index.php");
}	

?>