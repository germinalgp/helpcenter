<?php
//Llamada al modelo
require_once("../models/busqueda_model.php");
$busqueda=new busqueda_model();
$datos=$busqueda->get_busqueda();
 
//Llamada a la vista
require_once("../busqueda.php");

?>