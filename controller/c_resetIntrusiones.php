<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/resetIntrusiones_model.php");
$reset=new resetIntrusiones_model();
$intrusion = $reset->resetear();
 
//Llamada a la vista
if ($intrusion == 0){
	require_once("../intrusos.php");
}else{
	require_once("../index.php");
}

?>