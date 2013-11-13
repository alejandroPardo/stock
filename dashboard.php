<?php
	session_start();
	// dBase file
    include "dbConfig.php";
	//not logged
	if (!$_SESSION["valid_user"]){Header("Location: index.php");}
	$id=$_SESSION["valid_user"];
	$count=mysql_query("select * from `user` where `username`='$id';");
	while ($row = mysql_fetch_assoc($count)) {
    	$name= $row['name'];
    	$last= $row['lastname'];
    	$rank= $row['rank'];
    	$sede= $row['sede'];
	}
	if($rank<2){Header("Location: members.php");}
	
	$count=mysql_query("select * from `sede` where `id`='$sede';");
	while ($row = mysql_fetch_assoc($count)) {
    	$place= $row['place'];
	}
	$count=mysql_query("select count(*) from `message` where `receiver`='$id' and `read`=0;");
	$row = mysql_fetch_row($count);
    $messages = $row[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>StockOnline - Zona de Usuarios</title>
<link rel="Shortcut Icon" href="resources/images/favicon.png">
<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	
<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="resources/scripts/facebox.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.datePicker.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.date.js"></script>
</head>
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">StockOnline</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="dashboard.php"><img id="logo" src="resources/images/membersLogo.png" alt="Simpla Admin logo" /></a>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
			<?php
				echo("Hola, <a href=\"userAccount.php\" title=\"Edit your profile\">$name</a>.<br>");
				if($messages>0){
					echo("Tienes <a href=\"#messages\" rel=\"modal\" title=\"Messages\">$messages Mensaje(s) nuevo</a><br/>");
				} else {
					echo("No tienes <a href=\"#messages\" rel=\"modal\" title=\"Messages\">nuevos mensajes</a><br/>");
				}
				
			?>
				<br />
				<a href="logout.php" title="Sign Out">Desconectarse</a>
			</div>         
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
				<li>
					<a href="#" class="nav-top-item no-submenu current">
						Dashboard
					</a>       
				</li>
				
				<li>
					<a href="userAvailable.php" class="nav-top-item no-submenu">
						Productos
					</a>
				</li> 
				
				<li>
					<a href="userDaily.php" class="nav-top-item no-submenu">
						Inventario Diario
					</a>
				</li> 
				
				<li>
					<a href="#" class="nav-top-item">
						Nuevas Entradas
					</a>
					<ul>
						<li><a href="userEntry.php">Entradas por Peso</a></li>
						<li><a href="userUnitEntry.php">Entradas por Unidades</a></li>
					</ul>
				</li> 
				
				<li>
					<a href="#" class="nav-top-item">
						Entradas & Inventarios
					</a>
					<ul>
						<li><a href="userList.php">Listado de Entradas</a></li>
						<li><a href="userDailyStock.php">Listado de Inventarios Diarios</a></li>
						<li><a href="userProd.php">Entradas por Producto</a></li>
					</ul>
				</li>
				
				
				
			</ul> <!-- End #main-nav -->
			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
				<div id="diva">	
				<?php
					echo("<h3>$messages Mensajes</h3>");
					$count=mysql_query("select * from `message` where `receiver`='$id' ORDER BY `date_sent` DESC;");
					while ($row = mysql_fetch_assoc($count)) {
						$dates=$row['date_sent'];
						$sender=$row['sender'];
						$mensaje=$row['mensaje'];
						$num=$row['id'];
						if($row['read']==0){
							print("<p>
								<strong>$dates de $sender<br>
								$mensaje
								</strong></p>");
						} else{
							print("<p>
								<strong>$dates</strong> de $sender<br>
								$mensaje
								<small><a href=\"messages.php?op=del&mess=$num&wh=dashboard.php\" class=\"remove-link\" title=\"Remove message\">Eliminar</a></small>
								</p>");
						}
						mysql_query("UPDATE `message` SET `read` = '1' WHERE `id`='$num';");
					}
				?>
				
				</div>
				<form action="messages.php" rel="modal" method="post">
					
					<h4>Nuevo Mensaje</h4>
					
					<fieldset>
						<textarea class="textarea" name="mensaje" cols="79" rows="5"></textarea>
					</fieldset>
					
					<fieldset>
					
						<select name="receiver" class="small-input">
						<?php
							$count=mysql_query("select username from `user`;");
							print("<option>Todos</option>");
							while ($row = mysql_fetch_assoc($count)) {
								$names=$row['username'];
								print("<option>$names</option>");
							}
						?>
						</select>
						
						<input type="hidden" name="where" value="members.php">
						
						<input class="button" type="submit" value="Send" />
						
					</fieldset>
					
				</form>
				
			</div> <!-- End #messages -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
					</div>
				</div>
			</noscript>
			
			<!-- Page Head -->
			<?php
				echo("<h2>Bienvenido, $name $last</h2>");
				
				echo("<p id=\"page-intro\">Fujiyama Sushi Bar & Asian Cuisine - Sede: $place</p>");
				
				if ( $_GET["msg"] == "err" ){
				echo("<div class=\"notification error png_bg\">
				<a href=\"#\" class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=				\"Close this notification\" alt=\"close\" /></a>
				<div>
					Se necesita que escriba un mensaje para poder enviarlo!.
				</div>
				</div>");
			} else {
				if ( $_GET["msg"] == "good" ){
					echo("<div class=\"notification success png_bg\">
					<a href=\"#\" class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" 						title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						El mensaje se ha enviado Correctamente!.
					</div>
					</div>");
				} else {
					if ( $_GET["msg"] == "mdel" ){
						echo("<div class=\"notification success png_bg\">
						<a href=\"#\" class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" 						title=\"Close this 									notification\" alt=\"close\" /></a>
						<div>
							El mensaje se ha eliminado Correctamente!.
						</div>
						</div>");
				}
				}
			}
			?>
			<ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="userAvailable.php"><span>
					<img src="resources/images/icons/productos.png" alt="icon" /><br />
					Productos
				</span></a></li>
				
				<?php
					$count=mysql_query("select count(*) from `entrada_sede` where `procesado`='0';");
					$row = mysql_fetch_row($count);
					$salidas = $row[0];
				if($salidas>0){
				echo("
					<li><a class=\"shortcut-button\" href=\"userEntry.php\"><span>
					<img src=\"resources/images/icons/usernew1.png\" alt=\"icon\" /><br />
					Entradas
					</span></a></li>");
				} else {
				echo("
					<li><a class=\"shortcut-button\" href=\"userEntry.php\"><span>
					<img src=\"resources/images/icons/entrada.png\" alt=\"icon\" /><br />
					Entradas
					</span></a></li>");
				}
				
					$count=mysql_query("select count(*) from `product_sede` where `critico`='1' AND date='CURDATE()';");
					$row = mysql_fetch_row($count);
					$diario = $row[0];
				if($diario>0){
				echo("
					<li><a class=\"shortcut-button\" href=\"userDaily.php\"><span>
					<img src=\"resources/images/icons/userstock1.png\" alt=\"icon\" /><br />
					Inventario Diario
					</span></a></li>");
				} else {
				echo("
					<li><a class=\"shortcut-button\" href=\"userDaily.php\"><span>
					<img src=\"resources/images/icons/userstock2.png\" alt=\"icon\" /><br />
					Inventario Diario
					</span></a></li>");
				}
				
				if($messages>0){
				echo("
				<li><a class=\"shortcut-button\" href=\"#messages\" rel=\"modal\"><span>
					<img src=\"resources/images/icons/mensajes2.png\" alt=\"icon\" /><br />
					Mensajes
				</span></a></li>");
				} else {
				echo("
				<li><a class=\"shortcut-button\" href=\"#messages\" rel=\"modal\"><span>
					<img src=\"resources/images/icons/mensajes1.png\" alt=\"icon\" /><br />
					Mensajes
				</span></a></li>");				
				}
			?>
				
			</ul><!-- End .shortcut-buttons-set -->
			
			<div class="clear"></div> <!-- End .clear -->

			<?php
			$count=mysql_query("select count(*) from `entrada_sede` where `procesado`='0';");
			$row = mysql_fetch_row($count);
			$cant=$row[0];
			$count=mysql_query("select count(*) from `entrada_sede` where `procesado`='2';");
			$row = mysql_fetch_row($count);
			$cant2=$row[0];
			
				echo("<div class=\"content-box column-left\">
					<div class=\"content-box-header\">
						<h3>Informacion de Entradas</h3>
					</div> <!-- End .content-box-header -->
					<div class=\"content-box-content\">
						<div class=\"tab-content default-tab\">
							<h4>Alertas de Productos</h4>
							<p>");
							if($cant>0){
								echo("Hay $cant producto(s) con peso por procesar, para procesarlo(s) presione <a href=\"userEntry.php\">aqui</a><br><br>");
							}
							if($cant2>0){
								echo("Hay $cant producto(s) por unidades por procesar, para procesarlo(s) presione <a href=\"userUnitEntry.php\">aqui</a>");
							}	
							if($cant==0 && $cant2==0){
								echo("No hay ninguna entrada nueva de productos.");
							}						
							echo("</p>
						</div> <!-- End #tab3 -->        
					</div> <!-- End .content-box-content -->
					</div> <!-- End .content-box -->
				");
			?>
			
			<?php
			$q1=mysql_query("select count(*) from `product_sede` where `date`=curdate();");
			$rs1 = mysql_fetch_row($q1);
			$a1=$rs1[0];
			
			if($a1>0){
				echo("<div class=\"content-box column-right closed-box\">
					<div class=\"content-box-header\">
						<h3>Informacion de Sede</h3>
					</div> <!-- End .content-box-header -->
					<div class=\"content-box-content\">
						<div class=\"tab-content default-tab\">
							<h4>Alerta de Inventario Diario</h4>
							<p>
								El Inventario Diario ya fue realizado. Haga Click <a href=\"userDailyStock.php\">aqui</a> para verlo.
							</p>
				
						</div> <!-- End #tab3 -->        
					</div> <!-- End .content-box-content -->
					</div> <!-- End .content-box -->
					");
			} else {
				echo("<div class=\"content-box column-right\">
					<div class=\"content-box-header\">
						<h3>Informacion de Sedes</h3>
					</div> <!-- End .content-box-header -->
					<div class=\"content-box-content\">
						<div class=\"tab-content default-tab\">
							<h4>Alerta de Inventario Diario</h4>
							<p>
								El Inventario Diario no ha sido realizado. Haga click <a href=\"userDaily.php\">aqui</a> para realizarlo.
							</p>
						</div> <!-- End #tab3 -->        
					</div> <!-- End .content-box-content -->
					</div> <!-- End .content-box -->
				");
			}
			?>
			
			
			<div class="clear"></div>
			
			<div id="footer">
				<img src="resources/images/fujiyamalogo.png" alt="Fujiyama" align="right" width="116" height="32" />
				<small> <!-- Remove this notice or replace it with whatever you want -->
						<a href="dashboard.php">Principal</a> | <a href="siteMap.php">Mapa del Sitio</a> | <a href="#">Volver al Inicio</a> | &#169; Copyright 2010 StockOnline 
				</small>
			</div><!-- End #footer -->
	</body>
</html>