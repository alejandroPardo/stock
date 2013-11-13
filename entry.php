<?php
	session_start();
	// dBase file
    include "dbConfig.php";
	//not logged
	if (!$_SESSION["valid_user"]){Header("Location: index.php");}
	$id=$_SESSION["valid_user"];
	$count=mysql_query("select * from `user` where `username`='$id';");
	while ($row = mysql_fetch_assoc($count)) {
    	$sede= $row['sede'];
	}
	mysql_query("DELETE FROM `daily_sede` WHERE sede = '$sede';");
	
	if ($_GET["op"] == "peso"){
		$q = mysql_query("SELECT * FROM `entrada_sede` WHERE procesado='0' AND sede='$sede';");
		while($p = mysql_fetch_assoc($q)){  
			$prod=$p['producto'];
			$disp='0';
			$ent=$p['entrada'];
			$merma=$p['merma'];
			$real=$p['realpeso'];
			$date=$p['date'];
			$sede=$p['sede'];
			$q2 = mysql_query("SELECT count(*) FROM `product_sede` where name='$prod' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede where name='$prod' and sede='$sede');");
			$a2 = mysql_fetch_row($q2);
			if($a2[0]>0){
				$q1 = mysql_query("SELECT * FROM `product_sede` where name='$prod' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede where name='$prod' and sede='$sede');");
				while($p1 = mysql_fetch_assoc($q1)){
					$disp=$p1['pesostock'];
				}
			}	
			mysql_query("INSERT INTO `daily_sede` (producto, instock, entrada, merma, realpeso, unidades, date, sede) VALUES ('$prod', '$disp', '$ent', 					'$merma', '$real', '0', '$date', '$sede')");
		}
		Header("Location: userEntry.php?op=done");
	} else {
		if ($_GET["op"] == "unit"){
			$q = mysql_query("SELECT * FROM `entrada_sede` WHERE procesado='2' AND sede='$sede';");
			while($p = mysql_fetch_assoc($q)){  
				$prod=$p['producto'];
				$disp='0';
				$ent=$p['unidades'];
				$date=$p['date'];
				$sede=$p['sede'];
				$q2 = mysql_query("SELECT count(*) FROM `product_sede` where name='$prod' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede where 						name='$prod' and sede='$sede');");
				$a2 = mysql_fetch_row($q2);
				if($a2[0]>0){
					$q1 = mysql_query("SELECT * FROM `product_sede` where name='$prod' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede where 							name='$prod' and sede='$sede');");
					while($p1 = mysql_fetch_assoc($q1)){
						$disp=$p1['unidades'];
					}
				}	
				mysql_query("INSERT INTO `daily_sede` (producto, instock, entrada, merma, realpeso, unidades, date, sede) VALUES ('$prod', '$disp', '-1', 					'-1', '-1', '$ent', '$date', '$sede')");
			}
			Header("Location: userUnitEntry.php?op=done");
		} else {
			if ($_GET["op"] == "day"){
				$q3 = mysql_query("SELECT * FROM product_sede where critico='1' and date=CURDATE();");
				$a3 = mysql_fetch_row($q3);
				if($a3[0]==0){
					$q = mysql_query("SELECT * FROM product where critico='1';");
					while($p = mysql_fetch_assoc($q)){
						$name=$p['name'];
						$disp='0';
						$q2 = mysql_query("SELECT count(*) FROM `product_sede` where name='$name' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede 											where name='$name' and sede='$sede');");
						$a2 = mysql_fetch_row($q2);
						if($a2[0]>0){
							$q1 = mysql_query("SELECT * FROM `product_sede` where name='$name' and sede='$sede' and date = (SELECT MAX( date )FROM product_sede 							where name='$name' and sede='$sede');");
							while($p1 = mysql_fetch_assoc($q1)){
								$disp=$p1['pesostock'];
							}
						}	
						mysql_query("INSERT INTO `daily_sede` (producto, instock, entrada, merma, realpeso, unidades, date, sede) VALUES ('$name', '$disp', '0', 						'0', '0', '-1', CURDATE( ), '$sede')");
					}
					
				}
				Header("Location: userDaily.php?op=done");
			}
		}	
	}	
		
?>