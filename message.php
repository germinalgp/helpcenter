<?php

echo '<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="styles.css" rel="stylesheet" type="text/css" />
		</head>
	    <body bgcolor = "#B9D6ED">';
		
		$i = "";
		if ( isset ( $_GET['mensaje'] ) ){
			$i = $_GET['mensaje'];
		}

		$numero = "";
		if ( isset ( $_GET['numero'] ) ){
			$numero = $_GET['numero'];
		}		
		
		
		
		
		if ($i == 0){
			$mensaje = "USUARIO REGISTRADO CON EXITO";
		}else if ($i == 2){
			$mensaje = "ERROR DE REGISTRO:<br/>EL DNI NO CUMPLE CON EL FORMATO ADECUADO (8 DIGITOS). Ejemplo:<br/>    11111111)";
		}else if ($i == 3){
			$mensaje = "ERROR DE REGISTRO:<br/>LA CONFIRMACION DEL PASSWORD ES INCORRECTA AL SER DIFERENTE DEL PROPORCIONADO";
		}else if ($i == 4){
			$mensaje = "ERROR DE REGISTRO:<br/>TELEFONO PROPORCIONADO INCORRECTO (TIENE MENOS DE 9 DIGITOS - Sin letras -)";
		}else if ($i == 5){
			$mensaje = "ERROR DE REGISTRO:<br/>EMAIL PROPORCIONADO ES INCORRECTO";
		}else if ($i == 6){
			$mensaje = "ERROR DE REGISTRO:<br/>USUARIO YA REGISTRADO ANTERIORMENTE (Contactar mediante email o llamada telefonica proporcionados en la pagina principal)";
		}else if ($i == 7){
			$mensaje = "ERROR ELIMINANDO:<br/>NO HA SELECCIONADO NINGUN ELEMENTO PARA ELIMINAR";
		}else if ($i == 8){
			$mensaje = "ERROR CREANDO CATEGORIA:<br/>NO HA SELECCIONADO LA INCIDENCIA A LA QUE SE VINCULA";
		}else if ($i == 9){
			$mensaje = "ERROR CREANDO CATEGORIA:<br/>NO HA ESCRITO UN NOMBRE A LA CATEGORIA";
		}else if ($i == 10){
			$mensaje = "ERROR CREANDO CATEGORIA:<br/>LA CATEGORIA YA EXISTE";
		}else if ($i == 11){
			$mensaje = "ERROR CREANDO PRODUCTO:<br/>NO HA SELECCIONADO LA CATEGORIA A LA QUE SE VINCULA";
		}else if ($i == 12){
			$mensaje = "ERROR CREANDO PRODUCTO:<br/>NO HA ESCRITO UN NOMBRE AL PRODUCTO";
		}else if ($i == 13){
			$mensaje = "ERROR CREANDO PRODUCTO:<br/>EL PRODUCTO YA EXISTE";
		}else if ($i == 14){
			$mensaje = "PRODUCTO CREADO CORRECTAMENTE";
		}else if ($i == 15){
			$mensaje = "CATEGORIA CREADA CORRECTAMENTE";
		}else if ($i == 16){
			$mensaje = "PRODUCTO BORRADO CORRECTAMENTE";
		}else if ($i == 17){
			$mensaje = "CATEGORIA BORRADA CORRECTAMENTE";
		}else if ($i == 21){
			$mensaje = "<b>EL ENV&Iacute;O DE LA INCIDENCIA CON <u>ID N&#176;". $numero."</u> SE HA REALIZADO CORRECTAMENTE.<br/>USE DICHA ID PARA FUTURAS CONSULTAS.</b>";
		}else if ($i == 22){
			$mensaje = "<font color=\"RED\"><b>ERROR:</b> FALTA POR RELLENAR UN CAMPO OBLIGATORIO.</font>";
		}else if ($i == 27){
			$mensaje = "<font color=\"RED\"><b>ERROR:<b/> REITERAR ANTES DEL TIEMPO MAXIMO ESTABLECIDO PARA SOLUCIONAR UNA INCIDENCIA, SIENDO LOS SIGUIENTES:<br/>
					1. RECLAMACION SOBRE ACTIVACION: 24 HORAS<br/>
					2. RECLAMACION SOBRE PETICION DE DATOS: 4 DIAS<br/>
					3. HARDWARE y SOFTWARE: 2 DIAS<br/>
					4. OTRA INCIDENCIA: 4 DIAS<br/></font>";
		}else if ($i == 28){
			$mensaje = "<font color=\"RED\"><b>ERROR:<b/> REITERAR SOBRE EL ID DE UNA REITERACION ANTERIOR (SIEMPRE HAY QUE REITERAR SOBRE LA INCIDENCIA ORIGEN).</font>";
		}else if ($i == 29){
			$mensaje = "<font color=\"RED\"><b>ERROR:<b/> ID SELECCIONADO NO EXISTE.</font>";
		}else{
						$errorNick = substr($i,0,1);
						$errorPass = substr($i,1,1);
						$errorPass2 = substr($i,2,1);
						$errorNombre = substr($i,3,1);
						$errorEmail = substr($i,4,1);
						$errorTelephone = substr($i,5,1);
						$errorLevel = substr($i,6,1);
						$errorDepartamento = substr($i,7,1);
						if ($errorNick == "1"){
							$errorNick = "DNI<br/>";
						}else{
							$errorNick = "";
						}
						
						if ($errorPass == "1"){
							$errorPass = "    Password<br/>";
						}else{
							$errorPass = "";
						}
						
						if ($errorPass2 == "1"){
							$errorPass2 = "    Confirmacion Password<br/>";
						}else{
							$errorPass2 = "";
						}
						
						if ($errorNombre == "1"){
							$errorNombre = "    Nombre y apellidos<br/>";
						}else{
							$errorNombre = "";
						}
						
						if ($errorEmail == "1"){
							$errorEmail = "    Email<br/>";
						}else{
							$errorEmail = "";
						}
						
						if ($errorTelephone == "1"){
							$errorTelephone = "    Telefono<br/>";
						}else{
							$errorTelephone = "";
						}
						
						if ($errorLevel == "1"){
							$errorLevel = "    Level<br/>";
						}else{
							$errorLevel = "";
						}
						
						if ($errorDepartamento == "1"){
							$errorDepartamento = "    Departamento<br/>";
						}else{
							$errorDepartamento = "";
						}
						
						$mensaje = "ERROR DE REGISTRO: <br/>FALTAN LOS SIGUIENTES CAMPOS OBLIGATORIOS:<br/><blockquote>".$errorNick.$errorPass.$errorPass2.$errorNombre.$errorEmail.$errorTelephone.$errorLevel.$errorDepartamento."</blockquote>";
						
		}
	echo $mensaje;
		
		
		
	echo '</body>
		</html>';


?>