<?php
require ('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2 || $_SESSION['level'] == 3 || $_SESSION['level'] == 4 || $_SESSION['level'] == 9) {
//POR SI ES NECESARIO DESBLOQUEAR
	if ($_SESSION['block'] > 0){
		mysqli_query($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
		$_SESSION['block'] = 0;
	}
if (isset($_SESSION['usuario'])){
unset($_SESSION['usuario']);
unset($_SESSION['level']);
unset($_SESSION['block']);
}
Header ("Location:index.php"); //Volvemos al index.php
}else{
	$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
	$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
	$IP = $_SERVER['REMOTE_ADDR'];
	$pagina = $_SERVER['PHP_SELF'];
	mysql_query("INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
	Header("Location: index.php?intrusion=1");
}
?>
