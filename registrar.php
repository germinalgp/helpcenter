<?php
	/**
	* PHP para realizar el registro de un usuario para que haga uso de la aplicacion
	* @author Germinal GARRIDO PUYANA
	*/
	
	if ( $_GET['rutatiny'] == "1" ){
				$ruta = '../';
	}	

	
	echo '<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<link href="styles.css" rel="stylesheet" type="text/css" />
			</head>
			<body bgcolor = "#B9D6ED">
			<form id="registrarform" method="post" action="'.$ruta.'controller/c_registrar.php">
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
	


?>
