<?php
	/**
	* PHP para realizar busquedas de incidencias
	* Opcion accesible para todos los usuarios
	* @author Germinal GARRIDO PUYANA
	*/

	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include ('menu.php');

	if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2 || $_SESSION['level'] == 3 || $_SESSION['level'] == 4 || $_SESSION['level'] == 9) {
	echo '<html>
			<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>INCIDENCIAS</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="src/js/jscal2.js"></script>
					<script type="text/javascript" src="src/js/lang/en.js"></script>
					<link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
					<link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />
					<link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />
			 </head>
			  
			  <body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
		
		$colorFila="filaBlanca";

	if ( !isset ( $_POST['enviar_peticion']) || $_POST['enviar_peticion'] != 1){
		
		if ($_SESSION['level'] == 9){
			menu_ext(0, 0, 0);
		}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ 
			menu_int(0, 0, 0, 0, 0, 0);
		}
		
		echo '<blockquote><blockquote><blockquote><blockquote><blockquote>
				<form id="searchformPeticion" method="post" action="busqueda.php">
				<fieldset>
				<legend>Busqueda de incidencias</legend>
					<p>Por favor, seleccione campos</p>
					<input type="hidden" name="enviar_peticion" value="1" size="1"></input>';
					if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){
						echo '<label id="label2" for="user_close">
							<input type="text" name="user_close" tabindex="1" id="cp_close"></input>Empleado:
						</label>
						<label id="label2" for="user_open">
							<input type="text" name="user_open" tabindex="1" id="user_open"></input>Usuario:
						</label>';
					}
					echo '<label id="label2" for="n_serie">
							<input type="text" name="n_serie" tabindex="8" id="n_serie"></input>N&#176; de Serie:
						</label>
						<label id="label2" for="fechainicial">
							<input type="text" name="fechainicial" tabindex="9" id="fechainicial" readonly = "readonly"></input>Fecha inicial: <i>(usar calendario)</i>
						</label>
						<label id="label2" for="fechafinal">
							<input type="text" name="fechafinal" tabindex="10" id="fechafinal" readonly = "readonly"></input>Fecha final: <i>(usar calendario)</i>
						</label>
						<label id="label2" for="submit">
							<input name="Submit" type="submit" id="submit" tabindex="11" value="Buscar"></input>
						</label>
						<br/>
							<div id="cont"></div>
							<div id="info" style="text-align: center; margin-top: 1em;">Selecciona fecha inicial</div>
									
							<script type="text/javascript">//<![CDATA[
								var SELECTED_RANGE = null;
								function getSelectionHandler() {
									var startDate = null;
									var ignoreEvent = false;
									return function(cal) {
										var selectionObject = cal.selection;
										if (ignoreEvent)
											return;

										var selectedDate = selectionObject.get();
										if (startDate == null) {
											startDate = selectedDate;
											document.getElementById("fechainicial").value = selectionObject.print("%Y-%m-%d");
											SELECTED_RANGE = null;
											document.getElementById("info").innerHTML = "Selecciona fecha final";
											cal.args.min = Calendar.intToDate(selectedDate);
											cal.refresh();
										} else {
											ignoreEvent = true;
											document.getElementById("fechafinal").value = selectionObject.print("%Y-%m-%d");
											selectionObject.selectRange(startDate, selectedDate);
											ignoreEvent = false;
											SELECTED_RANGE = selectionObject.sel[0];
											startDate = null;
											document.getElementById("info").innerHTML = selectionObject.print("%Y-%m-%d") + "<br />Selecciona nueva fecha inicial";
											cal.args.min = null;
											cal.refresh();
										}
									};
								};

								Calendar.setup({
									cont          : "cont",
									fdow          : 1,
									selectionType : Calendar.SEL_SINGLE,
									onSelect      : getSelectionHandler()
								});

							//]]></script>
						</fieldset>
					</form>
			</blockquote></blockquote></blockquote></blockquote></blockquote>';

		echo '</body></html>';
	}else{ //SI SE MANDA UN COMENTARIO DESDE LA MISMA PAGINA busqueda.php
		if ($_SESSION['level'] == 9){
			menu_ext(0, 0, 0);
			
		}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ 
			menu_int(0, 0, 0, 0, 0, 0);
		}
		
		$user_open = "";
		$user_close = "";
		$n_serie = "";
		$fechainicial = "";
		$fechafinal = "";
		
		//MODIFICAMOS LAS VARIABLES $_POST PARA CREAR NUESTRA CONSULTA
		if ($_POST['user_open']!=""){
			$user_open = " AND USER_OPEN LIKE '".$_POST['user_open']."'";
		}
		if ($_POST['n_serie']!=""){
			$n_serie = " AND N_SERIE LIKE '".$_POST['n_serie']."'";
		}
		if ($_POST['fechainicial']!=""){
			$fechainicial = " AND DATE >= '".$_POST['fechainicial']." 00:00:00'";
		}
		if ($_POST['fechafinal']!=""){
			$fechafinal = " AND DATE <= '".$_POST['fechafinal']." 23:59:59'";
		}
		
		if ($_SESSION['level'] == 9){
			//SI NO ES ADMINISTRADOR SOLO PUEDE VER LAS INCIDENCIAS SUYAS
			$sql_busqueda="SELECT DISTINCT p.ID,p.USER_OPEN, c.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos c WHERE p.USER_OPEN = ".$_SESSION['usuario']." AND p.ISSUE_TYPE = c.ID_COMBO".$user_open.$n_serie.$fechainicial.$fechafinal." ORDER BY p.DATE";	
		}else if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){ 
			//SI ES USUARIO ADMINISTRADOR PUEDE VER TODAS LAS INCIDENCIAS DE TODOS LOS USUARIOS
			if ($_POST['user_close']!=""){
				$sql_busqueda="(SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d, comentarios c WHERE p.ISSUE_TYPE = d.ID_COMBO AND p.ID = c.ID_ISSUE".$user_open.$n_serie.$fechainicial.$fechafinal." AND c.AUTHOR LIKE '".$_POST['user_close']."' ORDER BY p.DATE)
				UNION (SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d, historial h WHERE p.ISSUE_TYPE = d.ID_COMBO AND p.ID = h.ID_ISSUE".$user_open.$n_serie.$fechainicial.$fechafinal." AND h.AUTHOR LIKE '".$_POST['user_close']."' ORDER BY p.DATE)
				";
			}else{
				$sql_busqueda="SELECT DISTINCT p.ID,p.USER_OPEN, d.DESCRIPCION, p.COMPETENCIA, p.STATE, p.BLOCK, p.DATE FROM peticiones p, tipos_combos d WHERE p.ISSUE_TYPE = d.ID_COMBO".$user_open.$n_serie.$fechainicial.$fechafinal." ORDER BY p.DATE";
			}
		}
		
		$res_busqueda=mysqli_query($connection, $sql_busqueda);
		$numrows=@mysqli_num_rows($res_busqueda);
		if ($numrows == 0){ //SI NO TENEMOS RESULTADOS
			echo 'NO HAY RESULTADOS';
		}else{
			echo '<table class="borde" cellpading="1" cellspacing="0" width="1000">
					<tr>
						<td align="center" colspan="7"><font face="Arial Black" size="4">INCIDENCIAS ENCONTRADAS</font></td>
					</tr>
					<tr>
						<td align="center"><b><font face="Arial">ID</font></b></td>';
						if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){
							echo '<td align="center"><b><font face="Arial">USUARIO</font></b></td>';
						}
						echo'<td align="center"><b><font face="Arial">TIPO INCIDENCIA</font></b></td>
						<td align="center"><b><font face="Arial">COMPETENCIA</font></b></td>
						<td align="center"><b><font face="Arial">FECHA</font></b></td>										
						<td align="center"><b></b></td>
					</tr>';
					while ($datos=mysqli_fetch_array($res_busqueda))
						{	
							echo '
							<form id="searchform" method="post" action="respuesta.php">
							<tr class="'.$colorFila.'">
								<td align="center">'.$datos["ID"].'</td>';
								if ($_SESSION['level'] == 4 || $_SESSION['level'] == 3 || $_SESSION['level'] == 2 || $_SESSION['level'] == 1){
									echo '<td align="center">'.$datos["USER_OPEN"].'</td>';
								}
								echo '<td align="center">'.$datos["DESCRIPCION"].'</td>
								<td align="center">'.$datos["COMPETENCIA"].'</td>		
								<td align="center">'.$datos["DATE"].'</td>
								<td align="center">';
									switch ($datos["STATE"]){
										case 0:	echo '<img title="INCIDENCIA ABIERTA" height="25" src="images/abierta_ico.gif" alt="NO IMAGEN"></img>';
												break;
										case 1: echo '<img title="INCIDENCIA EN TRAMITE" height="25" src="images/tramite_ico.gif" alt="NO IMAGEN"></img>';
												break;
										case 2: echo '<img title="INCIDENCIA CERRADA" height="25" src="images/cerrada_ico.gif" alt="NO IMAGEN"></img>';
												break;	
									}
								echo '</td>							
								<td align="center" valign="center">									
									<input type="hidden" name="ID" value="'.$datos["ID"].'"></input>'; //PARA ENVIAR AL ID
																				
									if ($datos["BLOCK"] == 0){				
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="ABRIR..."></input>';
									}
									else {
										echo '<input name="Submit" type="submit" id="submit" tabindex="13" value="Bloqueado" disabled = "disabled"></input>
											<a href=unlock.php?ID='.$datos["ID"].'><img border="0" src="images/candado.gif" width="20" height="20" alt="NO IMAGEN"></img></a>';
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
					echo '</table>';
			}
			echo '</body>
			</html>';	
	}
	}else{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}

?>