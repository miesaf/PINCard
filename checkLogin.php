<?php
	session_start();
	
	include('connectDB.php');
	
	$enteredID	= mysql_real_escape_string($_POST['uname']);
	$enteredIC	= mysql_real_escape_string($_POST['pword']);
		
	// Checking User Account ##################################################################################################
	function user($enteredID, $enteredIC)
	{
		//SQL query command
		$sql="SELECT STUD_ID, IC_NUM, NAME FROM STUDENT WHERE STUD_ID = $enteredID";
		
		// execute query
		$res_sql	= mysql_query($sql);
		$row		= mysql_fetch_array($res_sql);
		
		if($row)
		{
						// extract specific fields
			$STUD_ID	= $row["STUD_ID"];
			$IC_NUM		= $row["IC_NUM"];
			$NAME		= $row["NAME"];
			
			if(($enteredID == $STUD_ID) && ($enteredIC == $IC_NUM))
			{
				$_SESSION['pc_login']	= "YES";
				$_SESSION['pc_name']	= $NAME;
				$_SESSION['pc_priv']	= "BLUE";
				$_SESSION['pc_ident'] 	= $STUD_ID;
				
				$url = "Location: main.php";
				header($url);
				exit;
			}
		}
		
		return $problem = "failed";
	}
	
	// Checking Admin Account ##################################################################################################
	function admin($enteredID, $enteredIC)
	{
		//SQL query command
		$sql="SELECT * FROM ADMIN WHERE NO_ID = $enteredID";
		
		// execute query
		$res_sql	= mysql_query($sql);
		$row 		= mysql_fetch_array($res_sql);
		
		if($row)
		{
			// extract specific fields
			$NO_ID		= $row["NO_ID"];
			$PWORD		= $row["PWORD"];
			$NAME		= $row["NAME"];
			$TYPE		= $row["TYPE"];

			if(($enteredID == $NO_ID) && ($enteredIC == $PWORD))
			{
				$_SESSION['pc_login']	= "YES";
				$_SESSION['pc_name']	= $NAME;
				$_SESSION['pc_priv']	= $TYPE;
				$_SESSION['pc_ident'] 	= $NO_ID;
				
				$url = "Location: main.php";
				header($url);
				exit;
			}
		}
	
		return $problem = "failed";
	}
	
	$problem = user($enteredID, $enteredIC);
	$problem = admin($enteredID, $enteredIC);
	if(!$connection){ $problem = "server"; }
	elseif(!$selection){ $problem = "db"; }
	
	$url = "Location: index.php?err=$problem";
	header($url);
	exit;
?>