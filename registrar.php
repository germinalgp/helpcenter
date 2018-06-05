<?php
require('conexion.php');
function verify_email_format ($email)
{
	// El formato correcto de e-mail debe ser del tipo nombre_usuario@dominio.dom
	// Por lo tanto, buscamos la posicion de los caracteres "@" y "." en $email
	$email_at = strpos($email, "@");
	//$email_dot = strpos ($email, ".");
	$email_dot = strrpos ($email, ".");
	
			
	// Se devuelve error si en el e-mail no existe "@" o ".", o son el primer caracter,
	// o el "." se encuentra antes que la "@", o el "." es el penultimo o ultimo caracter
	if (!$email_at || !$email_dot || $email_at == 0 || $email_dot == 0 || $email_dot <= $email_at + 1 || $email_dot >= strlen($email) - 1) { return(false); }
	else { return(true); }
}

if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 1){
	//Comprobamos que los campos nick, pass y pass1 se han rellenado en el form de reg.php. sino volvemos al form
	$error = 0; $errorNick = 2; $errorPass = 2; $errorPass2 = 2; $errorNombre = 2; $errorEmail = 2; $errorTelephone = 2;
	if ($_POST['nick'] == ''){
		$errorNick = 1;
	}
	
	if ($_POST['pass'] == ''){
		$errorPass = 1;
	}
	
	if ($_POST['pass2'] == ''){
		$errorPass2 = 1;
	}
	
	if ($_POST['nombre'] == ''){
		$errorNombre = 1;
	}
	
	if ($_POST['email'] == ''){
		$errorEmail = 1;
	}
	
	if ($_POST['telephone'] == ''){
		$errorTelephone = 1;
	}
	
	//QUITAMOS CODIGO MALICIOSO
	$email = stripslashes($_POST['email']);
	$email = strip_tags($email);
	$telephone = stripslashes($_POST['telephone']);
	$telephone = strip_tags($telephone);
		
	
	$special_chars_num = array(" ", ".", "-", "_", "(", ")","+","*","/");	
	$special_chars_email = array(" ", "(", ")","+","*","/");				
				
	
	str_replace($special_chars_num, "", $telephone);
	str_replace($special_chars_email, "", $email);
	
	
	if (($errorNick == 1) || ($errorPass == 1) || ($errorPass2 == 1) || ($errorNombre == 1) || ($errorEmail == 1) || ($errorTelephone == 1)){
		$error = $errorNick.$errorPass.$errorPass2.$errorNombre.$errorEmail.$errorTelephone;
		Header("Location:index.php?mensaje=".$error.""); 
	}else if((ctype_digit($_POST['nick']) == false) or (strlen($_POST['nick']) != 8)){
		$error = 2;
		Header("Location:index.php?mensaje=".$error."");
	}else if ($_POST['pass'] != $_POST['pass2']){
		$error = 3; //PASS ES DISTINTA DE LA CONFIRMACION
		Header("Location:index.php?mensaje=".$error.""); 
	}else if ((ctype_digit($telephone) == false) or (strlen($telephone) < 9)){
		$error = 4; //PASS ES DISTINTA DE LA CONFIRMACION
		Header("Location:index.php?mensaje=".$error.""); 
	}else if (!verify_email_format($email)) { 
		$error = 5; 
		Header("Location:index.php?mensaje=".$error.""); 
	}else{

		$user = stripslashes($_POST['nick']);
		$user = strip_tags($user);
		$pass = stripslashes($_POST['pass']);
		$pass = strip_tags($pass);
		
		//Comprobamos que el usuario no existe en la BBDD
		$usuarios = mysqli_query($connection, "SELECT nick FROM users WHERE nick = '".$user."'");
		$numrows=mysqli_num_rows($usuarios); //Numero de filas de la sentencia anterior
		if ($numrows != 0){
			$error = 6; //USUARIO YA REGISTRADO
			Header("Location:index.php?mensaje=".$error.""); 
			mysql_free_result($usuarios); //Liberamos la memoria del query a la base de datos
		}else{
			//Quitamos todo el codigo malicioso de las demas variables del form de registro
			$nombre = stripslashes($_POST['nombre']);
			$nombre = strip_tags($nombre);
			$pass2 = stripslashes($_POST['pass2']);
			$pass2 = strip_tags($pass2);
			
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			//Introducimos el nuevo registro en la tabla users
			mysqli_query($connection, "INSERT INTO users (nick,pass,nombre,fecha,level,departamento, registrador, reseteador) values ('".$user."','".$pass."','".$nombre."','".$fecha."','9','','nick','nick') ");
			Header("Location:index.php?mensaje=".$error.""); 
		}

	}

}else{
echo '<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="styles.css" rel="stylesheet" type="text/css" />
		</head>
	    <body bgcolor = "#B9D6ED">
		<form id="registrarform" method="post" action="registrar.php">
				<fieldset>
					<legend>Registro</legend>
					<p>Por favor, introduzca datos del usuario</p>
					<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
					<label id="label2">
						<input type="text" name="nick" tabindex="1" id="nick"></input>DNI (sin letras):
					</label>
					<label id="label2">
						<input type="password" name="pass" tabindex="2" id="pass"></input>Contrase&#241;a:
					</label>
					<label id="label2">
						<input type="password" name="pass2" tabindex="3" id="pass2"></input>Repetir Contrase&#241;a:
					</label>
					<label id="label2">
						<input type="text" name="nombre" tabindex="4" id="nombre"></input>Nombre y Apellidos:
					</label>
					<label id="label2">
						<input type="text" name="email" tabindex="4" id="email"></input>Email:
					</label>
					<label id="label2">
						<input type="text" name="telephone" tabindex="4" id="telephone"></input>Tel&#233;fono:
					</label>
					<label id="label2">
						<input name="Submit" type="submit" id="submit" tabindex="7" value="Enviar"></input>
					</label>
		
				</fieldset>
				</form>
		</body>
		</html>';
}


?>
