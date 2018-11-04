<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo registrar
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/registrar_model.php");
$gestionar_autoregistro=new registrar_model();
$mensaje = $gestionar_autoregistro->gestionar_autoregistro();

//Llamada a la vista
require_once("../index.php");

?>