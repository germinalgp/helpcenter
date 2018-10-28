<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/filtrar_model.php");
$filtrar=new filtrar_model();
$model=$filtrar->filtrar();

if ($model == 99){
	$intrusion = 1;
}	
 
//Llamada a la vista
require_once("../index.php");

?>