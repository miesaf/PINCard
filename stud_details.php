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
		  		<div class="col-md-8">
					<div class="content-box-header">
						<div class="panel-title">Butiran Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<tbody>
							<?php
							$ID		= $_GET['ID'];
							
							if(($ID != null) && is_numeric($ID))
							{
								// create the query
								$sql = "SELECT * FROM STUDENT WHERE STUD_ID = $ID";
								
								// execute query
								$result = mysql_query($sql) or die("SQL select statement failed");
								
								// iterate through all rows in result set
								if($row = mysql_fetch_array($result))
								{									
									// extract specific fields
									$STUD_ID 	= $row["STUD_ID"];
									$STUD_NAME	= $row["NAME"];
									$STUD_IC	= $row["IC_NUM"];
									$STUD_PROG	= $row["PROG"];
									$STUD_SEM	= $row["SEM"];
									$STUD_KOLEJ	= $row["KOLEJ"];
									$STUD_ROOM	= $row["ROOM"];
									
									// Decode codes into string
									$sql_display = "SELECT PROG FROM PROG WHERE ID_PROG = '$STUD_PROG'";
									$result_dispay = mysql_query($sql_display);
									while($row = mysql_fetch_array($result_dispay)) { $DSTUD_PROG = $row["PROG"]; }
									
									$sql_display2 = "SELECT KOLEJ FROM KOLEJ WHERE ID_KOLEJ = \"$STUD_KOLEJ\"";
									$result_dispay2 = mysql_query($sql_display2);
									while($row2 = mysql_fetch_array($result_dispay2)) { $DSTUD_KOLEJ = $row2["KOLEJ"]; }
									
									echo "	<tr>
												<td>Nombor Pelajar</td>
												<td><b>$STUD_ID</b></td>
											</tr>
											<tr>
												<td>Nama Pelajar</td>
												<td><b>$STUD_NAME</b></td>
											</tr>
											<tr>
												<td>No. K/P Pelajar</td>
												<td><b>$STUD_IC</b></td>
											</tr>
											<tr>
												<td>Program Pengajian</td>
												<td><b>$STUD_PROG - $DSTUD_PROG</b></td>
											</tr>
											<tr>
												<td>Semester Semasa</td>
												<td><b>$STUD_SEM</b></td>
											</tr>
											<tr>
												<td>Kolej Kediaman</td>
												<td><b>$DSTUD_KOLEJ</b></td>
											</tr>
											<tr>
												<td>No. Bilik Semasa</td>
												<td><b>$STUD_ROOM</b></td>
											</tr>
											<tr>
												<td align=\"center\" colspan=\"2\"><a href='rpt_stud_view.php?ID=$STUD_ID'>
											<button class='btn btn-outline btn-info' type='button' value='Laporan'>Laporan Kehadiran</button>
										</a></td>
											</tr>";
								}
								else
								{
									echo "<tr><td align='center'><i> Pelajar tidak dijumpai </i></td></tr>";
								}
							}
							else
							{
								echo "<tr><td align='center'><i> Nombor Peljar tidak sah </i></td></tr>";
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