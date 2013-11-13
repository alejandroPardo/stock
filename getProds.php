<?php
	session_start();
	// dBase file
    include "dbConfig.php";


	$gquery="SELECT `name`, `descripcion` FROM `product` ORDER BY `name`";
	$gresult=mysql_query($gquery);

	$aUsers = array();
	$aInfo = array();

	$a=0;
	while($dbres = mysql_fetch_assoc($gresult)){
		$aUsers[$a] = $dbres['name'];
		$aInfo[$a] = $dbres['descripcion'];
		$a=$a+1;
	}
	
	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	
	$aResults = array();
	
	if ($len){
		for ($i=0;$i<count($aUsers);$i++){
			if (strtolower(substr(utf8_decode($aUsers[$i]),0,$len)) == $input)
				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars($aUsers[$i]), "info"=>htmlspecialchars($aInfo[$i]) );
		}
	}
	
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++){
			echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
?>