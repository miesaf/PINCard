<?php
	$db_host	= "mysql.hostinger.my";
	$db_uname	= "u681964154_pncrd";
	$db_pword	= "O28GE>W#]:xARp";
	$db_dbname	= "u681964154_pncrd";
	
	//connect to MySQL database server
	$connection = mysql_connect($db_host, $db_uname, $db_pword) 
				or die("Failed MySQL server connection attempt.<br>" . mysql_error() . "<br>");

	//connecting to database
	$selection = mysql_select_db("u681964154_pncrd") 
		or die("Fail to connect to database.<br>" . mysql_error() . "<br>");
?>