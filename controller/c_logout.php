<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/logout_model.php");
$logout=new logout_model();
$mensaje = $logout->logout();

if ($mensaje == 99){
	$intrusion = 1;
}	 
//Llamada a la vista
require_once("../index.php");

?>