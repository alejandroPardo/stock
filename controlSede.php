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
<script type="text/javascript" src="resources/scripts/tablesort.js"></script>
<script type="text/javascript" src="resources/scripts/paginate.js"></script>
<script type="text/javascript" src="resources/scripts/filters.js"></script>
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
					<a href="#" class="nav-top-item">
						Sedes
					</a>
					<ul>
						<li><a href="sedeAdmin.php">Disponible en Sede</a></li>
						<li><a href="sedeDaily.php">Inventario Diario</a></li>
						<li><a href="sedePending.php">Por Procesar en Sede</a></li>
						<li><a href="sedeProd.php">Procesado en Sede</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item current">
						Panel de Control
					</a>
					<ul>
						<?php if($rank==1){echo "<li><a href=\"controlUsers.php\">Usuarios</a></li>";}?>
						<li><a href="controlProducts.php">Productos</a></li>
						<?php if($rank==1){echo "<li><a class=\current\" href=\"#\">Sedes</a></li>";}?>
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
				echo("<h2>Panel de Control</h2>");
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
			<?php 
				if ( $_GET["op"] == "ef" ){
					print("<div align=\"left\" class=\"notification error png_bg\"><a href=\"#\" class=\"close\">
  							<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  							<div>
							Hubo un error al Agregar la Sede. Recuerde que todos los campos son obligatorios.
							</div>
							</div>");
				} else {
					if ( $_GET["op"] == "edb" ){
						print("<div align=\"left\" class=\"notification error png_bg\"><a href=\"#\" class=\"close\">
  							<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  							<div>
							El nombre de Sede seleccionado ya existe en la Base de Datos. Elija otro nombre de Sede.
							</div>
							</div>");
					} else {
						if ( $_GET["op"] == "ok" ){
							print("<div align=\"left\" class=\"notification success png_bg\"><a href=\"#\" class=\"close\">
  								<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  								<div>
								La Sede se ha Agregado Correctamente!.
								</div>
								</div>");
						} else {
							if ( $_GET["op"] == "okd" ){
								print("<div align=\"left\" class=\"notification success png_bg\"><a href=\"#\" class=\"close\">
  									<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  									<div>
									La Sede se ha Borrado Correctamente!.
									</div>
									</div>");
							}
						}
					}
				}
			?>
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
				
				<h3>Sedes</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Tabla de Sedes</a></li> <!-- href must be unique and match the id of target div -->
						<li><a href="#tab2">Agregar Sede</a></li> <!-- href must be unique and match the id of target div -->
						<li><a href="#tab3">Eliminar Sede</a></li> <!-- href must be unique and match the id of target div -->
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
				
				<div class="tab-content default-tab" id="tab1" style="text-align:center">
				
						<table style="margin-left:auto;margin-right:auto;text-align:left" id="theTable" cellpadding="0" cellspacing="0" class="sortable-onload-0 rowstyle-alt colstyle-alt paginate-20">
  							<thead>
  								<tr>
    								<th class="sortable-text">ID</th>
    								<th class="sortable-text">Lugar de la Sede:</th>
  								</tr>
  							</thead>
  							<tbody>
  								<?php
  									$count=mysql_query("select count(*) from sede where id!='1'");
  									$row = mysql_fetch_row($count);
  									if($row[0]<1){
  										print("<div align=\"left\" class=\"notification attention png_bg\"><a href=\"#\" class=\"close\">
  										<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  										<div>
										No hay sedes registradas en el Sistema. Puede Agregar una Sede en la pestaña 'Agregar Sede'.
										</div>
										</div>");
  									}
  									
									$summat = mysql_query("select * from sede where id <> '1' order by id");
									
									while ($row = mysql_fetch_assoc($summat)) {
        								print "<tr><td class=\"sized1\" >$row[id]</td>";
        								print "<td class=\"sized1\">$row[place]</td>";
        							}
								?>
  							</tbody>
						</table>						
					</div> <!-- End #tab1 -->
					
					<div class="tab-content" id="tab2">
					<?php
  						print ("<form action=\"sedes.php?op=add\" method=\"POST\">\n
  							<fieldset>
  							<p>
								<label>Nombre de Sede:</label>
								<input class=\"text-input medium-input\" type=\"text\" id=\"medium-input\" name=\"name\" />																		<br /><small>Introduzca el nombre de la nueva sede. Este debe ser UNICO.</small>
							</p>
							<p>
								<input class=\"button\" type=\"submit\" value=\"Guardar\" />
							</p>
								
							</fieldset>			
							
							<div class=\"clear\"></div><!-- End .clear -->
							
							</form>");
					?>
					</div> <!-- End #tab2 -->  
					
					<div class="tab-content" id="tab3">
					<?php
  						print ("<form action=\"sedes.php?op=del\" method=\"POST\">\n
							<p>
								<label>Sedes:</label>              
								<select name=\"name\" class=\"medium-input\">");
									$summat = mysql_query("select * from sede where id <> '1'");
									while ($row = mysql_fetch_assoc($summat)) {
        								print "<option>$row[place]</option>";
									}
								print("</select> 
										<br /><small>Elija el nombre de la sede a eliminar.</small>
										</p>
										<p>
											<input class=\"button\" type=\"submit\" value=\"Eliminar\" />
										</p>

										</fieldset>			
									<div class=\"clear\"></div><!-- End .clear -->
							
							</form>");
					?>
						</form>
						
					</div> <!-- End #tab3 -->       

					
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