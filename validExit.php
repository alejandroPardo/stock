<?php
	include "dbConfig.php";
	
	$fact=$_POST['fact'];
	$prov=$_POST['sede'];
	if(empty($fact)){
		Header("Location: exitAdmin.php?op=error");
	} else {
		$count=mysql_query("select * from `sede` where place = '$prov'");
		while ($row = mysql_fetch_assoc($count)) {
			$sede = $row['id'];	
		}
		$count=mysql_query("select * from `guia`");
		while ($row = mysql_fetch_assoc($count)) {
			$name=$row['producto'];
			$cant=$row['cantidad'];
			$q=mysql_query("select * from `product` where `name`='$name'");
			while ($w = mysql_fetch_assoc($q)) {
				$instock=$w['instock'];
				$peso=$w['peso'];
				$critico=$w['critico'];
				$instock=$instock-$cant;
				mysql_query("UPDATE `product` SET instock = '$instock' WHERE name= '$name';");
				mysql_query("INSERT INTO `salida` (`guia`,`date`,`sede`,`cantidad`,`producto`,`from`) VALUES ('$fact', CURDATE( ) , '$sede', '$cant', '$name', '1')");
				if($critico<1){
					if($peso>0){
						mysql_query("INSERT INTO `entrada_sede` (`date` ,`producto` ,`procesado` ,`entrada` ,`merma` ,`realpeso` ,`unidades` ,`sede`)
						VALUES (CURDATE( ) , '$name', '0', '0', '0', '0', '$cant', '$sede')");
					} else {
						mysql_query("INSERT INTO `entrada_sede` (`date` ,`producto` ,`procesado` ,`entrada` ,`merma` ,`realpeso` ,`unidades` ,`sede`)
						VALUES (CURDATE( ) , '$name', '2', '0', '0', '0', '$cant', '$sede')");
					}
				}
			}
		}
		mysql_query("TRUNCATE `guia`");
		Header("Location: exitAdmin.php?op=ok");
		
	}
?>