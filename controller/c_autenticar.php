<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/autenticar_model.php");
$autenticacion=new autenticar_model();
$error = $autenticacion->autenticar();
 
//Llamada a la vista 
if ($error == 1){
	require_once("../index.php");
}else{
	$intrusion = $error;
	require_once("../index.php");
}	


?>
