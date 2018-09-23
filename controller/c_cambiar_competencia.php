<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/cambiar_competencia_model.php");
$cambiar_competencia=new cambiar_competencia_model();
$datos=$cambiar_competencia->cambiar_competencia();
 
//Llamada a la vista
require_once("../respuesta.php");

?>