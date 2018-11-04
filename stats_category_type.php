<?php
	/**
	* PHP para visualizar estadisticas por tipo de incidencia
	* @author Germinal GARRIDO PUYANA
	* @version v1.1(0218)
	*/
	require('conexion.php'); //Incluimos el conexion.php que contiene los datos de la conexion a la base de datos
	require_once('maxChart.class.php');
	date_default_timezone_set('Europe/Madrid');
	if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
		echo '<html>
			  <head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>ESTADISTICAS POR OBJETIVO</title>
					<link href="style/style.css" rel="stylesheet" type="text/css" />
					<link href="styles.css" rel="stylesheet" type="text/css" />
			</head>
			
			
			<body style="font: 13px/20px sans-serif;" link="#0000ff" vlink="#0000ff">
			
				<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
			
			//RECOGEMOS EL AÑO
			$anio = "";
			if ( isset ( $_POST['anio'] ) ){
				$anio = $_POST['anio'];
			}	
			
		
			//TABLA DE DATOS USUARIO
			$usuarios = mysqli_query($connection, "SELECT u.nombre,u.departamento,l.avatar, l.descripcion FROM users u, tipo_level l WHERE u.nick = '".$_SESSION['usuario']."' AND l.level = u.level");
			$user = mysqli_fetch_array($usuarios); //Obtenemos el usuario en user_ok
			
			//PARA OBTENER LA LISTA DE AÑOS POSIBLES
			$cDateMinimo = mysqli_query ($connection, "SELECT MIN(DATE) FROM peticiones");
			$res_DateMinimo = mysqli_fetch_row($cDateMinimo);
			$DateMinimo = $res_DateMinimo[0];
			$anioMinimo = substr($DateMinimo, 0, 4);
			$ahora = getdate();
			$anioMaximo = $ahora["year"];
			echo '<blockquote><blockquote><blockquote>
				<table>
				<tr>
				 <td>
					<b>'.$user["nombre"].'</b>
					<br/><b>Usuario:</b> '.$_SESSION['usuario'].'
					<br/><b>Departamento:</b> '.$user["departamento"].'
				</td>
				<td>
				<form id="formShort" method="post" action="stats_category_type.php">
					<fieldset>
						<legend>Selecci&#243;n de a&#241;o: </legend>
						<label id="label2" for="anio">
							<select name="anio" tabindex="1" id="anio">
								<option value="">TOTAL</option>';
								for ($i=$anioMaximo; $i >= $anioMinimo; $i--){
									if ($i == $anio){
										echo '<option value="'.$i.'" SELECTED>'.$i.'</option>';
									}else{
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
								}
								
							echo '</select>A&#209;O:
						</label>
						<label id="label2" for="submit">
							<input name="Submit" type="submit" id="submit" value="Enviar"></input>
						</label>	
					</fieldset>
					</form>
				</td>
				</tr>
				</table>
				</blockquote></blockquote></blockquote>';
		
			echo '<hr/>';
			
			
			
			//ESTADISTICAS----------------POR-------------TIPO-----------------INCIDENCIA------------------
			
			if ($anio == ''){
				//PARA GRAFICA 1 TOTAL
				$tIncTargetTypeG1 = mysqli_query($connection, "SELECT c.DESCRIPCION, COUNT(*) FROM peticiones p, tipos_combos c WHERE p.CATEGORY_TYPE = c.ID_COMBO GROUP BY (p.CATEGORY_TYPE)");
			
				//PARA GRAFICA 2 TOTAL DE DIAS DIFERENTES
				$totalDiasDiferentes = mysqli_query($connection, "SELECT COUNT(DISTINCT(DATE(DATE))) FROM peticiones");
				$totalMesesDiferentes = mysqli_query($connection, "SELECT COUNT(DISTINCT(MONTH(DATE))) FROM peticiones");
			}else{
				//PARA GRAFICA 1 TOTAL
				$tIncTargetTypeG1 = mysqli_query($connection, "SELECT c.DESCRIPCION, COUNT(*) FROM peticiones p, tipos_combos c WHERE p.CATEGORY_TYPE = c.ID_COMBO AND p.DATE >= '".$anio."-01-01 00:00:00' AND p.DATE <= '".$anio."-12-31 23:59:59' GROUP BY (p.CATEGORY_TYPE)");
			
				//PARA GRAFICA 2 TOTAL DE DIAS DIFERENTES
				$totalDiasDiferentes = mysqli_query($connection, "SELECT COUNT(DISTINCT(DATE(DATE))) FROM peticiones WHERE DATE >= '".$anio."-01-01 00:00:00' AND DATE <= '".$anio."-12-31 23:59:59'");
				$totalMesesDiferentes = mysqli_query($connection, "SELECT COUNT(DISTINCT(MONTH(DATE))) FROM peticiones WHERE DATE >= '".$anio."-01-01 00:00:00' AND DATE <= '".$anio."-12-31 23:59:59'");
			}
			
			$datos_totalDiasDiferentes = mysqli_fetch_array($totalDiasDiferentes);
			$datos_totalMesesDiferentes = mysqli_fetch_array($totalMesesDiferentes);
			
			//GRAFICAS----------------------------------------------------------------------------------
			echo '<div id="main">';
			//GRAFICA 1,2 y 3: Incidencias por tipo de objetivo a lo largo de todo un año o total
				$numrows=@mysqli_num_rows($tIncTargetTypeG1);
				if ($numrows==0){
					$data0['NO HAY INCIDENCIAS'] = 0;
					$data1['NO HAY INCIDENCIAS'] = 0;
					$data2['NO HAY INCIDENCIAS'] = 0;
				}else{
					while ($datos_tIncTargetTypeG1 = mysqli_fetch_array($tIncTargetTypeG1)){
						$data0[$datos_tIncTargetTypeG1[0]] = $datos_tIncTargetTypeG1[1];
						$data1[$datos_tIncTargetTypeG1[0]] = round($datos_tIncTargetTypeG1[1]/$datos_totalMesesDiferentes[0],2);
						$data2[$datos_tIncTargetTypeG1[0]] = round($datos_tIncTargetTypeG1[1]/$datos_totalDiasDiferentes[0],2);
					}
				}
				
			
				
			$mcIncTargetTypeG1 = new maxChart($data0);
			$mcIncTargetTypeG1->displayChart('Incidencias por tipo categor&#237;a (AL A&#209;O) '.$anio,1,550,150,true);
			echo '<br/>';
			$mcIncTargetTypeG2 = new maxChart($data1);
			$mcIncTargetTypeG2->displayChart('Incidencias por tipo categor&#237;a (AL MES) '.$anio,1,550,150,true);
			echo '<br/>';
			$mcIncTargetTypeG3 = new maxChart($data2);
			$mcIncTargetTypeG3->displayChart('Incidencias por tipo categor&#237;a (AL DIA) '.$anio,1,550,150,true);
			echo '</div>
				<div id="footer">';
																							  
			echo '</div>';
			
			
			echo '</body>
			</html>';
	} else {
		$ahora = getdate(); //Obtiene un array con los datos de la fecha y hora actual
		$fecha = $ahora["year"]."-".$ahora["mon"]."-".$ahora["mday"]." ".$ahora["hours"].":".$ahora["minutes"].":".$ahora["seconds"]; //Obtiene el formato adecuado de fecha hora para insertar en la BBDD
		$IP = $_SERVER['REMOTE_ADDR'];
		$pagina = $_SERVER['PHP_SELF'];
		mysqli_query($connection, "INSERT INTO intrusos (IP,tipo,descripcion,fecha) values ('".$IP."',4,'".$pagina."','".$fecha."') ");
		Header("Location: index.php?intrusion=1");
	}


?>