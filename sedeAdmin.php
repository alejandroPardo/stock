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
	$count=mysql_query("select count(*) from `message` where `receiver`='$id' and `read`=0;");
	$row = mysql_fetch_row($count);
    $messages = $row[0];
	if($rank>1){Header("Location: dashboard.php");}
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
<link rel="stylesheet" href="resources/css/table.css"  type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/autosuggest.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="resources/scripts/tablesort.js"></script>
<script type="text/javascript" src="resources/scripts/paginate.js"></script>
<script type="text/javascript" src="resources/scripts/filters.js"></script>
<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="resources/scripts/facebox.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.datePicker.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.date.js"></script>
<script type="text/javascript" src="resources/scripts/AutoSuggest.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.validate.min.js"></script>
<script type="text/javascript" src="resources/scripts/adminexit.js"></script>

</head>
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">StockOnline</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="members.php"><img id="logo" src="resources/images/membersLogo.png" alt="StockOnline logo" /></a>
		  
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
					<a href="members.php" class="nav-top-item no-submenu">
						Dashboard
					</a>       
				</li>
				
				<li> 
					<a href="available.php" class="nav-top-item no-submenu">
						Productos
					</a>
				</li>
				<li>
					<a href="#" class="nav-top-item">
						Entradas
					</a>
					<ul>
						<li><a href="entryAdmin.php">Nueva Entrada</a></li>
						<li><a href="entryList.php">Listado de Entradas</a></li>
						<li><a href="entryProd.php">Entradas por Producto</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Salidas
					</a>
					<ul>
						<li><a href="exitAdmin.php">Nueva Salida</a></li>
						<li><a href="exitList.php">Listado de Salidas</a></li>
						<li><a href="exitProd.php">Salidas por Producto</a></li>
						<li><a href="exitSede.php">Salidas por Sede</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item current">
						Sedes
					</a>
					<ul>
						<li><a class="current" href="#">Disponible en Sede</a></li>
						<li><a href="sedeDaily.php">Inventario Diario</a></li>
						<li><a href="sedePending.php">Por Procesar en Sede</a></li>
						<li><a href="sedeProd.php">Procesado en Sede</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Panel de Control
					</a>
					<ul>
						<?php if($rank==1){echo "<li><a href=\"controlUsers.php\">Usuarios</a></li>";}?>
						<li><a href="controlProducts.php">Productos</a></li>
						<?php if($rank==1){echo "<li><a href=\"controlSede.php\">Sedes</a></li>";}?>
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
								<small><a href=\"messages.php?op=del&mess=$num&wh=members.php\" class=\"remove-link\" title=\"Remove message\">Eliminar</a></small>
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
				echo("<h2>Sedes</h2>");
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
					
			<p id="page-intro"></p>	
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box" style="text-align:center"><!-- Start Content Box -->
				
				<div class="content-box-header" style="text-align:center">
				
				<h3>Disponible por Sede</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Productos en Sede</a></li> <!-- href must be unique and match the id of target div -->
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content" style="text-align:center">  
				<?php
					if ($_GET["op"] == "not"){
						echo("<div class=\"notification error png_bg\">
						<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
						<div>
						Error. No hay productos en esta sede.
						</div>
						</div>");
					}
				?>
					<div class="tab-content default-tab" id="tab1" style="text-align:center"> 
						<div class="ajax"></div>
							<div id="response"></div>
							<div class="site">
								<div class="menu">
								<form id="getsedeStock" method="post">
									<br>
									<label>Sede</label>              
									<select id="name" name="name" class="medium-input">
									<?php
										$summat = mysql_query("select * from sede where id <> '1'");
											while ($row = mysql_fetch_assoc($summat)) {
        										print "<option>$row[place]</option>";
											}
									?>
									</select> 
									<br>
									<input class="button" type="submit" id="submit" name="submit" value="Buscar" />
								</form>
									<br><br>
								</div>
			
							</div><!--site-->
					</div> <!-- End #tab2 -->  
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			<div id="footer">
				<img src="resources/images/fujiyamalogo.png" alt="Fujiyama" align="right" width="116" height="32" />
				<small> <!-- Remove this notice or replace it with whatever you want -->
						<a href="members.php">Principal</a> | <a href="siteMap.php">Mapa del Sitio</a> | <a href="#">Volver al Inicio</a> | &#169; Copyright 2010 StockOnline 
				</small>
			</div><!-- End #footer -->
	</body>
</html>
