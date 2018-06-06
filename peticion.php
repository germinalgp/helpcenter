<?php
	/**
	* PHP para realizar la peticion o creacion de una incidencia
	* Actualmente solo accesibles para level 9
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php');					// Incluimos "conexion.php" que contiene los datos de la conexion a la base de datos
	include ('menu.php');

	// ##### INICIO DE FUNCIONES (PASARLAS CUANDO ESTEN DEFINIDAS A UN PHP APARTE Y HACER UN INCLUDE)

	// ********************************** Funcion "boolean verify_email_format (string $email)" *********************************************
	// *																																	*
	// *   Esta funcion realiza una comprobacion del formato correcto de una direccion de e-mail.											*
	// *   Parametros: (1) Cadena de texto conteniendo la direccion de e-mail a verificar.													*
	// *   Devuelve: True/false dependiendo de si el e-mail es correcto/incorrecto.															*
	// *																																	*
	// **************************************************************************************************************************************
	
	/**
	* Funcion que realiza una comprobacion del formato correcto de una direccion de email
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


	// ##### INICIO DEL CUERPO DE CODIGO PHP



	if ($_SESSION['level'] == 9)
	{
		// Si no se ha enviado por "post" el formulario de peticion	

		if ( !isset ( $_POST['enviar_peticion']) || $_POST['enviar_peticion'] != 1)
		{
			// Inicio de ECHO multilinea: Generamos en HTML el formulario de peticion
			echo '<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER - CREAR INCIDENCIA</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					
					<link rel="stylesheet" type="text/css" href="src/calendario_peticion.css" />
					<script type="text/javascript" src="tinybox.js"></script>
					<script type="text/javascript" src="src/calendario_peticion.js"></script>
					
					<script type="text/javascript">
						function Valida (formulario){
							formulario.enviar_peticion.value=1;
							formulario.submit();
						}
					</script>
					
				</head>';
				
			$mensaje = "";
			$numero = "";		
			if ( isset ( $_GET['mensaje'] ) ){
				$mensaje = $_GET['mensaje'];
			}
			if ( isset ( $_GET['numero'] ) ){
				$numero = $_GET['numero'];
			}		
				
			
			
			if ($mensaje != ''){
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\'message.php?mensaje='.$mensaje.'\',width:320,height:240})">';
			}else{
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">';
			}
				
			echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
				
			menu_ext(0,1,0);

			$combo_on = "";
			if ( isset ( $_POST['combo_on'] ) ){
				$combo_on=$_POST['combo_on']; //RECOGEMOS EL TIPO DE INCIDENCIA SELECCIONADA
			}
			
			
			echo '<blockquote><blockquote>';
			if ($combo_on==""){
				echo '<blockquote><blockquote><blockquote><img border="0" src="images/step_one.jpg" alt="NO IMAGEN"></img></blockquote></blockquote></blockquote>	
				
					<form id="searchformPeticion" method="post" action="peticion.php">
					<fieldset>
					<legend>Nueva incidencia</legend>
					<p>Por favor, introduzca los siguientes datos:</p>
					<label id="label2" for="combo_on">
					<select class = "blanco" name="combo_on" tabindex="5" onChange="searchformPeticion.submit();">';
					$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE tipo <> 'CATEGORIA' AND tipo <> 'PRODUCTO' GROUP BY descripcion ORDER BY orden ASC"); //Sentencia para buscarlo en la base de datos
					echo '<option selected="selected">Elija una opci&#243;n:</option>';
					while($row_tabla_incidencias=mysqli_fetch_array($tabla_incidencias))
					{	
						echo '<option value="'.$row_tabla_incidencias[0].'">'.$row_tabla_incidencias[1].'</option>';
					}
				echo '</select>Tipo de incidencia: <b class="error">(*)</b></label></fieldset></form><b class="error">(*) Campo obligatorio</b>';
			
			}else if (($combo_on=="0001") || ($combo_on == "0002")) { //INCIDENCIA SOFTWARE O HARDWARE
				$combo_issue_type = $combo_on;
				
				echo '<blockquote><blockquote><blockquote><img border="0" src="images/step_two.jpg" alt="NO IMAGEN"></img></blockquote></blockquote></blockquote>
					
					<form id="searchformPeticion" method="post" action="peticion.php">
						<fieldset>
						<legend>Nueva incidencia</legend>
						<p>Por favor, introduzca los siguientes datos:</p>
						<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
						<input type="hidden" name="combo_issue_type" value="'.$combo_issue_type.'"></input>
						<input type="hidden" name="combo_on" value="0010"></input>
						<label id="label2" for="combo_issue_type">
							<select name="combo_issue_type" tabindex="5" id="combo_issue_type" disabled="disabled">';
							$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_combo = '".$combo_issue_type."'"); //Sentencia para buscarlo en la base de datos
							$row_tabla_incidencias = mysqli_fetch_array($tabla_incidencias); 
					echo '<option value="'.$row_tabla_incidencias[0].'" selected="selected">'.$row_tabla_incidencias[1].'</option>
						</select>Tipo de incidencia: <b class="error">(*)</b>
						</label>';
			
					//SELECCION DE CATEGORIA	
					echo '<label id="label2" for="combo_on">
							<select name="combo_category_type" tabindex="5" id="combo_category_type" onChange="searchformPeticion.submit();">';
							$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_padre = '".$combo_issue_type."' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
							echo '<option selected="selected">Elija una opci&#243;n:</option>';
							while($row_tabla_categorias=mysqli_fetch_array($tabla_categorias))
							{	
								echo '<option value="'.$row_tabla_categorias[0].'">'.$row_tabla_categorias[1].'</option>';
							}
						echo '</select>Categoria: <b class="error">(*)</b>
						</label></fieldset></form>';
						
			}else if ($combo_on == "0010"){ //CATEGORIA
				$combo_issue_type = $_POST['combo_issue_type'];
				$combo_category_type = $_POST['combo_category_type'];
				echo '<blockquote><blockquote><blockquote><img border="0" src="images/step_three.jpg" alt="NO IMAGEN"></img></blockquote></blockquote></blockquote>
					
					<form id="searchformPeticion" method="post" action="peticion.php">
						<fieldset>
						<legend>Nueva incidencia</legend>
						<p>Por favor, introduzca los siguientes datos:</p>
						<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
						<input type="hidden" name="combo_issue_type" value="'.$combo_issue_type.'"></input>
						<input type="hidden" name="combo_category_type" value="'.$combo_category_type.'"></input>
						<label id="label2" for="combo_issue_type">
							<select name="combo_issue_type" tabindex="5" id="combo_issue_type" disabled="disabled">';
							$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_combo = '".$combo_issue_type."'"); //Sentencia para buscarlo en la base de datos
							$row_tabla_incidencias = mysqli_fetch_array($tabla_incidencias);
					echo '<option value="'.$row_tabla_incidencias[0].'" selected="selected">'.$row_tabla_incidencias[1].'</option>
						</select>Tipo de incidencia: <b class="error">(*)</b>
						</label>
						<label id="label2" for="combo_category_type">
							<select name="combo_category_type" tabindex="5" id="combo_category_type" disabled="disabled">';
							$tabla_categorias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_combo = '".$combo_category_type."'"); //Sentencia para buscarlo en la base de datos
							$row_tabla_categorias = mysqli_fetch_array($tabla_categorias); 
						echo '<option value="'.$row_tabla_categorias[0].'" selected="selected">'.$row_tabla_categorias[1].'</option>	
							</select>Categoria: <b class="error">(*)</b>
						</label>';
						
					//SELECCION DE PRODUCTO	
					echo '<label id="label2" for="combo_product_type">
							<select name="combo_product_type" tabindex="5" id="combo_product_type">';
							$tabla_productos = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_padre = '".$combo_category_type."' ORDER BY descripcion ASC"); //Sentencia para buscarlo en la base de datos
							while($row_tabla_productos=mysqli_fetch_array($tabla_productos))
							{	
								echo '<option value="'.$row_tabla_productos[0].'">'.$row_tabla_productos[1].'</option>';
							}
						echo '</select>Categoria: <b class="error">(*)</b>
						</label>
						<label id="label2" for="n_serie">
								<input class = "blanco" type="text" name="n_serie" tabindex="8" id="n_serie" />N&#176; de Serie: <b class="error">(*)</b>
						</label>
						<label id="label2" for="fecha_compra">
						<input class = "blanco" id="fecha_compra" type="text" READONLY name="fecha_compra" title="YYYY-MM-DD"></input>
							<a href="javascript:displayCalendarFor(\'fecha_compra\');">
							<img class="calendario" src="images/calendario.gif" border="0" alt="NO IMAGEN"></img></a>
							Fecha: <b class="error">(*)</b>
						</label>
						<label id="label3" for="comentario">Breve descripcion del problema: <b class="error">(*)</b>
							<textarea class = "blanco" name="comentario" tabindex="9" id="comentario"></textarea>
						</label></fieldset></form>
						<form id="formSubmit" onClick="Valida(searchformPeticion);">
						<label id="label4" for="Submit">
							<input name="submit" type="button" id="submit" tabindex="10" value="Enviar"></input>
						</label></form>
						<b class="error">(*) Campo obligatorio</b>';
			}else if ($combo_on == "0006"){
				$combo_issue_type = $combo_on;
				
				echo '<blockquote><blockquote><blockquote><img border="0" src="images/step_two_fin.jpg" alt="NO IMAGEN"></img></blockquote></blockquote></blockquote>
					
					<form id="searchformPeticion" method="post" action="peticion.php">
						<fieldset>
						<legend>Nueva incidencia</legend>
						<p>Por favor, introduzca los siguientes datos:</p>
						<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
						<input type="hidden" name="combo_issue_type" value="'.$combo_issue_type.'"></input>
						<label id="label2" for="combo_issue_type">
							<select name="combo_issue_type" tabindex="5" id="combo_issue_type" disabled="disabled">';
							$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_combo = '".$combo_issue_type."'"); //Sentencia para buscarlo en la base de datos
							$row_tabla_incidencias = mysqli_fetch_array($tabla_incidencias); //Obtenemos el usuario en user_ok
					echo '<option value="'.$row_tabla_incidencias[0].'" selected="selected">'.$row_tabla_incidencias[1].'</option>
						</select>Tipo de incidencia: <b class="error">(*)</b>
						</label>
						<label id="label2" for="id_anterior">
							<input class = "blanco" type="text" name="id_anterior" tabindex="6" id="id_anterior"></input>ID anterior: <b class="error">(*)</b>
						</label>
						
					<label id="label3" for="comentario">Comentarios: <b class="error">(*)</b>
						<textarea class = "blanco" name="comentario" tabindex="6" id="comentario"></textarea>
					</label></fieldset></form>
					<form id="formSubmit" onClick="return Valida(searchformPeticion);">
					<label id="label4" for="Submit">
						<input name="submit" type="button" id="submit" tabindex="7" value="Enviar"></input>
					</label></form>
					<b class="error">(*) Campo obligatorio</b>';
			}else if (($combo_on == "0003")){
				$combo_issue_type = $combo_on;
				
				echo '<blockquote><blockquote><blockquote><img border="0" src="images/step_two_fin.jpg" alt="NO IMAGEN"></img></blockquote></blockquote></blockquote>
					
					<form id="searchformPeticion" method="post" action="peticion.php">
						<fieldset>
						<legend>Nueva incidencia</legend>
						<p>Por favor, introduzca los siguientes datos:</p>
						<input type="hidden" name="enviar_peticion" value="0" size="1"></input>
						<input type="hidden" name="combo_issue_type" value="'.$combo_issue_type.'"></input>
						<label id="label2" for="combo_issue_type">
							<select name="combo_issue_type" tabindex="5" id="combo_issue_type" disabled="disabled">';
							$tabla_incidencias = mysqli_query($connection, "SELECT id_combo, descripcion FROM tipos_combos WHERE id_combo = '".$combo_issue_type."'"); //Sentencia para buscarlo en la base de datos
							$row_tabla_incidencias = mysqli_fetch_array($tabla_incidencias); //Obtenemos el usuario en user_ok
					echo '<option value="'.$row_tabla_incidencias[0].'" selected="selected">'.$row_tabla_incidencias[1].'</option>
						</select>Tipo de incidencia: <b class="error">(*)</b>
						</label>
					<label id="label3" for="comentario">Comentarios: <b class="error">(*)</b>
						<textarea class = "blanco" name="comentario" tabindex="6" id="comentario"></textarea>
					</label></fieldset></form>
					<form id="formSubmit" onClick="return Valida(searchformPeticion);">
					<label id="label4" for="Submit">
						<input name="submit" type="button" id="submit" tabindex="7" value="Enviar"></input>
					</label></form>
					<b class="error">(*) Campo obligatorio</b>';
			}		
			echo '</blockquote></blockquote><p>&nbsp;</p>
			</body>
		</html>';

			
		}

		// Si se ha enviado por "post" el formulario de peticion, la analizamos y procesamos si los datos se introdujeron correctamente
		
		else
		{

			$error = 21;
			$header = "";
			
			$combo_issue_type = "";
			$combo_category_type = "";
			$combo_product_type = "";
			$post_fecha_compra = "";
			$post_id_anterior = "";
			$post_n_serie = "";
			$post_comentario = "";
			
			if ( isset ( $_POST['combo_issue_type'] ) ){
				$combo_issue_type = $_POST['combo_issue_type'];
			}
			if ( isset ( $_POST['combo_category_type'] ) ){
				$combo_category_type = $_POST['combo_category_type'];
			}
			if ( isset ( $_POST['combo_product_type'] ) ){
				$combo_product_type = $_POST['combo_product_type'];
			}
			if ( isset ( $_POST['fecha_compra'] ) ){
				$post_fecha_compra = $_POST['fecha_compra'];
			}
			if ( isset ( $_POST['id_anterior'] ) ){
				$post_id_anterior = $_POST['id_anterior']; //RECOGEMOS EL TIPO DE INCIDENCIA SELECCIONADA
			}
			if ( isset ( $_POST['n_serie'] ) ){
				$post_n_serie = $_POST['n_serie'];
			}
			if ( isset ( $_POST['comentario'] ) ){
				$post_comentario = $_POST['comentario'];
			}
			
		
			
			
			if (($combo_issue_type=="0001") || ($combo_issue_type=="0002")){ //SOFTWARE / HARDWARE
				if ($post_n_serie=="" || $post_fecha_compra=="" || $post_comentario==""){
					$error = 22; //Comprobacion de campos vacios
				}
			}else if ($combo_issue_type=="0006"){ //REITEROS
				
				if ($post_id_anterior == "" || $post_comentario==""){
					$error = 22;
				}else{
					//COMPROBACION DE QUE DICHA ID ESTE CERRADA o en TRAMITE y QUE EL TIEMPO SOLICITADO ES CORRECTO
					$verificacion = mysqli_query ($connection, "SELECT ISSUE_TYPE, TIME_TO_SEC(TIMEDIFF(NOW(),DATE)) FROM peticiones WHERE ID = $post_id_anterior AND USER_OPEN = '".$_SESSION['usuario']."'");
					$numrows=mysqli_num_rows($verificacion);
					if ($numrows == 0){
						$error = 29;
					}else{
						$fila_verificacion = mysqli_fetch_row ($verificacion);	 
						switch ($fila_verificacion[0]){
							case "0001": //SOFTWARE
								$combo_issue_type = "0006";
								if ($fila_verificacion[1]<345600){ //1 DIA
									$error = 27;
								} 
								break;
							case "0002": //HARDWARE
								$combo_issue_type = "0004";
								if ($fila_verificacion[1]<345600){ //4 DIAS
									$error = 27;
								}  
								break;
							case "0004": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$error = 28;
								break;
							case "0005": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$error = 28;
								break;
							case "0006": //REITERO--- NO SE PUEDEN REALIZAR REITEROS DE REITEROS (SIEMPRE REITERAR SOBRE EL ORIGEN)
								$error = 28;
								break;
							case "0003": //OTRA INCIDENCIA
								$combo_issue_type = "0005";
								if ($fila_verificacion[1]<604800){ //7 DIAS
									$error = 27;
								}  
								break;
						}
					}
					
					
				}
			}else if ($combo_issue_type=="0003"){
				
				if ($post_comentario == ""){
					$error = 22;
				}
			}

			

			// Si se produce error tipo 1 (no hay errores), se procesan los datos introducidos
			if ($error == 21)
			{
				// Insertamos en la BB.DD. la peticion de incidencia exitosa
				
				// Obtener la fecha de realizacion de la peticion de incidencia
				$fecha_peticion = getdate ();
				$fecha_peticion2 = $fecha_peticion['year']."-".$fecha_peticion['mon']."-".$fecha_peticion['mday']." ".$fecha_peticion['hours'].":".$fecha_peticion['minutes'].":".$fecha_peticion['seconds'];
				
				//OBTENER COMPETENCIA INICIAL
				$competencia = mysqli_query ($connection, "SELECT TIPO FROM tipos_combos WHERE ID_COMBO = '".$combo_issue_type."'");
				$row_competencia = mysqli_fetch_row ($competencia);		
				
				
				// Actualizar la tabla "peticiones" con los datos de la peticion
				mysqli_query ($connection, "INSERT INTO peticiones (user_open, telephone, email, issue_type, category_type, product_type, fecha_compra, id_anterior, n_serie, state, block, date, competencia)
							 values('".$_SESSION['usuario']."','".$_SESSION['telephone']."','".$_SESSION['email']."', '".$combo_issue_type."','".$combo_category_type."','".$combo_product_type."','".$post_fecha_compra."','".$post_id_anterior."','".$post_n_serie."','0','0', '".$fecha_peticion2."','".$row_competencia[0]."')");

				$last_issue = mysqli_query ($connection, "SELECT MAX(id) FROM peticiones");
				$row = mysqli_fetch_row ($last_issue);			
				
				// Actualizar la tabla "comentarios" con los datos de la peticion
				mysqli_query ($connection, "INSERT INTO comentarios (id_issue, author, comments, date) values ('".$row[0]."','".$_SESSION['usuario']."','".$post_comentario."','".$fecha_peticion2."')");
				
				$header = $header . "&numero=" . $row[0];
				Header("Location: index.php?mensaje=".$error."" . $header);
			}else{ 

			// El tipo de error se manda por POST mediante "mensaje" y "descripcion" para ser posteriomente devuelto por pantalla.
			// Asimismo, el numero de incidencia se manda por POST mediante "numero" para que el usuario lo utilice en futuras referencias
			$header = $header . "&numero=" . $row[0];
			Header("Location: peticion.php?mensaje=".$error."" . $header);
			}
		}
	}

	else
	{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}

?>
