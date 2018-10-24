<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/registrar_model.php");
$gestionar_autoregistro=new registrar_model();
$mensaje = $gestionar_autoregistro->gestionar_autoregistro();

//Llamada a la vista
require_once("../index.php");

?>