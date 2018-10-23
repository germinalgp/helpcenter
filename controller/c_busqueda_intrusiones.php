<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/busqueda_intrusiones_model.php");
$busqueda=new busqueda_intrusiones_model();
$datos=$busqueda->busqueda();

//Llamada a la vista
require_once("../intrusos.php");

?>