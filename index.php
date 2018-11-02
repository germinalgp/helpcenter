<?php
	/**
	* PHP que representa la pagina inicial tanto al realizar el login como antes de hacerlo.
	* Opcion accesible para todos los usuarios y cuando se ha realizado el login mostraria la pasarela con todas las incidencias que podemos
	* visualizar en funcion de pertenencias asi como las opciones del menu a las que podamos acceder por level.
	* @author Germinal GARRIDO PUYANA
	*/
	
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');


	if (!isset($_SESSION['usuario'])) //Comprobamos que no existe la session, es decir, que no se ha logueado y mostramos el form
	{
		//Creamos el form que ir a autentificar.php para comprobar los datos con la tabla users
		echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER</title>
					<link href="styles_ini.css" rel="stylesheet" type="text/css" />
					<link href="../styles.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="tinybox.js"></script>
					<script type="text/javascript" src="../tinybox.js"></script>
			</head>';

			if (strpos($_SERVER['PHP_SELF'],'controller') != false){
				$ruta = '../';
				$rutatiny = "1";
			}
			
			if (!is_null($mensaje) && empty($intrusion)){
				echo '<body link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\''.$ruta.'message.php?mensaje='.$mensaje.'\',width:320,height:210})">';
			}else{
				echo '<body link="#0000ff" vlink="#0000ff">';
			}
			

			echo'<blockquote>
			<table>
				<tr>
					<td><input onclick="TINY.box.show({url:\''.$ruta.'inicio.php?rutatiny='.$rutatiny.'\',width:320,height:210})" class = "botones" type="button" value = "Entrar"></input></td>
					<td><input onclick="TINY.box.show({url:\''.$ruta.'registrar.php?rutatiny='.$rutatiny.'\',width:350,height:340})" class = "botones" type="button" value = "Registrar"></input></td>
				<tr>
			</table>
			</blockquote>
			<p>&nbsp;</p>
			<br><br>
			<blockquote>
			
			<form id="noticeform" action="">
				<fieldset>
				<legend>&iquest;Qu&eacute; somos?</legend>
					<label class = "masNoticia1" for="masnoticia">
						Nuestra empresa ofrece soluciones para aquellas empresas
						que desean disponer de un sistema de gesti&oacute;n de incidencias
					</label>
				</fieldset>
			</form>
			<br>
			<form id="noticeform" action="">
				<fieldset>
				<legend>&iquest;C&oacute;mo trabajamos?</legend>
					<label class = "masNoticia2" for="masnoticia">
						Disponemos de un equipo de analistas que buscaran el entorno mas adecuado
						de gesti&oacute;n de incidencias que se adapte a sus necesidades.
						Nuestros programadores, bajo la supervisi&oacute;n de un analista jefe, implementaran
						la soluci&oacute;n propuesta al cliente, teniendo en cuenta todos los criterios de calidad
						establecidos, ofreciendo el PACK++ SECURITY para evitar intrusiones
					</label>
				</fieldset>
			</form>
			<br>
			<form id="noticeform" action="">
				<fieldset>
				<legend>Solicite SuTest 1.0</legend>
					<label class = "masNoticia3" for="masnoticia">
						Puede registrarse mediante el Bot&oacute;n Registro situado en la esquina superior izquierda, permiti&eacute;ndole
						acceder en modo cliente a un entorno de gesti&oacute;n de incidencias de ejemplo.<br>
						Para acceder en modo ADMIN deber&oacute; solicitar acceso poni&eacute;ndose en contacto mediante:<br><br>
							&nbsp;&nbsp;&nbsp;<b>Llamada Telef&oacute;nica:</b> XXX XX XX XX<br>
							&nbsp;&nbsp;&nbsp;<b>Email:</b> xxxxxx@xxx.com
					</label>
				</fieldset>
			</form>
			
			</blockquote>';
			

			
			$intentos = substr($intrusion, 2);
			
			if ($intentos == 1) 
					{
						echo '<h2><font size="2" color="red">Te quedan 2 intentos</font></h2>';
					}
			else if ($intentos == 2) 
					{
						echo '<h2><font size="2" color="red">Te queda 1 intento</font></h2>';
					}
			else if ($intentos > 2) 
					{
						echo '<h2><font size="2" color="red">La cuenta esta bloqueada, consulte con un administrador</font></h2>';
					}
				
			
			
			if ($intrusion == 1){
				$IP = $_SERVER['REMOTE_ADDR'];
				echo '<font size="2" color="red">Intento de intrusi&#243n sin logueo desde la IP <b>'.$IP.'</b> ha sido grabada</font>';
			}else if ($intrusion == 98){
				echo '<font size="2" color="red">No ha introducido usuario y contrase&#241;a</font>';
			}else if ($intrusion == 97){
				echo '<font size="2" color="red">Usuario introducido no v&#225;lido</font>';
			}else if ($intrusion == 96){
				$IP = $_SERVER['REMOTE_ADDR'];
				echo '<font size="2" color="red">No existe el usuario...la IP <b>'.$IP.'</b> ha sido grabada</font>';
			}else if ($intrusion == 94){
				$IP = $_SERVER['REMOTE_ADDR'];
				echo '<font size="2" color="red">CUENTA BLOQUEADA...la IP <b>'.$IP.'</b> ha sido grabada</font>';
			}
			
			echo '</body>
			</html>';
	}
	else
	{
		if ($_SESSION['level'] != 9){
			header('refresh:60');
		}
		echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<link href="../styles.css" rel="stylesheet" type="text/css" /> 	 					
					<script type="text/javascript" src="tinybox.js"></script>
					<script type="text/javascript" src="../tinybox.js"></script>
					<script type="text/javascript" language="JavaScript">
						function js_ordenar(num_orden){
							switch (num_orden){
								case 1 : document.ordenform.orden.value="ORDER BY p.ID ASC";
								break;
								case 2 : document.ordenform.orden.value="ORDER BY p.ID DESC";
								break;
								case 3 : document.ordenform.orden.value="ORDER BY p.USER_OPEN ASC";
								break;
								case 4 : document.ordenform.orden.value="ORDER BY p.USER_OPEN DESC";
								break;
								case 5 : document.ordenform.orden.value="ORDER BY c.DESCRIPCION ASC";
								break;
								case 6 : document.ordenform.orden.value="ORDER BY c.DESCRIPCION DESC";
								break;
								case 7 : document.ordenform.orden.value="ORDER BY p.LAST_USER_MODIFY ASC";
								break;
								case 8 : document.ordenform.orden.value="ORDER BY p.LAST_USER_MODIFY DESC";
								break;
								case 9 : document.ordenform.orden.value="ORDER BY PLAZO ASC";
								break;
								case 10 : document.ordenform.orden.value="ORDER BY PLAZO DESC";
								break;
							}
							
							document.ordenform.submit();
						}
					</script>
					
			 </head>';

			if (strpos($_SERVER['PHP_SELF'],'controller') != false){
				$ruta = '../';
			}
		
			$id_mensaje = substr($mensaje,0,2);
			$numero = substr($mensaje,2);
			
			if ($mensaje != ''){
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff" onload="TINY.box.show({url:\''.$ruta.'message.php?mensaje='.$id_mensaje.'&numero='.$numero.'\',width:320,height:240})">';
			}else{
				echo '<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">';
			}
				
			echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			
			  //Si se ha logueado, mostramos el nick y la opcion de desloguearse
			  //Este sera el menu que saldria a la gente que esta logueada, se puede modificar y aadir cosas

		
		
		$colorFila="filaBlanca";
		
		if ($_SESSION['level'] == 9){   
			
			menu_ext(1,0,0);
			
			
			$sql_cerradas="SELECT p.ID, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos c WHERE USER_OPEN = ".$_SESSION['usuario']." AND p.STATE = 2 AND p.ISSUE_TYPE = c.ID_COMBO ORDER BY p.DATE DESC LIMIT 20";
			$sql_abiertas="SELECT p.ID, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos c WHERE USER_OPEN = ".$_SESSION['usuario']." AND (p.STATE = 0 OR p.STATE = 1) AND p.ISSUE_TYPE = c.ID_COMBO ORDER BY p.DATE DESC";
			$res_cerradas=mysqli_query($connection, $sql_cerradas);
			$res_abiertas=mysqli_query($connection, $sql_abiertas);
			//////////////////////INCIDENCIAS ABIERTAS/////////////////////
			echo '<table class="borde" cellpading="1" cellspacing="0" width="1000">
					<tr>
						<td align="center" colspan="5"><font face="Arial Black" size="4">INCIDENCIAS ABIERTAS Y EN TRAMITE</font></td>
					</tr>
					<tr>
						<td style = "filter:alpha(opacity=100)" align="center"><b><font face="Arial">ID</font></b></td>
						<td align="center"><b><font face="Arial">TIPO INCIDENCIA</font></b></td>
						<td align="center"><b><font face="Arial">COMPETENCIA</font></b></td>
						<td align="center"><b><font face="Arial">FECHA</font></b></td>										
						<td align="center"><b></b></td>
					</tr>';
					while ($datos=mysqli_fetch_array($res_abiertas))
						{	
							echo '<tr class="'.$colorFila.'">
								<td align="center">'.$datos["ID"].'</td>
								<td align="center">'.$datos["DESCRIPCION"].'</td>
								<td align="center">'.$datos["COMPETENCIA"].'</td>		
								<td align="center">'.$datos["DATE"].'</td>
								<td align="center">';
									switch ($datos["STATE"]){
										case 0:	echo '<img title="INCIDENCIA ABIERTA" height="25" src="images/abierta_ico.gif" alt="NO IMAGEN"></img>';
												break;
										case 1: echo '<img title="INCIDENCIA EN TRAMITE" height="25" src="images/tramite_ico.gif" alt="NO IMAGEN"></img>';
												break;
									}
								echo '</td>								
								<td align="center" valign="middle">									
									<input type="hidden" name="ID" value="'.$datos["ID"].'"></input>
									<input name="Submit" type="submit" id="submit" tabindex="13" value="ABRIR..."></input>											
								</td></tr></form>';							  
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
					echo '</table><p>&nbsp;</p>';
					
					
			//////////////////////INCIDENCIAS CERRADAS/////////////////////
			echo '<table class="borde" cellpading="1" cellspacing="0" width="1000">
					<tr>
						<td align="center" colspan="7"><font face="Arial Black" size="4">INCIDENCIAS CERRADAS </font><font size="4">(ULTIMAS 20)</font></td>
					</tr>
					<tr>
						<td align="center"><b><font face="Arial">ID</font></b></td>
						<td align="center"><b><font face="Arial">TIPO INCIDENCIA</font></b></td>
						<td align="center"><b><font face="Arial">COMPETENCIA</font></b></td>
						<td align="center"><b><font face="Arial">FECHA</font></b></td>												
						<td align="center"><b></b></td>
					</tr>';
					while ($datos=mysqli_fetch_array($res_cerradas))
						{	
							echo '
							<form id="searchform" method="post" action="respuesta.php">
							<tr class="'.$colorFila.'">
								<td align="center">'.$datos["ID"].'</td>
								<td align="center">'.$datos["DESCRIPCION"].'</td>
								<td align="center">'.$datos["COMPETENCIA"].'</td>		
								<td align="center">'.$datos["DATE"].'</td>
								<td align="center"><img title="INCIDENCIA CERRADA" height="25" src="images/cerrada_ico.gif" alt="NO FOTO"></img></td>					
								<td align="center" valign="middle">									
									<input type="hidden" name="ID" value="'.$datos["ID"].'"></input>
									<input name="Submit" type="submit" id="submit" tabindex="13" value="ABRIR..."></input>										
								</td></tr></form>';							  
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
					echo '</table><br/><a href="busqueda.php" title="BUSCAR"><img border="0" src="images/ver_mas.gif" alt="NO IMAGEN"></img></a>';
					
		}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){   
			//POR SI ES NECESARIO DESBLOQUEAR
			
			if ($_SESSION['block'] > 0){
				mysqli_query($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = $_SESSION[block]"); //DESBLOQUEAMOS
				$_SESSION['block'] = 0;
			}
			
			menu_int(1,0,0,0,0,0);
			
			//OBTENER FILTRO
			$filtro = mysqli_query($connection, "SELECT f_coordinacion, f_desarrollo, f_tecnico FROM users WHERE nick = ".$_SESSION['usuario']."");
			$res_filtro = mysqli_fetch_row($filtro); 
			$f_coordinacion = $res_filtro[0];
			$f_desarrollo = $res_filtro[1];
			$f_tecnico = $res_filtro[2];
			
			
			if ($f_coordinacion == 1 && $f_desarrollo == 1 && $f_tecnico == 1){
				$sql_filtro = "(p.COMPETENCIA = 'COORDINACION' OR p.COMPETENCIA = 'DESARROLLO' OR p.COMPETENCIA = 'TECNICA')";
			}else if ($f_coordinacion == 1 && $f_desarrollo == 1 && $f_tecnico == 0){
				$sql_filtro = "(p.COMPETENCIA = 'COORDINACION' OR p.COMPETENCIA = 'DESARROLLO')";
			}else if ($f_coordinacion == 1 && $f_desarrollo == 0 && $f_tecnico == 1){
				$sql_filtro = "(p.COMPETENCIA = 'COORDINACION' OR p.COMPETENCIA = 'TECNICA')";
			}else if ($f_coordinacion == 1 && $f_desarrollo == 0 && $f_tecnico == 0){
				$sql_filtro = "(p.COMPETENCIA = 'COORDINACION')";
			}else if ($f_coordinacion == 0 && $f_desarrollo == 1 && $f_tecnico == 1){
				$sql_filtro = "(p.COMPETENCIA = 'DESARROLLO' OR p.COMPETENCIA = 'TECNICA')";
			}else if ($f_coordinacion == 0 && $f_desarrollo == 1 && $f_tecnico == 0){
				$sql_filtro = "(p.COMPETENCIA = 'DESARROLLO')";
			}else if ($f_coordinacion == 0 && $f_desarrollo == 0 && $f_tecnico == 1){
				$sql_filtro = "(p.COMPETENCIA = 'TECNICA')";
			}else if ($f_coordinacion == 0 && $f_desarrollo == 0 && $f_tecnico == 0){
				$sql_filtro = "(p.COMPETENCIA = 'NINGUNO')";
			}
			
			$orden = "";
			if ( isset ( $_POST['orden'] )){
				$orden = $_POST['orden'];
			}
			if ($orden == ""){
				$sql_cerradas="SELECT TIME_TO_SEC(TIMEDIFF(NOW(),p.DATE)) AS PLAZO, p.ID, p.USER_OPEN, p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE, p.LAST_USER_MODIFY, p.LAST_DATE_MODIFY FROM peticiones p, tipos_combos c WHERE p.STATE = 2 AND ".$sql_filtro." AND p.ISSUE_TYPE = c.ID_COMBO ORDER BY p.DATE DESC LIMIT 20";
				$sql_abiertas="SELECT TIME_TO_SEC(TIMEDIFF(NOW(),p.DATE)) AS PLAZO, p.ID, p.USER_OPEN, p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE, p.LAST_USER_MODIFY, p.LAST_DATE_MODIFY, p.TRACING FROM peticiones p, tipos_combos c WHERE (p.STATE = 0 OR p.STATE = 1) AND ".$sql_filtro." AND p.ISSUE_TYPE = c.ID_COMBO ORDER BY p.DATE DESC";
			}else{
				$sql_cerradas="SELECT TIME_TO_SEC(TIMEDIFF(NOW(),p.DATE)) AS PLAZO, p.ID, p.USER_OPEN, p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE, p.LAST_USER_MODIFY, p.LAST_DATE_MODIFY FROM peticiones p, tipos_combos c WHERE p.STATE = 2 AND ".$sql_filtro." AND p.ISSUE_TYPE = c.ID_COMBO ".$orden.", p.DATE DESC LIMIT 20";
				$sql_abiertas="SELECT TIME_TO_SEC(TIMEDIFF(NOW(),p.DATE)) AS PLAZO, p.ID, p.USER_OPEN, p.ISSUE_TYPE, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE, p.LAST_USER_MODIFY, p.LAST_DATE_MODIFY, p.TRACING FROM peticiones p, tipos_combos c WHERE (p.STATE = 0 OR p.STATE = 1) AND ".$sql_filtro." AND p.ISSUE_TYPE = c.ID_COMBO ".$orden.", p.DATE DESC";
			}
			$res_cerradas=mysqli_query($connection, $sql_cerradas);
			$res_abiertas=mysqli_query($connection, $sql_abiertas);
			
			if ($model==1) $ruta="../";
			
			//////////////////////INCIDENCIAS ABIERTAS/////////////////////
			
			echo '<table class="borde" cellpading="1" cellspacing="0" width="1000">
					<tr>
						<td align="center" colspan="9"><font face="Arial Black" size="4">INCIDENCIAS ABIERTAS Y EN TRAMITE</font></td>
					</tr>
					
					<form name="ordenform" method="post" action="'.$ruta.'index.php">
					<input type="hidden" name="orden" size="1"></input>
					<tr>
						<td width="50" align="center">
							<b><font face="Arial">ID</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(1)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(2)"></input>
						</td>
						<td width="60" align="center">
							<b><font face="Arial">C.P.</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(3)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(4)"></input>
						</td>
						<td width="290" align="center">
							<b><font face="Arial">TIPO INCIDENCIA</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(5)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(6)"></input>
						</td>
						<td width="120" align="center"><b><font face="Arial">COMPETENCIA</font></b></td>
						<td width="130" align="center"><b><font face="Arial">FECHA</font></b></td>	
						<td width="170" align="center">
							<b><font face="Arial">Ult.modificacion</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(7)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(8)"></input>
						</td>							
						<td width="40" align="center"><b></b></td>
						<td width="50" align="center"><b></b></td>
						<td width="60" align="center">
							<img src="'.$ruta.'images/process_warning.png" alt="NO IMAGEN"></img>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(9)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(10)"></input>
						</td>
					</tr>
					</form>';
					while ($datos=mysqli_fetch_array($res_abiertas))
						{	
							echo '
							<form id="searchform" method="post" action="'.$ruta.'respuesta.php">';
							if ($datos["TRACING"]==1){
								echo '<tr class="filaTracing">';
							}else {
								echo '<tr class="'.$colorFila.'">';
							}
							echo'<td align="center">'.$datos["ID"].'</td>
								<td align="center">'.$datos["USER_OPEN"].'</td>
								<td align="center">'.$datos["DESCRIPCION"].'</td>
								<td align="center">'.$datos["COMPETENCIA"].'</td>		
								<td align="center">'.$datos["DATE"].'</td>';
								if ($datos["LAST_USER_MODIFY"] == ""){
									echo '<td class="celda" align="center">No modificado</td>';
								}else{
									echo '<td class="celda" align="center">'.$datos["LAST_USER_MODIFY"].', '.$datos["LAST_DATE_MODIFY"].'</td>';
								}
								echo '<td align="center">';
									switch ($datos["STATE"]){
										case 0:	echo '<img title="INCIDENCIA ABIERTA" height="25" src="'.$ruta.'images/abierta_ico.gif" alt="NO IMAGEN"></img>';
												break;
										case 1: echo '<img title="INCIDENCIA EN TRAMITE" height="25" src="'.$ruta.'images/tramite_ico.gif" alt="NO IMAGEN"></img>';
												break;
									}
								echo '</td>
								<td align="center" valign="middle">									
									<input type="hidden" name="ID" value="'.$datos["ID"].'"></input>'; //PARA ENVIAR AL ID
																				
									if ($datos["BLOCK"] == 0){				
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="ABRIR..."></input>';
									}
									else {
										
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="Bloqueado" disabled = "disabled"></input>';
										if (strpos($_SERVER['PHP_SELF'],'controller') != false){
												echo '<a href='.$ruta.'c_unlock.php?ID='.$datos["ID"].'><img border="0" src="'.$ruta.'images/candado.gif" width="20" height="20" alt="NO IMAGEN"></img></a>';
										}else{
												echo '<a href='.$ruta.'controller/c_unlock.php?ID='.$datos["ID"].'><img border="0" src="'.$ruta.'images/candado.gif" width="20" height="20" alt="NO IMAGEN"></img></a>';
										}
											
									}
										
							echo '</td>
							<td align="center">';
							if ($datos["PLAZO"]<345600){ //4 DIA
								echo '<img border="0" src="'.$ruta.'images/plazo_verde.gif" width="20" title="EN PLAZO" alt="NO IMAGEN"></img>';
							}else if ($datos["PLAZO"]<604800){//7 DIAS
								echo '<img border="0" src="'.$ruta.'images/plazo_amarillo.gif" width="20" title="PLAZO SUPERADO" alt="NO IMAGEN"></img>';
							}else {
								echo '<img border="0" src="'.$ruta.'images/plazo_rojo.gif" width="20" title="DEMORA EXCESIVA" alt="NO IMAGEN"></img>';
							} 
							
							echo '</td>
							</tr></form>';							  
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
					echo '</table><p>&nbsp;</p>';
					
					
			//////////////////////INCIDENCIAS CERRADAS/////////////////////
			echo '<table class="borde" cellpading="1" cellspacing="0" width="1000">
					<tr>
						<td align="center" colspan="9"><font face="Arial Black" size="4">INCIDENCIAS CERRADAS </font><font size="4">(ULTIMAS 20)</font></td>
					</tr>
					<tr>
						<td width="50" align="center">
							<b><font face="Arial">ID</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(1)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(2)"></input>
						</td>
						<td width="100" align="center">
							<b><font face="Arial">C.P.</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(3)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(4)"></input>
						</td>
						<td width="300" align="center">
							<b><font face="Arial">TIPO INCIDENCIA</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(5)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(6)"></input>
						</td>
						<td width="120" align="center"><b><font face="Arial">COMPETENCIA</font></b></td>
						<td width="130" align="center"><b><font face="Arial">FECHA</font></b></td>
						<td width="170" align="center">
							<b><font face="Arial">Ult.modificacion</font></b>
							<input type="image" src="'.$ruta.'images/flecha_arriba.jpg" title="ORDENAR" onclick="js_ordenar(7)"></input>
							<input type="image" src="'.$ruta.'images/flecha_abajo.jpg" title="ORDENAR" onclick="js_ordenar(8)"></input>
						</td>																					
						<td align="center"><b></b></td>
					</tr>';
					while ($datos=mysqli_fetch_array($res_cerradas))
						{	
							echo '
							<form id="searchform" method="post" action="'.$ruta.'respuesta.php">
							<tr class="'.$colorFila.'">
								<td align="center">'.$datos["ID"].'</td>
								<td align="center">'.$datos["USER_OPEN"].'</td>
								<td align="center">'.$datos["DESCRIPCION"].'</td>
								<td align="center">'.$datos["COMPETENCIA"].'</td>		
								<td align="center">'.$datos["DATE"].'</td>
								<td class="celda" align="center">'.$datos["LAST_USER_MODIFY"].', '.$datos["LAST_DATE_MODIFY"].'</td>
								<td align="center"><img title="INCIDENCIA CERRADA" height="25" src="'.$ruta.'images/cerrada_ico.gif" alt="NO FOTO"></img></td>						
								<td align="center" valign="middle">									
									<input type="hidden" name="ID" value="'.$datos["ID"].'"></input>'; //PARA ENVIAR AL ID
																				
									if ($datos["BLOCK"] == 0){				
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="ABRIR..." />';
									}
									else {
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="Bloqueado" disabled = "disabled"></input>
											<a href='.$ruta.'unlock.php?ID='.$datos["ID"].'><img border="0" src="'.$ruta.'images/candado.gif" width="20" height="20" alt="NO IMAGEN"></img></a>';
									}
										
							echo '</td></tr></form>';							  
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
					echo '</table><br /><a href="'.$ruta.'busqueda.php" title="BUSCAR"><img border="0" src="'.$ruta.'images/ver_mas.gif" alt="NO FOTO"></img></a>
					
				
					<blockquote><blockquote><blockquote><blockquote><blockquote><blockquote><blockquote>';
					
					
					if (strpos($_SERVER['PHP_SELF'],'controller') != false){
						echo '<form id="filtro" method="post" action="c_filtrar.php">';
					}else{
						echo '<form id="filtro" method="post" action="controller/c_filtrar.php">';
					}
					
					echo'<label>';
					if ($f_coordinacion == 1){
						echo '<input type="checkbox" name="f_coordinacion" value="1" checked = "checked"></input>Coordinacion';
					}else{
						echo '<input type="checkbox" name="f_coordinacion" value="1"></input>Coordinacion';
					}
					echo '</label>
					<label>';
					if ($f_desarrollo == 1){
						echo '<input type="checkbox" name="f_desarrollo" value="1" checked = "checked"></input>Desarrollo';
					}else{
						echo '<input type="checkbox" name="f_desarrollo" value="1"></input>Desarrollo';
					}
					echo '</label>
					<label>';
					if ($f_tecnico == 1){
						echo '<input type="checkbox" name="f_tecnico" value="1" checked = "checked"></input>Tecnico';
					}else{
						echo '<input type="checkbox" name="f_tecnico" value="1"></input>Tecnico';
					}
					echo '</label>
					<label><br/>
					<input name="submit_filtro" type="submit" value="FILTRAR..."></input>
					</label>
					</form>
					</blockquote></blockquote></blockquote></blockquote></blockquote></blockquote></blockquote>';
						
		}
		echo '</body></html>';
	}
?>
