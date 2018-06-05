<?php
require('conexion.php');

if ($_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
	$id = $_POST['ID'];
	$sql="SELECT * FROM historial WHERE ID_ISSUE = ".$id." ORDER BY ID ASC";
	$resultado=mysqli_query($connection, $sql);
	echo '<html>
		  <head>
		 		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>INCIDENCIAS</title>
				<link href="styles.css" rel="stylesheet" type="text/css" />
				
		 </head>
		<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
		
			<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
	while ($datos=mysqli_fetch_array($resultado)){
		echo'<form id="formLarge">
				<fieldset>';
					switch ($datos["TIPO"]){
							case 1 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend><img title="ESCRITURA" height="25" src="images/abierta_ico.gif" alt="NO IMAGEN"></img> CAMBIO ESTADO: ABIERTA';	
								break;
							case 2 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend><img title="ESCRITURA" height="25" src="images/tramite_ico.gif" alt="NO IMAGEN"></img> CAMBIO ESTADO: TRAMITE';	
								break;
							case 3 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend><img title="ESCRITURA" height="25" src="images/cerrada_ico.gif" alt="NO IMAGEN"></img> CAMBIO ESTADO: CERRADA';	
								break;
							case 4 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend>CAMBIO COMPETENCIA: COORDINACION';	
								break;
							case 5 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend>CAMBIO COMPETENCIA: DESARROLLO';	
								break;
							case 6 : 
								echo '<legend><b>['.$datos["DATE"].']</b> '.$datos["AUTHOR"].' ha realizado: </legend>CAMBIO COMPETENCIA: TECNICA';	
								break;

					}
			echo '</fieldset>
			</form>';
	}
	
	echo'</body>
		</html>';
}else{ //GRABAMOS
	$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
	$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
	$IP = $_SERVER['REMOTE_ADDR'];
	$pagina = $_SERVER['PHP_SELF'];
	mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
	Header("Location: index.php?intrusion=1");
}
?>