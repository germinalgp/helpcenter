<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/download_model.php");
$download=new download_model();
$download->download();
 
//Llamada a la vista
require_once("../respuesta.php");

?>