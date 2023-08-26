<?php
/**
 * Created by PhpStorm.
 * User: miguel.llabres
 * Date: 09/03/2017
 * Time: 13:20
 */
session_start();
//include ("./PHP/create_excel.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PARSER DDBB-2-EXCEL</title>
    <meta name="description" content="GENERADOR DE EXCEL A PARTIR DE UNA BASE DE DATOS">
    <meta name="keywords" content="GENERA UNA DOCUMENTO EXCEL CON LAS TABLAS DE UNA BASE DE DATOS PLASMADAS EN CADA SHEET">
    <meta name="author" content="Miquel Angel">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="./JS/jquery-1.12.3.min.js"></script>
    <script src="./JS/bootstrap.min.js"></script>
    <script src="./JS/main.js"></script>


    <link rel="stylesheet" href="./CSS/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/normalize.css">
    <link rel="stylesheet" href="./CSS/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./CSS/styles.css">

</head>
<body>

    <form id="form_db2excel" action="#">
        <header>CONEXIÓN AL SERVIDOR</header>

        <input type="text" id="host" placeholder="Introduce la IP del servidor" value="127.0.0.1" required>
        <input type="text" id="user" placeholder="Introduce usuario" value="root" required>
        <input type="password" id="pass" placeholder="Introduce contraseña" value="" required>
        <input type="number" id="port" min="1" max="56000" value="3306" required>
        <button id="butt_connection">Conecta!</button>
        <button id="butt_disconnection">Salir</button>
        <p id="connection_result"></p>
    </form>

        <div id="refreshable_content">
			<?php
				if(isset($_SESSION['tables'])){
					
			?>
				<div id="tables_section">
					<form id="form_tables">
						<header>SELECCION DE TABLAS/VISTAS</header>
						<label for="tables">Selecciona las tablas y vistas que van a ser exportadas</label>
						<br>
						<div id="checkboxes">
							<!--cargado desde session-->
							<?php
							//print_r($_SESSION['databases']);
								foreach ($_SESSION['tables'] as $datab){
									echo $datab;
								}
							?>
						</div>
					</form>
				</div>

				<div id="details_section">
					<form id="form_details"
						<header>DATOS DEL DOCUMENTO EXCEL</header>
						<input type="text" id="doc_author" placeholder="Introduce el autor del documento" value="autor">
						<input type="text" id="doc_name" placeholder="Introduce el nombre del archivo" value="documento">
						<input type="text" id="doc_category" placeholder="Introduce la categoria del archivo" value="categoria">
						<input type="text" id="doc_description" placeholder="Introduce la descripción del archivo" value="descripcion">
						<button id="butt_db2excel">DESCARGAR FICHERO</button>
					</form>

					<div id="refreshing_excel">
						<?php
						if(isset($_SESSION['author']) &&
							isset($_SESSION['title']) &&
							isset($_SESSION['category']) &&
							isset($_SESSION['description'])){
							//$objWriter = excel_maker();
							//$objWriter->save('php://output');
							?>
							<iframe align="center" width="100%" height="100%" src="PHP/create_excel.php"
									frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> 
									
									<?php 
										print_r($_SESSION);
									?>
									</iframe>
							<?php
						}else{
							?>
							<p>Faltan datos para generar el fichero excel</p>
							<?php
						}
						?>
					</div>
				</div>




			<?php
			}
			else if(isset($_SESSION['databases'])){
				?>
				<div id="db_section">
					<form id="form_database">


						<header>SELECCION DE BASE DE DATOS</header>
						<label for="ddbb">Selecciona la base de datos que se va a exportar a EXCEL</label>
						<select id="ddbb">

							<!--cargado desde session-->
							<?php
							foreach ($_SESSION['databases'] as $datab){
								echo $datab;
							}
							?>
							<option value="xxx" disabled selected>selecciona una base de datos</option>

						</select>
					</form>
				</div>
			<?php
			}
			?>
        </div>










</body>
</html>