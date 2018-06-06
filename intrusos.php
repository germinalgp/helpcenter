<?php
	/**
	* PHP para visualizar los intentos de intrusion en la aplicacion que se catalogan en tipos
	* Opcion accesible para ciertos usuarios
	* @author Germinal GARRIDO PUYANA
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	include('menu.php');

	//intrusiones.php, v1.1
	//script que muestra los intentos de intrusion al sistema que no son los habituales.
	//muestra intentos por nick,pass incorrectos y tambien accesos directos a paginas a las que no se tiene permiso

	if ($_SESSION['level'] == 1) //SOLO PUEDEN ACCEDER EL PERSONAL CON NIVEL 1
	{
		echo '<html>
			<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
					<title>HELPCENTER - INTRUSOS</title>
					<link href="styles.css" rel="stylesheet" type="text/css" />
					<script type="text/javascript" src="src/js/jscal2.js"></script>
					<script type="text/javascript" src="src/js/lang/en.js"></script>
					<link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
					<link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />
					<link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />
			 </head>


			  <body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
			 
			<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
		//POR SI ES NECESARIO DESBLOQUEAR
			
		if ($_SESSION['block'] > 0){
			mysqli_query ($connection, "UPDATE peticiones SET BLOCK = 0 WHERE ID = ".$_SESSION['block'].""); //DESBLOQUEAMOS
			$_SESSION['block'] = 0;
		}
		menu_int(0,0,0,0,0,1);

		if ( !isset ( $_POST['enviar_peticion']) || $_POST['enviar_peticion'] != 1){
			echo '<table cellpading="1" cellspacing="0" width="600">
				<tr>
					<td>
						<form id="formMasShort" method="post" action="intrusos.php">
						<fieldset>
						<legend>Busqueda de intrusiones</legend>
						<p>Por favor, configure la busqueda: </p>
							<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
						<label id="label2">
							<select name="tipo" tabindex="1" id="tipo">';
								$tipo_intrusiones = mysqli_query ($connection, "SELECT ID_INTRUSION FROM tipo_intrusion ORDER BY ID_INTRUSION ASC"); //Sentencia para buscarlo en la base de datos
								while($row=mysqli_fetch_array($tipo_intrusiones)){
									echo '<option value="'.$row[0].'">Tipo '.$row[0].'</option>';
								}
							// Inicio de ECHO multilinea
					echo '</select>Tipo de intrusion:
						<label id="label2">
							<select name="revisada" tabindex="2" id="revisada">
								<option value="0" selected>No revisadas</option>
								<option value="1">Revisadas</option>
							</select>Revisadas: 
						</label>
						<label id="label2">
							<input type="text" name="fechainicial" tabindex="3" id="fechainicial" readonly = "readonly"></input>Fecha inicial: <i>(usar calendario)</i>
						</label>
						<label id="label2">
							<input type="text" name="fechafinal" tabindex="4" id="fechafinal" readonly = "readonly"></input>Fecha final: <i>(usar calendario)</i>
						</label>
						<label id="label2">
							<input name="Submit" type="submit" id="submit" tabindex="5" value="Buscar"></input>
						</label>
						<br />
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
							})
						//]]></script>
					</fieldset>
				</form>
					
					</td>
					<td width="50"></td>
					<td>
						<table width="650">
						<tr>
							<td colspan="6"><center><h3>ULTIMAS 20 INTRUSIONES NO REVISADAS</h3></center></td>
						</tr>
						<tr>
							<td><b>NICK</b></td>
							<td><b>PASS</b></td>
							<td><b><center>IP</center></b></td>
							<td><b><center>TIPO</center></b></td>
							<td><b><center>DESCRIPCION</center></b></td>
							<td><center><b>FECHA</b></center></td>
						</tr>';
			
						//ULTIMAS 20
						$contador=1;
						$colorFila="filaBlanca";
						$query = "SELECT * FROM intrusos WHERE revisado = 0 order by fecha DESC limit 20";
						$result=mysqli_query ($connection, $query);
						$numrows=mysqli_num_rows($result);
						while ($datos=mysqli_fetch_array($result))
						{
							echo '<tr class="'.$colorFila.'">
									<td>'.$datos["nick"].'</td>
									<td>'.$datos["pass"].'</td>
									<td><center>'.$datos["IP"].'</center></td>
									<td><center>'.$datos["tipo"].'</center></td>
									<td><center>'.$datos["descripcion"].'</center></td>
									<td><center>'.$datos["fecha"].'</center></td>
								</tr>';
							if ($contador%2 == 0) {
								$colorFila = "filaBlanca";
							}else if ($contador%2 == 1) {
								$colorFila = "filaMorada";
							}
							$contador++;
																
						}
				echo '</table></td></tr></table>';
		}else{
			echo '<table cellpading="1" cellspacing="0" width="600">
				<tr>
					<td>
						<form id="formMasShort" method="post" action="intrusos.php">
						<fieldset>
						<legend>Busqueda de intrusiones</legend>
						<p>Por favor, configure la busqueda: </p>
							<input type="hidden" name="enviar_peticion" value="1" size="1"></input>
						<label id="label2">
							<select name="tipo" tabindex="1" id="tipo">';
								$tipo_intrusiones = mysqli_query ($connection, "SELECT ID_INTRUSION FROM tipo_intrusion ORDER BY ID_INTRUSION ASC"); //Sentencia para buscarlo en la base de datos
								while($row=mysqli_fetch_array($tipo_intrusiones)){
									if ($_POST[tipo] == $row[0]){
										echo '<option value="'.$row[0].'" selected>Tipo '.$row[0].'</option>';
									}else{
										echo '<option value="'.$row[0].'">Tipo '.$row[0].'</option>';
									}
								}
							// Inicio de ECHO multilinea
					echo '</select>Tipo de intrusion:
						<label id="label2">
							<select name="revisada" tabindex="2" id="revisada">';
								if ($_POST['revisada']==0){
									echo '<option value="0" selected>No revisadas</option>
									<option value="1">Revisadas</option>';
								}else{
									echo '<option value="0">No revisadas</option>
									<option value="1" selected>Revisadas</option>';
								}
							
							echo '</select>Revisadas: 
						</label>
						<label id="label2">
							<input type="text" name="fechainicial" tabindex="3" id="fechainicial" readonly = "readonly"></input>Fecha inicial: <i>(usar calendario)</i>
						</label>
						<label id="label2">
							<input type="text" name="fechafinal" tabindex="4" id="fechafinal" readonly = "readonly"></input>Fecha final: <i>(usar calendario)</i>
						</label>
						<label id="label2">
							<input name="Submit" type="submit" id="submit" tabindex="5" value="Buscar"></input>
						</label>
						<br />
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
							})
						//]]></script>
					</fieldset>
				</form>
					
					</td>
					<td width="50"></td>
					<td>
						<table width="650">
						<tr>
							<td colspan="5"><center><h3>INTRUSIONES SELECCIONADAS</h3></center></td>
						</tr>
						<tr>
							<td><b>NICK</b></td>
							<td><b>PASS</b></td>
							<td><b><center>IP</center></b></td>
							<td><b><center>DESCRIPCION</center></b></td>
							<td><center><b>FECHA</b></center></td>
						</tr>';
			
						//ULTIMAS 20
						$contador=1;
						$colorFila="filaBlanca";
						
						if (($_POST['fechainicial']=='') && ($_POST['fechafinal']=='')){
							$query = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." order by fecha DESC";	
						}else if (($_POST['fechainicial']<>'') && ($_POST['fechafinal']=='')){
							$query = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha >= '".$_POST['fechainicial']."' order by fecha DESC";	
						}else if (($_POST['fechainicial']=='') && ($_POST['fechafinal']<>'')){
							$query = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha <= '".$_POST['fechafinal']."' order by fecha DESC";	
						}else{
							$query = "SELECT * FROM intrusos WHERE revisado = ".$_POST['revisada']." AND tipo = ".$_POST['tipo']." AND fecha <= '".$_POST['fechafinal']."' AND fecha >= '".$_POST['fechainicial']."' order by fecha DESC";	
						}
						$result=mysqli_query ($connection, $query);
						$numrows=mysqli_num_rows($result);
						while ($datos=mysqli_fetch_array($result))
						{
							echo '<tr class="'.$colorFila.'">
									<td>'.$datos["nick"].'</td>
									<td>'.$datos["pass"].'</td>
									<td><center>'.$datos["IP"].'</center></td>
									<td><center>'.$datos["descripcion"].'</center></td>
									<td><center>'.$datos["fecha"].'</center></td>
								</tr>';
							if ($contador%2 == 0) {
								$colorFila = "filaBlanca";
							}else if ($contador%2 == 1) {
								$colorFila = "filaMorada";
							}
							$contador++;
																
						}
				if ($_POST['revisada']==0 && $numrows<>0){ //Si no estan revisadas damos la opcion de revisar
					echo '<tr><input value="Pulsar una vez revisados" type="button" onclick="location.href=\'resetIntrusiones.php?tipo='.$_POST['tipo'].'&fechainicial='.$_POST['fechainicial'].'&fechafinal='.$_POST['fechafinal'].'\';"/></tr>';
				}
				echo '</table></td></tr></table>';
		}
			
				echo '<p>&nbsp;</p>
					</body>
					</html>';
		

	}else{
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query ($connection, "INSERT INTO intrusion (ip,type,description,date) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}
?>