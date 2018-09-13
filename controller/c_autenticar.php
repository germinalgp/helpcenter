<?php
	/**
	* PHP para lograr la autenticacion de usuarios sobre una Base de datos
	* @author Germinal GARRIDO PUYANA
	*/
	require('../conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	
	if (($_POST['nick'] == '') and ($_POST['pass'] == '')) //Comprobamos que las variables enviadas por el form de index.php tienen contenido
	{
		Header("Location:../index.php"); //estan vacias, volvemos al index
	}
	elseif ((ctype_digit($_POST['nick']) == false) or (strlen($_POST['nick']) > 8) or (strlen($_POST['nick'] < 8)))
	{
		Header("Location:../index.php"); //usuarios no validos
	}
	else
	{ 
		//Comprobamos en la Base de datos si existe ese nick con esa pass
		$usuarios = mysqli_query($connection, "SELECT * FROM users WHERE nick = '".$_POST['nick']."' and pass = '".$_POST['pass']."' and active = 1");
		$user_ok = mysqli_fetch_array($usuarios); //Obtenemos el usuario en user_ok
		if ($user_ok && $user_ok["intentos"] < 3){
			mysqli_query($connection, "UPDATE users SET intentos = 0 WHERE nick = '".$_POST['nick']."'"); //Actualizamos el numero de intentos
			
			//Damos valores a las variables de la sesion
			$_SESSION['usuario'] = $user_ok["nick"]; //damos el nick a la variable usuario
			$_SESSION['level'] = $user_ok["level"]; //damos el level del user a la variable level
			$_SESSION['email'] = $user_ok["email"];
			$_SESSION['telephone'] = $user_ok["telephone"];
			$_SESSION['block'] = 0; //damos el level del user a la variable level
		
			Header("Location:../index.php"); //Volvemos al login donde nos saldra nuestro menu de usuario
		}
		else
		{
			$usuarios2 = mysqli_query($connection, "SELECT * FROM users WHERE nick = '".$_POST['nick']."'"); //Sentencia para buscarlo en la base de datos
			$numrows=mysql_num_rows($usuarios2); //Numero de filas de la sentencia anterior
			if ($numrows == 0){ //No existe ese usuario en la tabla de usuarios por lo tanto ser usuario de level 4*/
				$IP = $_SERVER['REMOTE_ADDR'];
				$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
				$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
				$descripcion = "NICK/PASS incorrectos";
				mysqli_query($connection, "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',3,'".$descripcion."','".$fecha."') ");
				Header("Location: ../index.php?login_fail_N4=1");
			}
			else //USUARIO QUE NO SE HA LOGUEADO CON LA CLAVE CORRECTA
			{
				$row=mysqli_fetch_row($usuarios2);
				$intentos = $row[9];
				$IP = $_SERVER['REMOTE_ADDR'];
				if ($intentos < 2) { //Al tercer intento fallido se bloquea
					$intentos++; //Aumentamos los intentos
					/////Incluimos el intento de intrusion
					$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
					$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
					$descripcion = "Intento de intrusion: ".$intentos."";
					mysqli_query($connection, "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',1,'".$descripcion."','".$fecha."') ");
					
					/////Actualizamos el numero de intentos
					mysqli_query($connection, "UPDATE users SET intentos = ".$intentos." WHERE nick = '".$_POST['nick']."'"); 
					Header("Location:../index.php?intentos=".$intentos.""); //Volvemos al login donde nos saldra nuestro menu de usuario
					
				}else {
					$intentos++;
					$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
					$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
					$descripcion = "Cuenta bloqueada";
					mysqli_query($connection, "INSERT INTO intrusos (nick,pass,IP,tipo,descripcion,fecha) values ('".$_POST['nick']."','".$_POST['pass']."','".$IP."',2,'".$descripcion."','".$fecha."') ");
					
					/////Actualizamos el numero de intentos
					mysqli_query($connection, "UPDATE users SET intentos = ".$intentos." WHERE nick = '".$_POST['nick']."'"); 
					Header("Location: ../index.php?intentos=".$intentos."");
					
				}
			
			}
			
			
		}

	 
    }   
 
?>
