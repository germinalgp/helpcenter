<?php
	/**
	* En nuestro MVC es el controlador que llama al modelo tracing
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
date_default_timezone_set('Europe/Madrid');
//Llamada al modelo
require_once("../models/tracing_model.php");
$tracing=new tracing_model();
$model=$tracing->activar_tracing();
 
if ($model == 99){
	$intrusion = 1;	
}
//LLamada a la vista
require_once("../index.php");

?>