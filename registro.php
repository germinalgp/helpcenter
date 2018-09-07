<?php
	/**
	* PHP para realizar el registro de un usuario por parte de un administrador
	* @author Germinal GARRIDO PUYANA
	* @return boolean true si es correcta la verificacion y false en caso contrario 
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');

	/**
	* Funcion que realiza una comprobacion del formato correcto de una direccion de email
	* @author Germinal GARRIDO PUYANA
	* @param string $email cadena de texto conteniendo la direccion email a verificar
	* @return boolean true si es correcta la verificacion y false en caso contrario 
	*/
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

	if ($_SESSION['level'] == 1 || $_SESSION['level'] == 3) {
		if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 1){
		//Comprobamos que los campos nick, pass y pass1 se han rellenado en el form de reg.php. sino volvemos al form
		$error = 0; $errorNick = 2; $errorPass = 2; $errorPass2 = 2; $errorNombre = 2; $errorEmail = 2; $errorTelephone = 2; $errorLevel = 2; $errorDepartamento = 2;
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
		
		if ($_POST['level'] == "-1"){
			$errorLevel = 1;
		}
		
		if ($_POST['departamento'] == "-1"){
			$errorDepartamento = 1;
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
		
		
		if (($errorNick == 1) || ($errorPass == 1) || ($errorPass2 == 1) || ($errorNombre == 1) || ($errorEmail == 1) || ($errorTelephone == 1) || ($errorLevel == 1) || ($errorDepartamento == 1)){
			$error = $errorNick.$errorPass.$errorPass2.$errorNombre.$errorEmail.$errorTelephone.$errorLevel.$errorDepartamento;
			Header("Location:registro.php?mensaje=".$error.""); 
		}else if((ctype_digit($_POST['nick']) == false) or (strlen($_POST['nick']) != 8)){
			$error = 2;
			Header("Location:registro.php?mensaje=".$error.""); 
		}else if ($_POST['pass'] != $_POST['pass2']){
			$error = 3; //PASS ES DISTINTA DE LA CONFIRMACION
			Header("Location:registro.php?mensaje=".$error."");
		}else if ((ctype_digit($telephone) == false) or (strlen($telephone) < 9)){
			$error = 4; //PASS ES DISTINTA DE LA CONFIRMACION
			Header("Location:registro.php?mensaje=".$error.""); 
		}else if (!verify_email_format($email)) { 
			$error = 5; 
			Header("Location:registro.php?mensaje=".$error.""); 
		}else{

			$user = stripslashes($_POST['nick']);
			$user = strip_tags($user);
			$pass = stripslashes($_POST['pass']);
			$pass = strip_tags($pass);
			
			//Comprobamos que el usuario no existe en la BBDD
			$usuarios = mysqli_query ($connection, "SELECT nick FROM users WHERE nick = '".$user."'");
			$numrows=mysqli_num_rows($usuarios); //Numero de filas de la sentencia anterior
			if ($numrows != 0){
				$error = 6; //USUARIO YA REGISTRADO
				Header("Location:registro.php?mensaje=".$error.""); //Enviamos al form de registro que está en reg.php con el codigo 2
				mysql_free_result($usuarios); //Liberamos la memoria del query a la base de datos
			}else{
				//Quitamos todo el codigo malicioso de las demas variables del form de registro
				$nombre = stripslashes($_POST['nombre']);
				$nombre = strip_tags($nombre);
				$pass2 = stripslashes($_POST['pass2']);
				$pass2 = strip_tags($pass2);
				$level = stripslashes($_POST['level']);
				$level = strip_tags($level);
				$departamento = stripslashes($_POST['departamento']);
				$departamento = strip_tags($departamento);
				
				$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
				$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
				//Introducimos el nuevo registro en la tabla users
				mysqli_query ($connection, "INSERT INTO users (nick,pass,nombre,fecha,level,departamento, registrador, reseteador, active) values ('".$user."','".$pass."','".$nombre."','".$fecha."','".$level."','".$departamento."','".$_SESSION['usuario']."','', '1') ");
				Header("Location:registro.php?mensaje=".$error.""); //Enviamos al form de registro que está en reg.php con el codigo 3
			}

		}

	}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 2){
			$userid = $_POST['ID'];
			mysqli_query ($connection, "UPDATE users SET active = '1' WHERE nick LIKE '".$userid."'");
			Header("Location:registro.php"); //Enviamos al form de registro que está en reg.php con el codigo 3
	
	}else if ( isset ( $_POST['enviar_peticion']) && $_POST['enviar_peticion'] == 3){
			$userid = $_POST['ESTADO'];
			$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
			$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
			mysqli_query ($connection, "INSERT INTO delete_users SELECT nick, nombre, email, telephone, '".$fecha."', level, departamento, cod_departamento, registrador, '".$_SESSION['usuario']."' FROM users WHERE nick LIKE '".$userid."'");
			mysqli_query ($connection, "DELETE FROM users WHERE nick LIKE '".$userid."'");
			Header("Location:registro.php"); //Enviamos al form de registro que está en reg.php con el codigo 3
	
	}else{
			if ($_SESSION['block'] > 0){
				mysqli_query ($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
				$_SESSION['block'] = 0;
			}
			echo '<html>
			<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER - REGISTRO USUARIO</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="tinybox.js"></script>
			</head>';
				
			$mensaje = "";
		
			if ( isset ( $_GET['mensaje'] ) ){
				$mensaje = $_GET['mensaje'];
			}	

			if ($mensaje != ''){
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\'message.php?mensaje='.$mensaje.'\',width:320,height:240})">';
			}else{
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">';
			}
			echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			
			
			menu_int(0,0,1,0,0,0);
			 
			echo '<blockquote><blockquote>
					<p align="left"><b><font size="4">REGISTRO DE USUARIOS</font></b></p>
				</blockquote></blockquote>
			
				<blockquote><blockquote>
				
			<table>
			<tr>
			<td>
			<form id="registrarform" method="post" action="registro.php">
					<fieldset>
						<legend>Registro</legend>
						<p>Por favor, introduzca datos del usuario</p>
						<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
						<label id="label2" for="nick">
							<input type="text" name="nick" tabindex="1" id="nick" />DNI (sin letras):
						</label>
						<label id="label2" for="password">
							<input type="password" name="pass" tabindex="2" id="pass" />Contrase&#241;a:
						</label>
						<label id="label2" for="password2">
							<input type="password" name="pass2" tabindex="3" id="pass2" />Repetir Contrase&#241;a:
						</label>
						<label id="label2" for="nombre">
							<input type="text" name="nombre" tabindex="4" id="nombre" />Nombre y Apellidos:
						</label>
						<label id="label2" for="email">
							<input type="text" name="email" tabindex="4" id="email" />Email:
						</label>
						<label id="label2" for="telephone">
							<input type="text" name="telephone" tabindex="4" id="telephone" />Tel&#233;fono:
						</label>
						<label id="label2" for="level">
							<select name = "level" tabindex="5" id="level">
								<option value = "-1">Escoja una opci&#243;n:</option>
								<option value = "1">1:Desarrollador</option>
								<option value = "2">2:Jefes y/o superiores</option>
								<option value = "3">3:T&#233;cnico</option>
								<option value = "4">4:Operadores</option>
							</select>Nivel:
						</label>
						<label id="label2" for="departamento">
							<select name="departamento" tabindex="6" id="departamento">
								<option value = "-1">Escoja una opci&#243;n:</option>
								<option value = "T&#233;cnico">Grupo T&#233;cnico</option>
								<option value = "Desarrollo">Grupo de Desarrolo</option>
								<option value = "Coordinaci&#243;n">Grupo Coordinaci&#243;n</option>
								<option value = "Administraci&#233;n">Grupo Administraci&#243;n</option>
							</select>Departamento:
						</label>
						<label id="label2" for="submit">
							<input name="Submit" type="submit" id="submit" tabindex="7" value="Enviar" />
						</label>
						<p align = "justify">
						<b>Instrucciones: </b>El formato de Nombre y Apellidos ser&#225; "Nombre APELLIDO1 APELLIDO2", siempre sin acentos. <br> (ejemplo: Fernando Jose TORRES SANZ)
						</p>
						<u>Selecci&#243;n del nivel:</u> 
						<ul>
						<li><p align="justify">Como norma general 4:Operadores.</li>
						<li><p align="justify">Desarrollador ser&#225; para personal cualificado y hay que motivarlo con los desarrolladores de la aplicaci&#243;n.</li> 
						<li><p align="justify">El nivel Jefes y/o superiores se seleccionar&#225; para aquellos superiores que se determine.</li>
						<li><p align="justify">El nivel T&#233;cnico se seleccionar&#225; para miembros del Grupo T&#233;cnico.</li><br>
						</ul>		
					</fieldset>
					</form>
					</td>
					<td valign="top">';
					$colorFila="filaBlanca";
					$sql_registro="SELECT nick, nombre, email, telephone, fecha FROM users WHERE active = 0 ORDER BY fecha ASC";
					$resultado_registro=mysqli_query($connection, $sql_registro);
					
					//MOSTRAR USUARIOS
					echo '<table class="borde" cellpading="1" cellspacing="0">
					<tr>
						<td align="center" colspan="6"><font face="Arial Black" size="5">Peticiones de registro</font></td>
					</tr>
					<tr>
						<td align="center" width="80"><b><font face="Arial">Usuario</font></b></td>
						<td align="center" width="100"><b><font face="Arial">Nombre/Apellidos</font></b></td>
						<td align="center" width="100"><b><font face="Arial">Email</font></b></td>
						<td align="center" width="100"><b><font face="Arial">Tel&#233;fono</font></b></td>
						<td align="center" width="100"><b><font face="Arial">Fecha</font></b></td>										
						<td align="center"><b></b></td>
					</tr>';
					
					while ($datos_registro=mysqli_fetch_array($resultado_registro))
						{	
							echo '
							<form id="searchform" method="post" action="registro.php">
							<tr class="'.$colorFila.'">
								<td align="center">'.$datos_registro["nick"].'</td>
								<td align="center">'.$datos_registro["nombre"].'</td>
								<td align="center">'.$datos_registro["email"].'</td>		
								<td align="center">'.$datos_registro["telephone"].'</td>
								<td align="center">'.$datos_registro["fecha"].'</td>
															
								<td align="center" valign="middle">
									<input type="hidden" name="enviar_peticion" value="2" size="1"></input>								
									<input type="hidden" name="ID" value="'.$datos_registro["nick"].'"></input>
									<input name="Submit" type="submit" id="submit" value="ACEPTAR"></input>											
								</td>
							</tr>
							</form>';							  
							//Cambio el color de la fila	
							if ($colorFila == "filaBlanca") 
							{
								$colorFila = "filaMorada";
							}
							else
							{
								$colorFila = "filaBlanca";
							}
						}	
					echo '</table>';
					
					$colorFila="filaBlanca";
					$sql_delete="SELECT nick, nombre, email, telephone, fecha FROM users WHERE nick NOT LIKE '".$_SESSION['usuario']."' ORDER BY fecha ASC";
					$resultado_delete=mysqli_query($connection, $sql_delete);
					
					//MOSTRAR USUARIOS
			echo '<br>
					<table class="borde" cellpading="1" cellspacing="0">
					<tr>
						<td align="center" colspan="6"><font face="Arial Black" size="5">Eliminar usuario</font></td>
					</tr>
					<tr class="'.$colorFila.'">
						<td align="center" colspan="5">
						<form id="searchform" method="post" action="registro.php">
							<select style="width:98%;" name="ESTADO" id="estado">';		
							while ($datos_delete=mysqli_fetch_array($resultado_delete)){
								echo '<option value="'.$datos_delete["nick"].'">'.$datos_delete["nick"].';'.$datos_delete["nombre"].'</option>';
							}			
				echo '</select>
					</td>
					<td>
						<input type="hidden" name="enviar_peticion" value="3" size="1"></input>								
						<input name="Submit" type="submit" id="submit" value="ELIMINAR"></input>		
					</td>
					</tr>
					</table>
					</blockquote></blockquote>
			</body>
			</html>';
	}
	} else {
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query ($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}


?>
