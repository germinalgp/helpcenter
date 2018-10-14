<?php
//Llamada al modelo
require_once("../models/unlock_model.php");
$desbloquear=new unlock_model();
$model=$desbloquear->desbloquear();
 
//Llamada a la vista
require_once("../index.php");

?>