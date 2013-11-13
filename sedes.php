<?php
	include ("dbConfig.php");
	if ( $_GET["op"] == "add" ){
		if (!$_POST["name"]){
			Header("Location: controlSede.php?op=ef");
		} else {
  			$name=$_POST['name'];
  						
  			$count=mysql_query("select count(*) from `sede` where place='$sede';");
			$row = mysql_fetch_row($count);
			if($row[0]>0){
				Header("Location: controlSede.php?op=edb");
			} else {
				$q = "INSERT INTO `sede` (`place`) VALUES ('$name');";

  				$r = mysql_query($q);
  
        		Header("Location: controlSede.php?op=ok");
			}
        }
	} else {
		if ( $_GET["op"] == "del" ){
			$name=$_POST['name'];
			
			$q = "DELETE FROM `sede` WHERE `place` = '$name';";

  			$r = mysql_query($q);
 
        	Header("Location: controlSede.php?op=okd");
		}
	}
?>