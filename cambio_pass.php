<?php
	/**
	* PHP que posibilita que un usuario puede cambiar su password.
	* Ademas los usuarios con mas level pueden resetear o cambiar el password de otros usuarios. Un usuario se bloquea al introducir
	* 3 veces la password incorrecta y por lo tanto hay que resetearlo.
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');


	if ($_SESSION['level'] == 9 || $_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1) {
			
		if ($_SESSION['block'] > 0){
			mysqli_query ($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
			$_SESSION['block'] = 0;
		}
		echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER - RESETEO PASS</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<link href="../styles.css" rel="stylesheet" type="text/css" /> 
			</head>
			  <body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			 
			if ($_SESSION['level'] == 9){
				menu_ext(0,0,1);
			}else{
				menu_int(0,0,0,1,0,0);
			}
			
			
			if ($mensaje==1){
				echo 'OPERACION REALIZADA CON EXITO';
			}else if ($mensaje==2){
				echo 'PASS Y PASS2 TIENEN QUE SER DIFERENTES Y NO PUEDE ESTAR VACIO';
			}else if ($mensaje==3){
				echo 'RESETEO REALIZADO CON EXITO';
			}
			
			echo '<blockquote><blockquote><blockquote><blockquote><blockquote>
					<p align="left"><b><font size="4">CAMBIO DE PASSWORD PROPIO</font></b></p>
				</blockquote></blockquote></blockquote></blockquote></blockquote>	
				<blockquote><blockquote><blockquote><blockquote><blockquote>';
					if (strpos($_SERVER['PHP_SELF'],'controller') != false){
						echo '<form id="searchform" method="post" action="c_cambiar_pass.php">';
					}else{
						echo '<form id="searchform" method="post" action="controller/c_cambiar_pass.php">';
					}
				
					echo '<fieldset>
						<legend>Cambio pass</legend>
						<p>Por favor, introduzca los datos</p>
						<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
						<label id="label2">
							<input type="password" name="antigua" tabindex="1" id="antigua"></input>Contrase&#241;a Anterior:
						</label>
						<label id="label2">
							<input type="password" name="pass" tabindex="2" id="pass"></input>Contrase&#241;a Nueva:
						</label>
						<label id="label2">
							<input type="password" name="pass2" tabindex="3" id="pass2"></input>Repetir Contrase&#241;a Nueva:
						</label>
						<label id="label2">
							<input name="Submit" type="submit" id="submit_cambio" tabindex="4" value="Aplicar"></input>
						</label>
					</fieldset>
					</form>
				</blockquote></blockquote></blockquote></blockquote></blockquote>';
			if (($_SESSION['level'] == 1) || ($_SESSION['level'] == 3)){
			echo '<br /><blockquote><blockquote><blockquote><blockquote><blockquote>
					<p align="left"><b><font size="4">CAMBIO DE PASSWORD DE OTRO USUARIO</font></b></p>
				</blockquote></blockquote></blockquote></blockquote></blockquote>
				
				<blockquote><blockquote><blockquote><blockquote><blockquote>';
					if (strpos($_SERVER['PHP_SELF'],'controller') != false){
						echo '<form id="searchform" method="post" action="c_cambiar_pass.php">';
					}else{
						echo '<form id="searchform" method="post" action="controller/c_cambiar_pass.php">';
					}
				echo '<fieldset>
						<legend>Cambio pass</legend>
						<p>Por favor, introduzca los datos</p>
						<input type="hidden" name="enviar_peticion" value="2" size="1"></input>
						<label id="label2">
							<select name="nick" tabindex="5" id="nick">';
							$usuarios = mysqli_query ($connection, "SELECT * FROM users ORDER BY nick"); //Sentencia para buscarlo en la base de datos
							while($row=mysqli_fetch_array($usuarios)){
								echo '<option value="'.$row[0].'">'.$row[0].'</option>';}
							echo '</select>DNI:
						</label>
						<label id="label2">
							<input type="password" name="pass" tabindex="6" id="pass"></input>Contrase&#241;a Nueva:
						</label>
						<label id="label2">
							<input type="password" name="pass2" tabindex="7" id="pass2"></input>Repetir Contrase&#241;a Nueva:
						</label>
										
						<label id="label2">
							<input name="Submit" type="submit" id="submit_cambio" tabindex="8" value="Aplicar"></input>
						</label>
					</fieldset>
					</form>
				</blockquote></blockquote></blockquote></blockquote></blockquote>
				<br /><blockquote><blockquote><blockquote><blockquote><blockquote>
					<p align="left"><b><font size="4">RESETEO DE PASSWORD</font></b></p>
				</blockquote></blockquote></blockquote></blockquote></blockquote>
			
				<blockquote><blockquote><blockquote><blockquote><blockquote>';
					if (strpos($_SERVER['PHP_SELF'],'controller') != false){
						echo '<form id="searchform" method="post" action="c_cambiar_pass.php">';
					}else{
						echo '<form id="searchform" method="post" action="controller/c_cambiar_pass.php">';
					}
					
					echo '<fieldset>
						<legend>Reseteo</legend>
						<p>Por favor, seleccione usuario</p>
						<input type="hidden" name="enviar_peticion" value="3" size="1"></input>
						<label id="label2">
							<select name="nick2" tabindex="9" id="nick2">';
							$usuarios = mysqli_query ($connection, "SELECT * FROM users ORDER BY nick"); //Sentencia para buscarlo en la base de datos
							while($row=mysqli_fetch_array($usuarios)){
								echo '<option value="'.$row[0].'">'.$row[0].'</option>';}
							echo '</select>DNI:
						</label>
						<label id="label2">
							<input name="submit2" type="submit" id="submit2" tabindex="10" value="Resetear"></input>
						</label>
					</fieldset>
					</form>
				</blockquote></blockquote></blockquote></blockquote></blockquote>';					
			}
		echo '<p>&nbsp;</p>
		</body>
		</html>';
				
	} else {
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysql_query("INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}


?>