<!DOCTYPE html>
<html>
  <head>
<?php
	session_start();
	if(!$_SESSION['pc_login'] || $_SESSION['pc_priv'] != "BLCK")
	{
		header("Location: index.php?err=expired");
		exit;
	}
	$name	= $_SESSION['pc_name'];
	
	include('connectDB.php');
	include("dictionary.php");
	
	$D_BC	= false;
	
	$RPT_ID		= $_SESSION["pc_rim_id"];
	$RPT_STAT	= $_SESSION["pc_rim_stat"];
	
	unset($_SESSION["pc_rim_id"]);
	unset($_SESSION["pc_rim_stat"]);
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
						<div class="panel-title">Laporan Pendaftaran Pukal Maklumat Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<td>Bil.</td>
									<td>No. Pelajar</td>
									<td>Nama Pelajar</td>
									<td>No. K/P</td>
									<td>Program</td>
									<td>Semester</td>
									<td>Kolej Kediaman</td>
									<td>Status Pendaftaran</td>
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
						
						// Display organizer name array
						$sql_dprog		= "SELECT * FROM PROG";
						$result_dprog	= mysql_query($sql_dprog);
						while($row_dprog	= mysql_fetch_array($result_dprog))
						{
							$PROG1			= $row_dprog["ID_PROG"];
							$PROG2			= $row_dprog["PROG"];
							$D_PROG[$PROG1]	= $PROG2;
						}
						
						$BIL_T	= 0;
						
						for($I_STD=0; $I_STD<count($RPT_ID); $I_STD++)
						{
							$BIL_T++;
							
							$S_ID 	= $RPT_ID[$I_STD];
							$STAT	= $RPT_STAT[$I_STD];
							
							$STUD_ID 	= $S_ID[0];
							$NAME		= $S_ID[1];
							$IC_NUM		= $S_ID[2];
							$PROG		= $S_ID[3];
							$SEM		= $S_ID[4];
							$KOLEJ		= $S_ID[5];
							$ROOM		= $S_ID[6];
							
							$check_sql	= "SELECT * FROM STUDENT WHERE STUD_ID = $STUD_ID";
							
							// execute query
							$check_result = mysql_query($check_sql) or die("SQL select statement failed");
							
							// iterate through all rows in result set
							if($STAT == "BER")
							{
								$crow = mysql_fetch_array($check_result);
								
								// extract specific fields
								$NAME		= $crow["NAME"];
								$IC_NUM		= $crow["IC_NUM"];
								$PROG		= $crow["PROG"];
								$SEM		= $crow["SEM"];
								$KOLEJ		= $crow["KOLEJ"];
								$ROOM		= $crow["ROOM"];
?>
								<tr>
									<td align="center"><?php echo $BIL_T; ?></td>
									<td align="center"><?php echo $STUD_ID; ?></td>
									<td><?php echo $NAME; ?></td>
									<td align="center"><?php echo $IC_NUM; ?></td>
									<td><?php echo "$PROG - $D_PROG[$PROG]"; ?></td>
									<td align="center"><?php echo $SEM; ?></td>
									<td><?php echo "$D_KOLEJ[$KOLEJ]<br>($ROOM)"; ?></td>
									<td><?php echo $IMP_STAT[$STAT]; ?></td>
								</tr>
<?php
							}
							else
							{
?>
								<tr>
									<td align="center"><font color="red"><?php echo $BIL_T; ?></font></td>
									<td align="center"><font color="red"><?php echo $STUD_ID; ?></font></td>
									<td><font color="red"><?php echo $NAME; ?></font></td>
									<td align="center"><font color="red"><?php echo $IC_NUM; ?></font></td>
									<td><font color="red"><?php echo "$PROG - $D_PROG[$PROG]"; ?></font></td>
									<td align="center"><font color="red"><?php echo $SEM; ?></font></td>
									<td><font color="red"><?php echo "$D_KOLEJ[$KOLEJ]<br>($ROOM)"; ?></td>
									<td><?php echo $IMP_STAT[$STAT]; ?></td>
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