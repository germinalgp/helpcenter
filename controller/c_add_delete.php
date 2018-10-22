<?php
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/add_delete_model.php");
$error=new add_delete_model();
$add_delete_model->add_delete();
 
//Llamada a la vista
require_once("../add_delete.php");

?>