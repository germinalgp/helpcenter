<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/tracing_model.php");
$tracing=new tracing_model();
$model=$tracing->activar_tracing();
 
//Llamada a la vista
require_once("../index.php");

?>