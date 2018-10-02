<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/filtrar_model.php");
$filtrar=new filtrar_model();
$model=$filtrar->filtrar();
 
//Llamada a la vista
require_once("../index.php");

?>