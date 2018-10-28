<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/unlock_model.php");
$desbloquear=new unlock_model();
$model=$desbloquear->desbloquear();

if ($model == 99){
	$intrusion = 1;
}	
//Llamada a la vista
require_once("../index.php");

?>