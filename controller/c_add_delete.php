<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/add_delete_model.php");
$add_delete=new add_delete_model();
$error = $add_delete->add_delete();

//Llamada a la vista
require_once("../add_delete.php");

?>