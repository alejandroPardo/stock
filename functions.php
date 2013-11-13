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
					
					case 'editprod' :
						wooo_editprod($_POST);
					break;
					
					case 'editsave' :
						wooo_editsave($_POST);
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
		$q = mysql_query("SELECT * FROM `factura`;");

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
		$nohay=0;
		if(empty($prod)){
			$nohay=1;
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El campos 'Producto' no puede estar Vacio!
				</div>
			</div>");
		
		} else {
			$q = mysql_query("SELECT count(*) FROM `product` where name='$prod';");
			$p = mysql_fetch_row($q);
			if($p[0]<1){
			$nohay=1;
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod no existe en la Base de Datos.
				</div>
				</div>");
			} else {
				$q = mysql_query("SELECT count(*) FROM `entrada` where producto='$prod';");
				$p = mysql_fetch_row($q);
				if($p[0]>0){
					$o = "<table style=\"margin-left:auto;margin-right:auto;text-align:left\"cellpadding=\"0\" cellspacing=\"0\" class=\"rowstyle-alt colstyle-alt 					paginate-20\">
					<tr>
			  		<th class=\"sortable-date\">Fecha</th>
			  	  	<th class=\"sortable-text\">Producto</th>
			  	  	<th class=\"sortable-numeric\">Cantidad</th>
			  	  	<th class=\"sortable-text\">Numero de Factura</th>
			  	  	<th class=\"sortable-text\">Proveedor</th>
			  	  	</tr>
			  	  	<tr>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized2\"></td>
			   		<td class=\"sized1\"></td>
			   		<td class=\"sized1\"></td>
			   		</tr>";
					$q = mysql_query("SELECT * FROM `entrada` where producto = '$prod' order by date, producto;");

					while($p = mysql_fetch_assoc($q)){
    					$id=$p['date'];
    					$name=$p['producto'];
    					$cant=$p['cantidad'];
    					$fact=$p['factura'];
    					$prov=$p['proveedor'];
						$o .="<tr>
			   			<td class=\"sized1\">$id</td>
			   			<td class=\"sized1\">$name</td>
			   			<td class=\"sized1\">$cant</td>
			   			<td class=\"sized1\">$fact</td>
			   			<td class=\"sized1\">$prov</td>
			   			</tr>";	
 					}
 					$o .= '</table>'; 
 					print $o;
				} else {
					$nohay=1;
					echo("<div class=\"notification error png_bg\">
					<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
					<div>
						Error. El producto $prod no tiene ninguna entrada. Para agregar una entrada haga click <a href=\"entryAdmin.php\">aqui</a>.
					</div>
					</div>");
				}
			}
			
		}
		if($nohay==0){
			echo("<br>
			<br>			
			<a class=\"button\" target=\"_blank\" href=\"reports.php?id=3&cols=4&prod=$prod\">Exportar</a>");
		}
		
	}
	
	function wooo_editprod($data){
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
			if($p[0]<1){
			echo("<div class=\"notification error png_bg\">
				<a class=\"close\"><img src=\"resources/images/icons/cross_grey_small.png\" title=\"Close this notification\" alt=\"close\" /></a>
				<div>
					Error. El producto $prod no existe en la Base de Datos.
				</div>
				</div>");
			} else {
				$q = mysql_query("SELECT * FROM `product` where name='$prod';");
				while($p = mysql_fetch_assoc($q)){
					$name=$p['name'];
					$desc=$p['descripcion'];
					$minimo=$p['minimo'];
					$peso=$p['peso'];
					$crit=$p['critico'];
					print ("<form action=\"products.php?op=upd\" method=\"POST\">\n
  							<fieldset>
  							<p>
								<label>Nombre del Producto: <br><h2>$name</h2></label>
								<input type=\"hidden\" id=\"medium-input\" name=\"name\" value=\"$name\" />									
							</p>
							<p>
								<label>Descripcion:</label>
								<input class=\"text-input large-input\" type=\"text\" id=\"large-input\" name=\"desc\" value=\"$desc\" />									
							</p>
  							<p>
								<label>Minimo:</label>
								<input class=\"text-input medium-input\" type=\"text\" id=\"medium-input\" name=\"minimo\" value=\"$minimo\"/>								
							</p>
							<p>
								<label>Opciones de Producto</label>
								
								<input type=\"checkbox\" name=\"peso\" value=\"yes\"");if($peso=='1'){ echo "checked=\"true\""; } 
								echo("/> El producto es Pesable?
								<br>
								<input type=\"checkbox\" name=\"crit\" value=\"yes\"");if($crit=='1'){ echo "checked=\"true\""; }
								echo("/> El producto es Critico?
							</p>
							<p>
								<input class=\"button\" type=\"submit\" value=\"Guardar\" />
							</p>
								
							</fieldset>			
							<div class=\"clear\"></div><!-- End .clear -->
							
							</form>");
				}
			}
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
			$q = mysql_query("SELECT count(*) FROM `factura` WHERE producto='$prod'");
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
					mysql_query("INSERT INTO `factura` (producto, cantidad) VALUES ('$prod', '$cant')");
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
				
		mysql_query("UPDATE `factura` SET producto = '$prod', cantidad = '$cant' WHERE id= '$id';");
		
	}
	

	function wooo_delete($data){
			
		mysql_query('DELETE FROM `factura` WHERE id = "'.$_POST['id'].'";');
	}
	
	function wooo_editform($id){

		/* retrieve product */
		
		$q = mysql_query("SELECT * FROM `factura` WHERE id='$id';");

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

?>