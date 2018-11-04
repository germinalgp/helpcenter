<?php
	/**
	* PHP que representa la ventana del login para abrir mediante tinybox
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/

if ( $_GET['rutatiny'] == "1" ){
	$ruta = '../';
}	

echo '<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="'.$ruta.'styles.css" rel="stylesheet" type="text/css" />
		</head>
	    <body bgcolor = "#B9D6ED">
		<form id="loginform" method="post" action="'.$ruta.'controller/c_autenticar.php">
			<fieldset>
			<legend>Inicio de sesion</legend>
				<p>Por favor, introduzca nombre de usuario y contrase&#241;a parar entrar en Helpcenter</p>
				<label for="username">
					<input type="text" name="nick" tabindex="1" id="nick">Usuario:</input>
				</label>
				<label for="password">
					<input type="password" name="pass" tabindex="2" id="pass">Contrase&#241a:</input>
				</label>
				<label for="submit">
					<input name="Submit" type="submit" id="submit" tabindex="3" value="Log in"></input>
				</label>
			</fieldset>
		</form>
		</body>
		</html>';



?>