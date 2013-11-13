<?php
	include "dbConfig.php";
	
	$fact=$_POST['fact'];
	$prov=$_POST['prov'];
	if(empty($fact)){
		Header("Location: entryAdmin.php?op=error");
	} else {
		if(empty($prov)){
			$prov = "no especificado";
		}
		$count=mysql_query("select * from `factura`");
		while ($row = mysql_fetch_assoc($count)) {
			$name=$row['producto'];
			$cant=$row['cantidad'];
			$q=mysql_query("select * from `product` where `name`='$name'");
			while ($w = mysql_fetch_assoc($q)) {
				$instock=$w['instock'];
				$instock=$instock+$cant;
				mysql_query("UPDATE `product` SET instock = '$instock' WHERE name= '$name';");
			}

			mysql_query("INSERT INTO `entrada` (`factura`,`date`,`proveedor`,`cantidad`,`producto`) VALUES ('$fact', CURDATE( ) , '$prov', '$cant', '$name')");
			
		}
		mysql_query("TRUNCATE `factura`");
		Header("Location: entryAdmin.php?op=ok");
	}
?>