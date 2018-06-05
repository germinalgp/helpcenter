<?php
require ('conexion.php'); 	
if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ //SI SESSION Y NIVEL ADECUADO
	
	$id = "";
	$tracing = "";
	
	if ( isset ( $_POST['tracing'] ) ){
		$tracing = $_POST['tracing'];
	}
	
	if ( isset ( $_POST['ID'] ) ){
		$id = $_POST['ID'];
	}

	
	if ($tracing == 0){
		$tipo=25;
	}else{
		$tipo=26;
	}
	
	$fecha_peticion = getdate ();
	$fecha_peticion = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
	mysqli_query($connection, "UPDATE peticiones SET TRACING = '".$tracing."', LAST_USER_MODIFY = '".$_SESSION['usuario']."', LAST_DATE_MODIFY = '".$fecha_peticion."' WHERE ID = '".$id."'"); //Ponemos Estado al nuevo estado
	//mysql_query ("INSERT INTO comentarios (id_issue, author, comments, tipo_comentario, date) values ('$id','$_SESSION[usuarioInc]','$comentario','$tipo_comentario','$fecha_peticion')");
	mysqli_query ($connection, "INSERT INTO historial (id_issue, author, tipo, date) values ('".$id."','".$_SESSION['usuario']."','".$tipo."','".$fecha_peticion."')");
	Header("Location: index.php");

}else{ //GRABAMOS
	$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
	$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
	$IP = $_SERVER['REMOTE_ADDR'];
	$pagina = $_SERVER['PHP_SELF'];
	mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
	Header("Location: index.php?intrusion=1");
}
	
?>
	
	
	
	
	
	

