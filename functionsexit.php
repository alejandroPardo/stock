<?php
	
	include_once 'dbConfig.php'; 
	
	/** parse AJAX GET REQUESTS ****/
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
		
		if (isset($_GET)) {
		
			if (isset($_GET['mode'])) {
				
				switch ($_GET['mode']){
					
					case 'list' :
						wooolist();
					break;
					
					case 'add' :
						wooo_addform();
					break;
					
					case 'edit' :
						wooo_editform($_GET['id']);
					break;
					
					case 'delete' :
						wooo_delete($_POST);
					break;
					
					case 'save' :
						wooo_save($_POST);
					break;
					
					case 'prod' :
						wooo_prod($_POST);
					break;
					
					case 'sede' :
						wooo_sede($_POST);
					break;
					
					case 'sedeStock' :
						sedeStock($_POST);
					break;
					
					case 'sedePending' :
						sedePending($_POST);
					break;
					
					case 'editsave' :
						wooo_editsave($_POST);
					break;
					
					case 'dateSede' :
						fecha($_POST);
					break;
					
					case 'prodSede' :
						prodSede($_POST);
					break;
					
					case 'p_data':
						p_data($_GET['id'],$_GET['opt'],$_GET['price']);
					break;
					
					case 'gateway':
						wooo_gateway();
					break;
									
				} // switch
				
			} // mode	
			
		}
	} // ajax
	
	
	
	function wooolist() {

		$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt paginate-20\">
				<tr>
			  	  <th>ID</th>
			  	  <th>Producto</th>
			  	  <th>Cantidad</th>
			  	  <th></th>
			  	  </tr>
			  	  <tr>
			   	<td class=\"sized1\"></td>
			   	<td class=\"sized1\"></td>
			   	<td class=\"sized1\"></td>
			   	<td class=\"sized1\"></td>
			   	</tr>";
		$q = mysql_query("SELECT * FROM `guia`;");

		while($p = mysql_fetch_assoc($q)){
    		$id=$p['id'];
    		$name=$p['producto'];
    		$cant=$p['cantidad'];
			$o .="<tr>
			   <td class=\"sized1\">$id</td>
			   <td class=\"sized1\">$name</td>
			   <td class=\"sized1\">$cant</td>
			   <td class=\"sized1\"><a class=\"delete \" name=\"$name\" ref=\"$id\">delete</a><a class=\"edit\" ref=\"$id\">edit</a></td>
			   </tr>";	
 		 }
 		$o .= '</table>'; 
 		print $o;
		
	}
	
	function wooo_addform(){
		$o = "
		<form id=\"addproduct\" method=\"post\">				
			<br>
			<label>Producto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad</label>
			<select id=\"name\" name=\"dropdown\" class=\"small-input\" >";
				$q = mysql_query("SELECT count(*) FROM `product`;");
				$p = mysql_fetch_row($q);
				if($p[0]<1){
					$o.= "<div class=\"notification error png_bg\">
							<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
							<div>
								Error. La Base de Datos esta Vacia.
							</div>
						</div>";
				} else {
					$q = mysql_query("SELECT * FROM `product` ORDER BY `name`;");
						while($p = mysql_fetch_assoc($q)){
							$name=$p['name'];
							$cant=$p['instock'];
							$o .= "<option value=\"$name\">$name - Disp: $cant</option>";
						}
				}
				$o .= "</SELECT>
				<input type=\"text\" id=\"price\" name=\"price\" class=\"text-input small-input\" />
				<br>
				<input class=\"button\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"Agregar\" />
				</form>";
		print $o;
	}
	
	function wooo_prod($data){
		$prod=$data['name'];
		
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
			$nohay=0;
			if($p[0]<1){
			$nohay=1;
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod no existe en la Base de Datos.
				</div>
				</div>");
			} else {
				$q = mysql_query("SELECT count(*) FROM `salida` where producto='$prod';");
				$p = mysql_fetch_row($q);
				if($p[0]>0){
					$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt 					paginate-20\">
					<tr>
			  		<th class=\"sortable-date\">Fecha</th>
			  	  	<th class=\"sortable-text\">Producto</th>
			  	  	<th class=\"sortable-numeric\">Cantidad</th>
			  	  	<th class=\"sortable-text\">Numero de Factura</th>
			  	  	<th class=\"sortable-text\">Sede</th>
			  	  	</tr>
			  	  	<tr>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized1\"></td>
			   		</tr>";
					$q = mysql_query("SELECT * FROM `salida` where producto = '$prod' order by date, producto;");
					while($p = mysql_fetch_assoc($q)){
    					$id=$p['date'];
    					$name=$p['producto'];
    					$cant=$p['cantidad'];
    					$fact=$p['guia'];
    					$prov=$p['sede'];
						$o .="<tr>
			   			<td class=\"sized1\">$id</td>
			   			<td class=\"sized1\">$name</td>
			   			<td class=\"sized1\">$cant</td>
			   			<td class=\"sized1\">$fact</td>";
			   			
        				$count=mysql_query("select * from `sede` where id = '$prov'");
						while ($s = mysql_fetch_assoc($count)) {
							$sede=$s['place'];
						}
			   			$o .="<td class=\"sized1\">$sede</td>
			   			</tr>";
 					}
 					$o .= '</table>'; 
 					print $o;
				} else {
					$nohay=1;
					echo("<div class=\"notification error png_bg\">
					<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						Error. El producto $prod no tiene ninguna salida. Para agregar una salida haga click <a href=\"exitAdmin.php\">aqui</a>.
					</div>
					</div>");
				}
			}
			
		}
		if($nohay==0){
			echo("<br>
			<br>			
			<a class=\"button\" target=\"_blank\" href=\"reports.php?id=5&cols=4&prod=$prod\">Exportar</a>");
		}
	}
	
	function wooo_sede($data){
		$prod=$data['name'];
		$nohay=0;
			$q = mysql_query("SELECT * FROM `sede` where place='$prod';");
			while($p = mysql_fetch_assoc($q)){
				$sede=$p['id'];
			}
				$q = mysql_query("SELECT count(*) FROM `salida` where sede='$sede';");
				$p = mysql_fetch_row($q);
				if($p[0]>0){
					$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt 					paginate-20\">
					<tr>
			  		<th class=\"sortable-date\">Fecha</th>
			  	  	<th class=\"sortable-text\">Producto</th>
			  	  	<th class=\"sortable-numeric\">Cantidad</th>
			  	  	<th class=\"sortable-text\">Numero de Factura</th>
			  	  	<th class=\"sortable-text\">Sede</th>
			  	  	</tr>
			  	  	<tr>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized1\"></td>
			   		</tr>";
					$q = mysql_query("SELECT * FROM `salida` where sede = '$sede' order by date, producto;");
					while($p = mysql_fetch_assoc($q)){
    					$id=$p['date'];
    					$name=$p['producto'];
    					$cant=$p['cantidad'];
    					$fact=$p['guia'];
    					$prov=$p['sede'];
						$o .="<tr>
			   			<td class=\"sized1\">$id</td>
			   			<td class=\"sized1\">$name</td>
			   			<td class=\"sized1\">$cant</td>
			   			<td class=\"sized1\">$fact</td>
			   			<td class=\"sized1\">$prod</td>
			   			</tr>";
 					}
 					$o .= '</table>'; 
 					print $o;
				} else {
					$nohay=1;
					echo("<div class=\"notification error png_bg\">
					<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						Error. La sede $prod no tiene ninguna salida. Para agregar una salida haga click <a href=\"exitAdmin.php\">aqui</a>.
					</div>
					</div>");
				}
		if($nohay==0){
			echo("<br>
			<br>			
			<a class=\"button\" target=\"_blank\" href=\"reports.php?id=6&cols=4&sede=$prod\">Exportar</a>");
		}
	}
	
	function sedeStock($data){
		$prod=$data['name'];
		$q = mysql_query("SELECT * FROM `sede` where place='$prod';");
		while($p = mysql_fetch_assoc($q)){
			$sede=$p['id'];
		}
		$q = mysql_query("SELECT count(*) FROM `product_sede` where sede='$sede';");
		$p = mysql_fetch_row($q);
		if($p[0]>0){
			$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt 					paginate-20 sortable-onload-0\">
			<tr>
			<th class=\"sortable-text\">Producto</th>
		  	<th class=\"sortable-date\">Fecha de Ultima Entrada</th>
		  	<th class=\"sortable-numeric\">Peso en Ultima Entrada</th>
	  	 	<th class=\"sortable-numeric\">Cantidad en Ultima Entrada</th>
	  	 	</tr>
	  	  	<tr>
	   		<td class=\"sized1\"></td>
	   		<td class=\"sized1\"></td>
	   		<td class=\"sized1\"></td>
	   		<td class=\"sized1\"></td>
	   		</tr>";
	   		
  			$count=mysql_query("select DISTINCT name from product_sede where sede='$sede'");
  			while ($row = mysql_fetch_assoc($count)) {
  				$name=$row['name'];
  				$q=mysql_query("SELECT * FROM product_sede WHERE sede='$sede' AND name = '$name' AND date = (SELECT MAX( date )FROM 											product_sede where name = '$name' AND sede='$sede')");
  				while ($rs = mysql_fetch_assoc($q)) {
       				$o .= "<tr><td class=\"sized1\" >$rs[name]</td>";
       				$o .= "<td class=\"sized1\">$rs[date]</td>";
       				if($rs['pesostock']==-1){
       					$o .= "<td class=\"sized1\">No Aplica</td>";
       				} else {
       					$o .= "<td class=\"sized1\">$rs[pesostock]</td>";
       				}
       				if($rs['unidades']==-1){
       					$o .= "<td class=\"sized1\">No Aplica</td></tr>";
       				} else {
       					$o .= "<td class=\"sized1\">$rs[unidades]</td></tr>";
       				}
       			}
       		}
       		print $o;
       	} else {
  			print("<div align=\"left\" class=\"notification information png_bg\"><a href=\"#\" class=\"close\">
  			<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  			<div>
			No hay productos disponibles en la sede $prod. 
			</div>
			</div>");
       	}
	}
	
	
	function sedePending($data){
		$prod=$data['name'];
		$q = mysql_query("SELECT * FROM `sede` where place='$prod';");
		while($p = mysql_fetch_assoc($q)){
			$sede=$p['id'];
		}
		$q = mysql_query("SELECT count(*) FROM `entrada_sede` where sede='$sede' and (procesado='0' OR procesado='2');");
		$p = mysql_fetch_row($q);
		if($p[0]>0){
			$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt 					paginate-20 sortable-onload-0\">
			<tr>
			<th class=\"sortable-text\">Producto</th>
		  	<th class=\"sortable-date\">Fecha de Entrada</th>
	  	 	<th class=\"sortable-numeric\">Unidades</th>
	  	 	</tr>
	  	  	<tr>
	   		<td class=\"sized1\"></td>
	   		<td class=\"sized1\"></td>
	   		<td class=\"sized1\"></td>
	   		</tr>";
  			$q=mysql_query("SELECT * FROM entrada_sede where sede='$sede' and (procesado='0' OR procesado='2')");
  			while ($rs = mysql_fetch_assoc($q)) {
       			$o .= "<tr><td class=\"sized1\" >$rs[producto]</td>";
       			$o .= "<td class=\"sized1\">$rs[date]</td>";
       			$o .= "<td class=\"sized1\">$rs[unidades]</td>";
       		}
       		print $o;
       	} else {
  			print("<div align=\"left\" class=\"notification information png_bg\"><a href=\"#\" class=\"close\">
  			<img src=\"resources/images/icons/cross_grey_small.png\" title=\"Notificacion\" alt=\"close\" /></a>
  			<div>
			No hay productos por procesar en la sede $prod. 
			</div>
			</div>");
       	}
	}

			
	function wooo_save($data){
		$prod=$data['name'];
		$cant=$data['price'];
		if(empty($prod) || empty($cant)){
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. Los campos 'Producto' y 'Cantidad' no pueden estar Vacios!
				</div>
			</div>");
		
		} else {
			$q = mysql_query("SELECT count(*) FROM `guia` WHERE producto='$prod'");
			$p = mysql_fetch_row($q);
			if($p[0]>0){
				echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. Este Producto ya esta agregado a la factura. Si desea modificar la cantidad de elementos a agregar haga click en 'Editar' en la tabla 					de Productos.
				</div>
				</div>");
			} else {
			
			
			$q = mysql_query("SELECT count(*) FROM `product` where name='$prod';");
			$p = mysql_fetch_row($q);
			if($p[0]>0){
				$q = mysql_query("SELECT * FROM `product` where name='$prod';");
				while($p = mysql_fetch_assoc($q)){
					if($p['instock']<$cant){
						$instock=$p['instock'];
						echo("<div class=\"notification error png_bg\">
						<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
						<div>
							Error. El producto $prod solo tiene disponibles $instock unidades.
						</div>
						</div>");
					
					} else {
						mysql_query("INSERT INTO `guia` (producto, cantidad) VALUES ('$prod', '$cant')");
					}
				}
				
			} else {
				echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod no existe en la Base de Datos.
				</div>
				</div>");
			}
			}
		}
		
		
	}


	function wooo_editsave($data){
		$id=$data['id'];
		$prod=$data['name'];
		
		$cant=$data['price'];
				
		$q = mysql_query("SELECT * FROM `product` where name='$prod';");
		while($p = mysql_fetch_assoc($q)){
			if($p['instock']<$cant){
				$instock=$p['instock'];
				echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod solo tiene disponibles $instock unidades.
				</div>
				</div>");
				
			} else {
		
			mysql_query("UPDATE `guia` SET producto = '$prod', cantidad = '$cant' WHERE id= '$id';");
			}
		}
	}
	

	function wooo_delete($data){
			
		mysql_query('DELETE FROM `guia` WHERE id = "'.$_POST['id'].'";');
	}
	
	function wooo_editform($id){

		/* retrieve product */
		
		$q = mysql_query("SELECT * FROM `guia` WHERE id='$id';");

		while($p = mysql_fetch_assoc($q)){
			$prod=$p['producto'];
			$cant=$p['cantidad'];
			$o = "
			
			<script type=\"text/javascript\">
				$(document).ready(function(){
					
					$(\"#editproduct\").validate();
	
				});
	
			</script>
			
			<form id=\"editproduct\" method=\"post\">
					<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />			
					<fieldset>
					<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$prod\" />
					<br>
						<label>Producto: $prod</label> <br>
					<label>Cantidad</label>
					<input type=\"text\" id=\"price\" name=\"price\" value='$cant' class=\"text-input small-input required\" />
					</fieldset>
										
					<input class=\"button\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"Guardar\" />
				</form>";
			

			
			print $o;
		

		} // while
	}
	
	function fecha($data){
		$date=$data['date'];
		$place=$data['sede'];
		$nohay=0;
		$q = mysql_query("SELECT * FROM `sede` where place='$place';");
		while($p = mysql_fetch_assoc($q)){
			$sede=$p['id'];
		}
		
		if(empty($date)){
			$nohay=1;
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
				$nohay=1;
				echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. La fecha $date no tiene Inventario Diario Realizado.
				</div>
				</div>");
			}
		}
		if($nohay==0){
			echo("<br>
			<br>			
			<a class=\"button\" target=\"_blank\" href=\"reports.php?id=7&cols=4&fecha=$date&sede=$sede\">Exportar</a>");
		}
	}
		
	function prodSede($data){
		$prod=$data['prod'];
		$place=$data['sede'];
		$q = mysql_query("SELECT * FROM `sede` where place='$place';");
		while($p = mysql_fetch_assoc($q)){
			$sede=$p['id'];
		}
		
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
						if($peso<1){
							if($v['critico']==2){
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
 							}
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
									
								} else {
									$o .="<tr>
			   			
			   						<td class=\"sized2\">$id</td>
			   						<td class=\"sized1\">$name</td>
			   						<td class=\"sized2\">$cant</td>
			   						<td class=\"sized2\">$ent</td>
			   						<td class=\"sized2\">$merma</td>
			   						<td class=\"sized2\">$real</td>
			   						</tr>";
			   					}
 							}
 							$o .= '</table>'; 
 							print $o;
  						}
 					}
				} else {
					echo("<div class=\"notification error png_bg\">
					<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						Error. El producto $prod no tiene ninguna entrada en $place.
					</div>
					</div>");
				}
			}
		}
	}

?>