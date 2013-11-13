<?php
	session_start();
    include "dbConfig.php";
	if (!$_SESSION["valid_user"]){Header("Location: index.php");}
	
	/** parse AJAX GET REQUESTS ****/
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
		
		if (isset($_GET)) {
		
			if (isset($_GET['mode'])) {
				
				switch ($_GET['mode']){
					
					case 'list' :
						listEntry();
					break;
					
					case 'date' :
						fecha($_POST);
					break;
					
					case 'listUnit' :
						listUnit();
					break;
					
					case 'listDaily' :
						listDaily();
					break;
										
					case 'edit' :
						wooo_editform($_GET['id'], $_GET['sede']);
					break;
					
					case 'editUnit' :
						editUnit($_GET['id'], $_GET['sede']);
					break;
					
					case 'editDaily' :
						editDaily($_GET['id'], $_GET['sede']);
					break;
														
					case 'prod' :
						wooo_prod($_POST);
					break;
										
					case 'editsave' :
						wooo_editsave($_POST);
					break;
					
					case 'saveUnit' :
						saveUnit($_POST);
					break;
					
					case 'saveDaily' :
						saveDaily($_POST);
					break;
					
					case 'check' :
						check($_POST);
					break;
					
					case 'checkUnit' :
						checkUnit($_POST);
					break;
					
					case 'checkDaily' :
						checkDaily($_GET['sede'], $_GET['date']);
					break;
									
					case 'gateway':
						wooo_gateway();
					break;		
				} // switch
				
			} // mode	
			
		}
	} // ajax	
	
	function listUnit(){
	$id=$_SESSION["valid_user"];
	$count=mysql_query("select * from `user` where `username`='$id';");
	$sede=0;
	while ($row = mysql_fetch_assoc($count)) {
    	$sede= $row['sede'];
	}
								$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"sortable-									onload-0 rowstyle-alt colstyle-alt paginate-20\">
								<tr>
			  	  					<th class=\"sortable-text\">Producto</th>
			  	  					<th class=\"sortable-date\">Fecha</th>
			  	  					<th class=\"sortable-numeric\">Disponible Actual</th>
			  	  					<th class=\"sortable-numeric\">Unidades Nuevas</th>
			  	  					<th></th>
			  	  				</tr>
			  	  				<tr>
			   						<td class=\"sized1\"></td>
			   						<td class=\"sized1\"></td>
			   						<td class=\"sized1\"></td>
			   						<td class=\"sized1\"></td>
			   						<td class=\"sized3\"></td>
			   					</tr>";
			   	
								
								$q2 = mysql_query("SELECT count(*) FROM `daily_sede` where entrada='-1' and sede='$sede';");
								$a2 = mysql_fetch_row($q2);
								
								if($a2[0]>0){
								$q = mysql_query("SELECT * FROM `daily_sede` where entrada='-1' and sede='$sede';");
								while($p = mysql_fetch_assoc($q)){
									$id=$p['id'];
    								$prod=$p['producto'];
    								$disp=$p['instock'];
    								$ent=$p['unidades'];
    								$date=$p['date'];
									$o .="<tr>
			   							<td class=\"sized1\">$prod</td>
			   							<td class=\"sized2\">$date</td>
			   							<td class=\"sized2\">$disp</td>
			   							<td class=\"sized2\">$ent</td>
			   							<td class=\"sized3\"><a class=\"check\" uni=\"$ent\" nam=\"$prod\" ref=\"$id\" dat=\"$date\">check</a><a class=\"edit\" sede =\"$sede\" ref=\"$id\">edit</a></td>
			   						</tr>";	
 		 						}
 								$o .= '</table>'; 
 								print $o;
 								} else {
 									print("<div align=\"left\" class=\"notification success png_bg\"><a href=\"#\" class=\"close\">
  										<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  										<div>
										No hay nuevas Entradas por Procesar!.
										</div>
										</div>");
 								}
	
	}
	
	function listEntry(){
	$id=$_SESSION["valid_user"];
	$count=mysql_query("select * from `user` where `username`='$id';");
	$sede=0;
	while ($row = mysql_fetch_assoc($count)) {
    	$sede= $row['sede'];
	}
	
								$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"sortable-									onload-0 rowstyle-alt colstyle-alt paginate-20\">
								<tr>
			  	  					<th class=\"sortable-text\">Producto</th>
			  	  					<th class=\"sortable-date\">Fecha</th>
			  	  					<th class=\"sortable-numeric\">Disponible</th>
			  	  					<th class=\"sortable-numeric\">Entrada</th>
			  	  					<th class=\"sortable-numeric\">Merma</th>
			  	  					<th class=\"sortable-numeric\">Real</th>
			  	  					<th></th>
			  	  				</tr>
			  	  				<tr>
			   						<td class=\"sized1\"></td>
			   						<td class=\"sized2\"></td>
			   						<td class=\"sized2\"></td>
			   						<td class=\"sized2\"></td>
			   						<td class=\"sized2\"></td>
			   						<td class=\"sized2\"></td>
			   						<td class=\"sized3\"></td>
			   					</tr>";
			   	
								
								$q2 = mysql_query("SELECT count(*) FROM `daily_sede` where unidades='0' and sede='$sede';");
								$a2 = mysql_fetch_row($q2);
								
								if($a2[0]>0){
								$q = mysql_query("SELECT * FROM `daily_sede` where unidades='0' and sede='$sede';");
								while($p = mysql_fetch_assoc($q)){
									$id=$p['id'];
    								$prod=$p['producto'];
    								$disp=$p['instock'];
    								$ent=$p['entrada'];
    								$merma=$p['merma'];
    								$real=$p['realpeso'];
    								$date=$p['date'];
									$o .="<tr>
			   							<td class=\"sized1\">$prod</td>
			   							<td class=\"sized2\">$date</td>
			   							<td class=\"sized2\">$disp</td>
			   							<td class=\"sized2\">$ent</td>
			   							<td class=\"sized2\">$merma</td>
			   							<td class=\"sized2\">$real</td>
			   							<td class=\"sized3\"><a class=\"check \" nam=\"$prod\" ref=\"$id\" dat=\"$date\">check</a><a class=\"edit\" sede =\"$sede\" ref=\"$id\">edit</a></td>
			   						</tr>";	
 		 						}
 								$o .= '</table>'; 
 								print $o;
 								} else {
 									print("<div align=\"left\" class=\"notification success png_bg\"><a href=\"#\" class=\"close\">
  										<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  										<div>
										No hay nuevas Entradas por Procesar!.
										</div>
										</div>");
 								}
	
	}
	
	function listDaily(){
	$id=$_SESSION["valid_user"];
	$count=mysql_query("select * from `user` where `username`='$id';");
	$sede=0;
	while ($row = mysql_fetch_assoc($count)) {
    	$sede= $row['sede'];
	}
		$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"sortable-									onload-0 rowstyle-alt colstyle-alt paginate-20\">
		<tr>
			<th class=\"sortable-text\">Producto</th>
			<th class=\"sortable-numeric\">Disponible</th>
			<th class=\"sortable-numeric\">Entrada</th>
			<th class=\"sortable-numeric\">Merma</th>
			<th class=\"sortable-numeric\">Peso Real</th>
			<th></th>
			</tr>
			<tr>
				<td class=\"sized1\"></td>
			   	<td class=\"sized2\"></td>
			   	<td class=\"sized2\"></td>
			   	<td class=\"sized2\"></td>
			   	<td class=\"sized2\"></td>
			   	<td class=\"sized3\"></td>
			</tr>";
			$q2 = mysql_query("SELECT count(*) FROM `daily_sede` where sede='$sede'");
			$a2 = mysql_fetch_row($q2);	
			if($a2[0]>0){
				$q = mysql_query("SELECT * FROM `daily_sede` where sede='$sede';");
				while($p = mysql_fetch_assoc($q)){
					$id=$p['id'];
    				$prod=$p['producto'];
    				$disp=$p['instock'];
    				$ent=$p['entrada'];
    				$merma=$p['merma'];
    				$real=$p['realpeso'];
					$o .="<tr>
			   			<td class=\"sized1\">$prod</td>
			   			<td class=\"sized2\">$disp</td>
			   			<td class=\"sized2\">$ent</td>
			   			<td class=\"sized2\">$merma</td>
			   			<td class=\"sized2\">$real</td>
			   			<td class=\"sized3\"><a class=\"edit\" sede =\"$sede\" ref=\"$id\">edit</a></td>
			   			</tr>";	
 		 		}
 				$o .= '</table>'; 
 				print $o;
 			} else {
 				print("<div align=\"left\" class=\"notification success png_bg\"><a href=\"#\" class=\"close\">
  						<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  						<div>
						Ya fue realizado el inventario diario, para revisarlo haga click <a href=\"userDailyStock.php\">aqui</a>!.
						</div>
						</div>");
 			}
	}
	
	function wooo_editsave($data){
	
		$id=$data['id'];
		$prod=$data['name'];
		$disp=$data['disp'];
		$ent=$data['ent'];
		$merma=$data['merma'];
		$real=$data['peso'];
		if(($merma + $real)==$ent){
			mysql_query("UPDATE `daily_sede` SET instock = '$disp', entrada='$ent', merma='$merma', realpeso='$real' WHERE id= '$id';");
		} else {
			print("<div align=\"left\" class=\"notification error png_bg\"><a href=\"#\" class=\"close\">
  				<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
				<div>
				La entrada debe ser igual a la suma de la merma mas el peso real utilizable.
				</div>
				</div>");
		}
	}
	
	function saveDaily($data){
	
		$id=$data['id'];
		$prod=$data['name'];
		$disp=$data['disp'];
		$ent=$data['ent'];
		$merma=$data['merma'];
		$real=$data['peso'];
		if(($merma + $real)==$ent){
			mysql_query("UPDATE `daily_sede` SET instock = '$disp', entrada='$ent', merma='$merma', realpeso='$real' WHERE id= '$id';");
		} else {
			print("<div align=\"left\" class=\"notification error png_bg\"><a href=\"#\" class=\"close\">
  				<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
				<div>
				La entrada debe ser igual a la suma de la merma mas el peso real utilizable.
				</div>
				</div>");
		}
	}
	
	function saveUnit($data){
		$id=$data['id'];
		$disp=$data['disp'];
		mysql_query("UPDATE `daily_sede` SET instock = '$disp' WHERE id= '$id';");
	}
	

	function check($data){
		$id=$data['id'];
		$prod=$data['name'];
		$date=$data['date'];
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE id='$id';");
		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
    		$disp=$p['instock'];
    		$ent=$p['entrada'];
    		$merma=$p['merma'];
    		$real=$p['realpeso'];
			$sede=$p['sede'];
			$units=$p['unidades'];
			if($ent==0||$real==0){
				print("<div align=\"left\" class=\"notification error png_bg\"><a href=\"#\" class=\"close\">
  				<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
				<div>
				No se puede guardar una entrada sin valores de Entrada y Peso Real Utilizable.
				</div>
				</div>");
			
			} else {
				mysql_query("UPDATE `entrada_sede` SET procesado = '1', entrada='$ent', merma='$merma', realpeso='$real' where producto='$prod' and date='$date' and sede='$sede';");
				$pesostock=$disp+$real;
				mysql_query("INSERT INTO `product_sede` (name, pesostock, sede, peso, critico, date, unidades) VALUES ('$prod', '$pesostock', '$sede', '1', '0', '$date', '-1')");
				mysql_query("DELETE FROM `daily_sede` WHERE id = '$id';");
			}
			
		}
	}
	
	function checkUnit($data){
		$id=$data['id'];
		$prod=$data['name'];
		$date=$data['date'];
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE id='$id';");
		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
    		$disp=$p['instock'];
			$sede=$p['sede'];
			$units=$p['unidades'];
			mysql_query("UPDATE `entrada_sede` SET procesado = '3', unidades='$units' where producto='$prod' and date='$date' and sede='$sede';");
			$unidades=$disp+$units;
			mysql_query("INSERT INTO `product_sede` (name, pesostock, sede, peso, critico, date, unidades) VALUES ('$prod', '-1', '$sede', '0', '0', '$date', '$units')");
			mysql_query("DELETE FROM `daily_sede` WHERE id = '$id';");
		}
	}
	
	function checkDaily($sede, $date){
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE sede='$sede';");
		while($p = mysql_fetch_assoc($q)){
			$id=$p['id'];
			$prod=$p['producto'];
    		$disp=$p['instock'];
    		$ent=$p['entrada'];
    		$merma=$p['merma'];
    		$real=$p['realpeso'];
			$sede=$p['sede'];
			$units=$p['unidades'];
			$pesostock=$disp+$real;
			mysql_query("INSERT INTO `entrada_sede` (`date` ,`producto` ,`procesado` ,`entrada` ,`merma` ,`realpeso` ,`unidades` ,`sede`)
						VALUES (CURDATE( ) , '$prod', '4', '$ent', '$merma', '$real', '$pesostock', '$sede')");
			mysql_query("INSERT INTO `product_sede` (name, pesostock, sede, peso, critico, date, unidades) VALUES ('$prod', '$pesostock', '$sede', '1', '1', 				'$date', '$units')");
			mysql_query("DELETE FROM `daily_sede` WHERE id = '$id';");
			Header("Location: userDaily.php");
		}
	}
	
	function wooo_editform($id, $sede){
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE id='$id';");

		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
    		$disp=$p['instock'];
    		$ent=$p['entrada'];
    		$merma=$p['merma'];
    		$real=$p['realpeso'];
			
			$o = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					
					$(\"#editar\").validate();
	
				});
	
			</script>
			<form id=\"editar\" method=\"post\">
					<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />			
					<fieldset>
					<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$prod\" />
					<br>
						<label>Producto: $prod</label> <br>
					<label>Disponible:</label>
					<input type=\"text\" id=\"disp\" name=\"disp\" value='$disp' class=\"text-input small-input required\" />
					<label>Entrada:</label>
					<input type=\"text\" id=\"ent\" name=\"ent\" value='$ent' class=\"text-input small-input required\" />
					<label>Merma:</label>
					<input type=\"text\" id=\"merma\" name=\"merma\" value='$merma' class=\"text-input small-input required\" />
					<label>Real:</label>
					<input type=\"text\" id=\"realpeso\" name=\"realpeso\" value='$real' class=\"text-input small-input required\" />
					</fieldset>
										
					<input class=\"button\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"Guardar\" />
				</form>";
			print $o;
		
		} // while
	}
	
	
	function editDaily($id, $sede){
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE id='$id';");

		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
    		$disp=$p['instock'];
    		$ent=$p['entrada'];
    		$merma=$p['merma'];
    		$real=$p['realpeso'];
			
			$o = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					
					$(\"#editar\").validate();
	
				});
	
			</script>
			<form id=\"editar\" method=\"post\">
					<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />			
					<fieldset>
					<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$prod\" />
					<br>
						<label>Producto: $prod</label> <br>
					<label>Disponible:</label>
					<input type=\"text\" id=\"disp\" name=\"disp\" value='$disp' class=\"text-input small-input required\" />
					<label>Entrada:</label>
					<input type=\"text\" id=\"ent\" name=\"ent\" value='$ent' class=\"text-input small-input required\" />
					<label>Merma:</label>
					<input type=\"text\" id=\"merma\" name=\"merma\" value='$merma' class=\"text-input small-input required\" />
					<label>Real:</label>
					<input type=\"text\" id=\"realpeso\" name=\"realpeso\" value='$real' class=\"text-input small-input required\" />
					</fieldset>
										
					<input class=\"button\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"Guardar\" />
				</form>";
			print $o;
		
		} // while
	}

	
	function editUnit($id, $sede){
		$q = mysql_query("SELECT * FROM `daily_sede` WHERE id='$id';");

		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
    		$disp=$p['instock'];
			
			$o = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					
					$(\"#editar\").validate();
	
				});
			</script>
			<form id=\"editar\" method=\"post\">
					<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />			
					<fieldset>
					<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$prod\" />
					<br>
						<label>Producto: $prod</label> <br>
					<label>Disponible:</label>
					<input type=\"text\" id=\"disp\" name=\"disp\" value='$disp' class=\"text-input small-input required\" />
										
					<input class=\"button\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"Guardar\" />
				</form>";
			print $o;
		
		} // while
	}

	function wooo_prod($data){
		$prod=$data['name'];
		$sede=$data['sede'];
		
		if(empty($prod)){
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El campo 'Producto' no puede estar Vacio!
				</div>
			</div>");
		
		} else {
			$q = mysql_query("SELECT count(*) FROM `product` where name='$prod';");
			$p = mysql_fetch_row($q);
			if($p[0]<1){
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod no existe en la Base de Datos.
				</div>
				</div>");
			} else {
				$q = mysql_query("SELECT count(*) FROM `entrada_sede` where producto='$prod' and sede='$sede';");
				$p = mysql_fetch_row($q);
				if($p[0]>0){
					$a = mysql_query("SELECT * FROM `product` where name = '$prod'");
					if($v = mysql_fetch_assoc($a)){
						$peso=$v['peso'];
						$critico=$v['critico'];
						if($peso<1 || $critico>0){
							$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt 								colstyle-alt paginate-20\">
							<tr>
			  					<th class=\"sortable-date\">Fecha</th>
			  	  				<th class=\"sortable-text\">Producto</th>
			  	  				<th class=\"sortable-numeric\">Cantidad</th>
			  	  			</tr>
			  	  			<tr>
			   					<td class=\"sized2\"></td>
			   					<td class=\"sized1\"></td>
			   					<td class=\"sized2\"></td>
			   				</tr>";
							$q = mysql_query("SELECT * FROM `entrada_sede` where producto = '$prod' and sede='$sede' order by date, producto;");
							while($p = mysql_fetch_assoc($q)){
    							$id=$p['date'];
    							$name=$p['producto'];
    							$cant=$p['unidades'];
								$o .="<tr>
			   					<td class=\"sized1\">$id</td>
			   					<td class=\"sized1\">$name</td>
			   					<td class=\"sized1\">$cant</td>
					   			</tr>";
 							}
 							$o .= '</table>'; 
 							print $o;
						} else {
							$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt 								colstyle-alt paginate-20\">
							<tr>
			  					<th class=\"sortable-date\">Fecha</th>
			  	  				<th class=\"sortable-text\">Producto</th>
			  	  				<th class=\"sortable-numeric\">Cantidad</th>
			  	  				<th class=\"sortable-numeric\">Peso de Entrada</th>
			  	  				<th class=\"sortable-numeric\">Merma</th>
			  	  				<th class=\"sortable-numeric\">Peso Real</th>
			  	  			</tr>
			  	  			<tr>
			   					<td class=\"sized2\"></td>
			   					<td class=\"sized1\"></td>
			   					<td class=\"sized2\"></td>
			   					<td class=\"sized2\"></td>
			   					<td class=\"sized2\"></td>
			   					<td class=\"sized2\"></td>
			   				</tr>";
			   				$a=0;
							$q = mysql_query("SELECT * FROM `entrada_sede` where producto = '$prod' and sede='$sede' order by date, producto;");
							while($p = mysql_fetch_assoc($q)){
    							$id=$p['date'];
    							$name=$p['producto'];
    							$cant=$p['unidades'];
    							$ent=$p['entrada'];
    							$merma=$p['merma'];
    							$real=$p['realpeso'];
    							$proc=$p['procesado'];
								if($proc<1){
									$a=1;
								}
								
								$o .="<tr>
			   			
			   					<td class=\"sized2\">$id</td>
			   					<td class=\"sized1\">$name</td>
			   					<td class=\"sized2\">$cant</td>
			   					<td class=\"sized2\">$ent</td>
			   					<td class=\"sized2\">$merma</td>
			   					<td class=\"sized2\">$real</td>
			   					</tr>";
 							}
 							$o .= '</table>'; 
 							print $o;
 							if($a>0){
 								echo("<br><br><div class=\"notification attention png_bg\">
								<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
								<div>
								Hay entradas pendientes por ser Procesadas. Para procesarlas haga click <a href=\"userEntry.php\">aqui</a>.
								</div>
								</div>");
 							}
 						}
 					}
				} else {
					echo("<div class=\"notification error png_bg\">
					<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						Error. El producto $prod no tiene ninguna entrada.
					</div>
					</div>");
				}
			}
		}
	}
	
	function fecha($data){
		$date=$data['date'];
		$sede=$data['sede'];
		
		if(empty($date)){
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El campo 'Fecha' no puede estar Vacio!
				</div>
			</div>");
		} else {
			$q = mysql_query("SELECT count(*) FROM `entrada_sede` where date='$date' and sede='$sede' and procesado='4';");
			$p = mysql_fetch_row($q);
			if($p[0]>0){
				$a = mysql_query("SELECT * FROM `entrada_sede` where date='$date' and sede='$sede' and procesado='4'");
				if($v = mysql_fetch_assoc($a)){
					$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt 								colstyle-alt paginate-20\">
					<tr>
			  			<th class=\"sortable-text\">Producto</th>
			  			<th class=\"sortable-numeric\">Disponible $date</th>
			  			<th class=\"sortable-numeric\">Peso de Entrada</th>
			  			<th class=\"sortable-numeric\">Merma</th>
			  			<th class=\"sortable-numeric\">Peso Real</th>
			  		</tr>
			  		<tr>
			   			<td class=\"sized1\"></td>
			   			<td class=\"sized1\"></td>
			   			<td class=\"sized2\"></td>
			   			<td class=\"sized2\"></td>
			   			<td class=\"sized2\"></td>
			   		</tr>";
			   		$a=0;
					$q = mysql_query("SELECT * FROM `entrada_sede` where date='$date' and sede='$sede' and procesado='4' order by producto;");
					while($p = mysql_fetch_assoc($q)){
    					$name=$p['producto'];
    					$cant=$p['unidades'];
    					$ent=$p['entrada'];
    					$merma=$p['merma'];
    					$real=$p['realpeso'];
						
						$o .="<tr>
			   		
			   				<td class=\"sized1\">$name</td>
			   				<td class=\"sized2\">$cant</td>
			   				<td class=\"sized2\">$ent</td>
			   				<td class=\"sized2\">$merma</td>
			   				<td class=\"sized2\">$real</td>
			   				</tr>";
 					}
 					$o .= '</table>'; 
 					print $o;
 				}
			} else {
				echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. La fecha $date no tiene Inventario Diario Realizado.
				</div>
				</div>");
			}
		}
	}
?>