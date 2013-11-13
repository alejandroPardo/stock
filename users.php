<?php
	include ("dbConfig.php");
	if ( $_GET["op"] == "add" ){
		if (!$_POST["username"] || !$_POST["password"] || !$_POST["name"] || !$_POST["lastname"] || !$_POST["sede"]){
			Header("Location: controlUsers.php?op=ef");
		} else {
  			$username=$_POST['username'];
  			$password=$_POST['password'];
  			$name=$_POST['name'];
  			$lastname=$_POST['lastname'];
  			$sede=$_POST['sede'];
  			$password=md5($password);
  						
  			$count=mysql_query("select `id` from `sede` where place='$sede';");
			while ($row = mysql_fetch_assoc($count)) {
				$place=$row['id'];
			}
  						
  			$q = "INSERT INTO `user` (`username`,`name`,`lastname`,`sede`,`password`,`rank`) VALUES ('$username','$name','$lastname','$place','$password', '2');";

  			$r = mysql_query($q);
  
  			if ( !mysql_insert_id() ){
        		Header("Location: controlUsers.php?op=edb");
        	} else {
        		Header("Location: controlUsers.php?op=ok");
        	}
        				
        }
	} else {
		if ( $_GET["op"] == "del" ){
			$user=$_POST['user'];
			
			$q = "DELETE FROM `user` WHERE `username` = '$user';";

  			$r = mysql_query($q);
 
        	Header("Location: controlUsers.php?op=okd");
		} else {
			if ( $_GET["op"] == "mod" ){
			
				if (!$_POST["oldp"] || !$_POST["newp1"] || !$_POST["newp2"]){
					Header("Location: userAccount.php?op=err");
				} else {
  					$username=$_POST['user'];
  					$old=$_POST['oldp'];
  					$new1=$_POST['newp1'];
  					$new2=$_POST['newp2'];
  					$old=md5($old);
  					
  					$count=mysql_query("select * from `user` where username='$username';");
					while ($row = mysql_fetch_assoc($count)) {
						$password=$row['password'];
						if($password==$old){
							if($new1==$new2){
								$pass=md5($new1);
								mysql_query("UPDATE `user` SET `password` = '$pass' WHERE `username`='$username';");
								Header("Location: userAccount.php?op=ok");
							} else {
								Header("Location: userAccount.php?op=errn");
							}
						} else {
							Header("Location: userAccount.php?op=errp");
						}
					}
          		}			
       	 	}
		}
	}
?>