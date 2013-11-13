<?php
	include "dbConfig.php";
	$sede=$_GET['sede'];
	$date=$_GET['date'];
	$q = mysql_query("SELECT * FROM `daily_sede` WHERE sede='$sede';");
		while($p = mysql_fetch_assoc($q)){
			$id=$p['id'];
			$prod=$p['producto'];
    		$disp=$p['instock'];
    		$ent=$p['entrada'];
    		$merma=$p['merma'];
    		$real=$p['realpeso'];
			$units=$p['unidades'];
			mysql_query("INSERT INTO `entrada_sede` (`date` ,`producto` ,`procesado` ,`entrada` ,`merma` ,`realpeso` ,`unidades` ,`sede`)
						VALUES (CURDATE( ) , '$prod', '4', '$ent', '$merma', '$real', '$units', '$sede')");
			
			$pesostock=$disp+$real;
			mysql_query("INSERT INTO `product_sede` (name, pesostock, sede, peso, critico, date, unidades) VALUES ('$prod', '$pesostock', '$sede', '1', '1', 				CURDATE( ), '$units')");
			mysql_query("DELETE FROM `daily_sede` WHERE id = '$id';");
			print "<script>";
			print " self.location='userDaily.php';";
			print "</script>";
		}
?>