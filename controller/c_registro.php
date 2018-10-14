<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/registro_model.php");
$gestionar_registro=new registro_model();
$error = $gestionar_registro->gestionar_registro();
 
//Llamada a la vista
require_once("../registro.php");

?>