<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/cambiar_estado_model.php");
$cambiar_estado=new cambiar_estado_model();
$cambiar_estado->cambiar_estado();
 
//Llamada a la vista
require_once("../respuesta.php");

?>