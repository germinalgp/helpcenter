<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo cambiar_pass
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/cambiar_pass_model.php");
$cambiar_pass=new cambiar_pass_model();
$mensaje=$cambiar_pass->cambiar_pass();

//Llamada a la vista 
if ($mensaje == 99){
	$intrusion = 1;
	require_once("../index.php");
}else{
	require_once("../cambio_pass.php");
}	
 


?>