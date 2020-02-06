<!DOCTYPE html>
<html>
  <head>
<?php
	session_start();
	if(!$_SESSION['pc_login'] || $_SESSION['pc_priv'] == "BLUE")
	{
		header("Location: index.php?err=expired");
		exit;
	}
	$name	= $_SESSION['pc_name'];
	
	include('connectDB.php');
	include("dictionary.php");
	
	$EVN	= $_GET['EVN'];
	
	$D_BC	= false;
	
	if($EVN != null)
	{
		// create the query
		$sql = "SELECT * FROM EVENT WHERE EVN_ID = $EVN";
		
		// execute query
		$result = mysql_query($sql) or die("SQL select statement failed");
		
		// iterate through all rows in result set
		if($row = mysql_fetch_array($result))
		{
			// display barcode
			$D_BC		= true;
			
			// extract specific fields
			$EVN_ID 	= $row["EVN_ID"];
			$EVN_NAME	= $row["EVN_NAME"];
			$EVN_ORG	= $row["EVN_ORG"];
			$EVN_TYPE	= $row["EVN_TYPE"];
			$EVN_HOUR	= $row["EVN_HOUR"];
			$EVN_DATE	= $row["EVN_DATE"];
			
			// Date reformatting algorithm
			$DATE_FORMAT	= strtotime($EVN_DATE);
			$DDATE			= date("j F Y", $DATE_FORMAT);
			
			// Display organizer name array
			$sql_dorg		= "SELECT ORG_NAME FROM ORGANIZER WHERE ORG_ID = \"$EVN_ORG\"";
			$result_dorg	= mysql_query($sql_dorg);
			while($row_dorg	= mysql_fetch_array($result_dorg))
			{
				$D_EVN_ORG	= $row_dorg["ORG_NAME"];
			}
			
		}
		else
		{
			echo "<center><i> Program tidak wujud </i></center>";
		}
	}
	else
	{
		echo "<center><i> Program tidak wujud </i></center>";
	}
	
	$RPT_ID		= $_SESSION["pc_rar_id"];
	$RPT_STAT	= $_SESSION["pc_rar_stat"];
	
	unset($_SESSION["pc_rar_id"]);
	unset($_SESSION["pc_rar_stat"]);
	
	$CHECK_CAT	= $_GET["CAT"];
	$ATT_CRDT	= $_GET["CRDT"];
?>
    <title>PINCard</title>
	
	<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-touch-icon.png?v=kPgEkaXqbz">
	<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png?v=kPgEkaXqbz">
	<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png?v=kPgEkaXqbz">
	<link rel="manifest" href="favicons/manifest.json?v=kPgEkaXqbz">
	<link rel="mask-icon" href="favicons/safari-pinned-tab.svg?v=kPgEkaXqbz" color="#5bbad5">
	<link rel="shortcut icon" href="favicons/favicon.ico?v=kPgEkaXqbz">
	<meta name="apple-mobile-web-app-title" content="PINCard">
	<meta name="application-name" content="PINCard">
	<meta name="msapplication-config" content="favicons/browserconfig.xml?v=kPgEkaXqbz">
	<meta name="theme-color" content="#ffffff">
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-5">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.php">PINCard</a></h1>
	              </div>
	           </div>
	           <div class="col-md-5">
	              <div class="row">
	                <div class="col-lg-12">
						
	                </div>
	              </div>
	           </div>
	           <div class="col-md-2">
	              <div class="navbar navbar-inverse" role="banner">
	                  <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
	                    <ul class="nav navbar-nav">
	                      <li class="dropdown">
	                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
	                        <ul class="dropdown-menu animated fadeInUp">
	                          <li><a href="logout.php">Logout</a></li>
	                        </ul>
	                      </li>
	                    </ul>
	                  </nav>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-2">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
<?php include("nav.php"); ?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-10">
		  	<div class="row">
				<div class="col-md-10">
					<div class="content-box-header">
						<div class="panel-title">Laporan Pendaftaran Pukal Kehadiran Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<tbody>
								<tr>
									<td>Program</td>
									<td><b><?php echo "$EVN_ID - $EVN_NAME"; ?></b></td>
								</tr>
								<tr>
									<td>Penganjur</td>
									<td><b><?php echo "$EVN_ORG - $D_EVN_ORG"; ?></b></td>
								</tr>
								<tr>
									<td>Tarikh Program</td>
									<td><b><?php echo $DDATE; ?></b></td>
								</tr>
								<tr>
									<td>Kategori Penglibatan</td>
									<td><b><?php echo $DATT_CAT[$CHECK_CAT]; ?></b></td>
								</tr>
								<tr>
									<td>Kredit Pemarkahan</td>
									<td><b><?php echo $ATT_CRDT; ?></b></td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<td>Bil.</td>
									<td>No. Pelajar</td>
									<td>Nama Pelajar</td>
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
<?php
						// Display organizer name array
						$sql_dkol		= "SELECT * FROM KOLEJ";
						$result_dkol	= mysql_query($sql_dkol);
						while($row_dkol	= mysql_fetch_array($result_dkol))
						{
							$KOL1			= $row_dkol["ID_KOLEJ"];
							$KOL2			= $row_dkol["KOLEJ"];
							$D_KOLEJ[$KOL1]	= $KOL2;
						}
						
						$BIL_T	= 0;
						
						for($I_ATT=0; $I_ATT<count($RPT_ID); $I_ATT++)
						{
							$BIL_T++;
							
							$S_ID 	= $RPT_ID[$I_ATT];
							$STAT	= $RPT_STAT[$I_ATT];
							
							$check_sql	= "SELECT NAME FROM STUDENT WHERE STUD_ID = $S_ID";
							
							$NAME		= null;
							
							// execute query
							$check_result = mysql_query($check_sql) or die("SQL select statement failed");
							
							// iterate through all rows in result set
							if($crow = mysql_fetch_array($check_result))
							{
								// extract specific fields
								$NAME		= $crow["NAME"];
?>
								<tr>
									<td align="center"><?php echo $BIL_T; ?></td>
									<td align="center"><?php echo $S_ID; ?></td>
									<td><?php echo $NAME; ?></td>
									<td><?php echo $D_STAT[$STAT]; ?></td>
								</tr>
<?php
							}
							else
							{
?>
								<tr>
									<td align="center"><font color="red"><b><i><?php echo $BIL_T; ?></i></b></font></td>
									<td align="center"><font color="red"><b><i><?php echo $S_ID; ?></i></b></font></td>
									<td><font color="red"><b><i>Pelajar tidak wujud!</i></b></font></td>
									<td><?php echo $D_STAT[$STAT]; ?></td>
								</tr>
<?php
							}
							
						}							
?>
							</tbody>
						</table>
					</div>
		  		</div>
		  	</div>
		  </div>
		</div>
    </div>

    <footer>
         <div class="container">
         
            <div class="copy text-center">
               Copyright 2017 <a href='#'>PINCard</a>
            </div>
            
         </div>
      </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>