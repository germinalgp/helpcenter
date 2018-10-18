<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/logout_model.php");
$logout=new logout_model();
$logout->logout();
 
//Llamada a la vista
require_once("../index.php");

?>