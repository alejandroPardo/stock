<?php
	session_start();
	include ("dbConfig.php");
	if($_GET["op"]=="del"){
		$id=$_GET['mess'];
		$where=$_GET['wh'];
		mysql_query("DELETE FROM `message` WHERE id = '$id';");
		Header("Location: $where?msg=mdel");
	} else {
		if (!$_POST["mensaje"]){
			Header("Location: $where?msg=err");
		} else {
			$mensaje=$_POST["mensaje"];
			$receiver=$_POST["receiver"];
			$where=$_POST["where"];
			$sender=$_SESSION["valid_user"];
			
  			if($receiver=="Todos"){
  				$count=mysql_query("select * from `user` where username!='$sender'");
				while ($row = mysql_fetch_assoc($count)) {
					$rec=$row['username'];
					mysql_query("INSERT INTO `message` (`sender`,`receiver`,`date_sent`,`mensaje`,`read`) VALUES ('$sender','$rec',CURDATE(),'$mensaje','0');");
				}
  			} else {
  				mysql_query("INSERT INTO `message` (`sender`,`receiver`,`date_sent`,`mensaje`,`read`) VALUES ('$sender','$receiver',CURDATE(),'$mensaje','0');");
  			}
        	Header("Location: $where?msg=good");	
		}
	}
?>