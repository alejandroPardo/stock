<?php
	$host = "localhost";
	$user = "root";
	$pass = "";
	$db   = "ga000848_StockDB";

	// This part sets up the connection to the database
	$ms = mysql_connect($host, $user, $pass);
	if ( !$ms ){
        echo "Error connecting to database.\n";
    }
	mysql_select_db($db);
?>