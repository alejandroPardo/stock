<?php
	session_start();
	// dBase file
    include "dbConfig.php";

    if ($_GET["op"] == "login"){
  		if (!$_POST["username"] || !$_POST["password"]){
        	Header("Location: index.php?op=login");
        }
  		$username=$_POST['username'];
  		$password=$_POST['password'];
  		$password=md5($password);
  
  		// Create query
  		$q = "SELECT * FROM `ga000848_StockDB`.`user` WHERE `username`='$username' AND `password` = '$password' LIMIT 1;";
  		// Run query
  		$r = mysql_query($q);

  		if ( $obj = @mysql_fetch_object($r) ){
        // Login good, create session variables
        	$_SESSION["valid_id"] = $obj->id;
        	$_SESSION["valid_user"] = $_POST["username"];
        	$_SESSION["valid_time"] = time();

        // Redirect to member page
        	Header("Location: members.php");
        } else {
        // Login not successful
        	Header("Location: index.php?op=error");
        }
  	}
?>