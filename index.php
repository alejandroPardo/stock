<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stock Online | Entrar</title>
<link rel="Shortcut Icon" href="resources/images/favicon.png">
<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	
<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="resources/scripts/facebox.js"></script>
<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
</head>
  
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h1>Stock</h1>
				<!-- Logo (221px width) -->
				<img id="logo" src="resources/images/loginLogo.png" alt="StockOnline logo" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				<?php
					if(isset($_GET["op"])){
	        			if ($_GET["op"] == "login"){
	        				echo("<form action=\"login.php?op=login\" METHOD=POST>
							<div class=\"notification error png_bg\">
							<div> Los campos no pueden estar vacios.</div></div>");
	        			} else {
	        				if ($_GET["op"] == "error"){
	        					echo("<form action=\"login.php?op=login\" METHOD=POST>
								<div class=\"notification error png_bg\">
								<div> Los datos introducidos son incorrectos.</div></div>");
	        				}
	        			}
	        		} else {
    					echo("<form action=\"login.php?op=login\" METHOD=POST>
						<div class=\"notification information png_bg\">
						<div> Introduzca su Usuario y clave.</div></div>");
    				}
				?>
					<p>
						<label>Usuario</label>
						<input class="text-input" name="username" type="text" id="myusername">
					</p>
					<div class="clear"></div>
					<p>
						<label>Clave</label>
						<input class="text-input" name="password" type="password" id="mypassword"/>
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Entrar" />
					</p>
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
  </body>
</html>
