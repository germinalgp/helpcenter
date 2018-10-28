<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/cambiar_estado_model.php");
$cambiar_estado=new cambiar_estado_model();
$error = $cambiar_estado->cambiar_estado();
 
//Llamada a la vista 
if ($error == 99){
	$intrusion = 1;
	require_once("../index.php");
}else{
	require_once("../respuesta.php");
}	
 


?>