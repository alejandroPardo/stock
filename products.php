<?php
	include ("dbConfig.php");
	if ( $_GET["op"] == "add" ){
		if (!$_POST["name"] || !$_POST["desc"] || (!$_POST['minimo'] && $_POST['minimo']!=0)){
			Header("Location: controlProducts.php?op=ef");
		} else {
  			$desc=$_POST['desc'];
  			$minimo=$_POST['minimo'];
  			$name=$_POST['name'];
  			$instock=$_POST['instock'];
  			if($_POST['peso']=='yes'){$peso=1;} else {$peso=0;}
  			if($_POST['crit']=='yes'){$crit=1;} else {$crit=0;}
  			
  			$q = "INSERT INTO `product` (`name`,`descripcion`,`instock`,`minimo`,`sede`,`peso`,`critico`) VALUES ('$name','$desc','$instock','$minimo','1','$peso','$crit');";

  			$r = mysql_query($q);

  			if(!mysql_insert_id()){
        		Header("Location: controlProducts.php?op=edb");
        	} else {
        		Header("Location: controlProducts.php?op=ok");
        	}
        }
	} else {
		if ( $_GET["op"] == "del" ){
			if (!$_POST["prod"]){
				Header("Location: controlProducts.php?op=ed");
			} else {
				$prod=$_POST["prod"];
				$count=mysql_query("select count(*) from `product` where `name`='$prod';");
				$row = mysql_fetch_row($count);
   				$messages = $row[0];
   				if($row[0]==1){
   					$q = "DELETE FROM `product` WHERE `name` = '$prod';";
   					$r = mysql_query($q);
        			Header("Location: controlProducts.php?op=okd");
   				} else {
   					Header("Location: controlProducts.php?op=ene");
   				}
			}
		} else {
			if ( $_GET["op"] == "upd" ){
				if (!$_POST["name"] || !$_POST["desc"] || !$_POST["minimo"]){
					Header("Location: controlProducts.php?op=euf");
				} else {
  					$desc=$_POST['desc'];
  					$minimo=$_POST['minimo'];
  					$name=$_POST['name'];
  					if($_POST['peso']=='yes'){$peso=1;} else {$peso=0;}
  					if($_POST['crit']=='yes'){$crit=1;} else {$crit=0;}

  					$q = "UPDATE `product` SET `name`='$name' ,`descripcion`='$desc', `minimo`='$minimo', `peso`='$peso', `critico`='$crit'  WHERE `name`='$name';";

  					$r = mysql_query($q);
  
        			Header("Location: controlProducts.php?op=uok");
        		}
			}
		}
	}
?>