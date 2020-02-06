<!DOCTYPE html>
<html>
  <head>
  <?php
		session_start();
		if(!$_SESSION['pc_login'])
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
		  		<div class="col-md-10">
					<div class="content-box-header">
						<div class="panel-title">Laporan Kehadiran Program Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<tbody>
							<?php
							if($_SESSION["pc_priv"] == "BLUE")
							{
								$ID	= $_SESSION["pc_ident"];
							}
							else
							{
								$ID		= $_GET['ID'];
							}
							
							$D_STD	= false;
							$D_BC	= false;
							
							$sql_display1	= "SELECT * FROM STUDENT WHERE STUD_ID = $ID";
							$result_dispay1	= mysql_query($sql_display1);
							if($row1 = mysql_fetch_array($result_dispay1))
							{
								$D_STD		= true;
								
								$STD_ID		= $row1["STUD_ID"];
								$STD_NAME	= $row1["NAME"];
								$STD_IC		= $row1["IC_NUM"];
								$STD_PROG	= $row1["PROG"];
								$STD_SEM	= $row1["SEM"];
								$STD_COL	= $row1["KOLEJ"];
								$STD_ROOM	= $row1["ROOM"];
							}
							
							if($D_STD == true)
							{
								$sql_display2 = "SELECT PROG FROM PROG WHERE ID_PROG = \"$STD_PROG\"";
								$result_dispay2 = mysql_query($sql_display2);
								while($row2 = mysql_fetch_array($result_dispay2)) { $D_STD_PROG = $row2["PROG"]; }
								
								$sql_display3 = "SELECT KOLEJ FROM KOLEJ WHERE ID_KOLEJ = \"$STD_COL\"";
								$result_dispay3 = mysql_query($sql_display3);
								while($row3 = mysql_fetch_array($result_dispay3)) { $D_STD_COL = $row3["KOLEJ"]; }
								
								echo "<thead>
										<tr>
											<td colspan=\"6\">
											<table>
												<tr>
													<td colspan=\"2\"><b>Laporan Kehadiran Program Pelajar</b></td>
												</tr>
												<tr>
													<td>Nombor Pelajar</td>
													<td><b>: $STD_ID</b></td>
												</tr>
												<tr>
													<td>Nama</td>
													<td><b>: $STD_NAME</b></td>
												</tr>
												<tr>
													<td>Nombor K/P</td>
													<td><b>: $STD_IC</b></td>
												</tr>
												<tr>
													<td>Program Pengajian</td>
													<td><b>: $D_STD_PROG</b></td>
												</tr>
												<tr>
													<td>Sem</td>
													<td><b>: $STD_SEM</b></td>
												</tr>
												<tr>
													<td>Kolej Kediaman</td>
													<td><b>: $D_STD_COL</b></td>
												</tr>
												<tr>
													<td>Nombor Bilik</td>
													<td><b>: $STD_ROOM</b></td>
												</tr>
											</table>
											</td>
										</tr>
										<tr>
											<td align=\"center\"><b>Bil</b></td>
											<td align=\"center\"><b>Program</b></td>
											<td align=\"center\"><b>Tarikh</b></td>
											<td align=\"center\"><b>Penglibatan</b></td>
											<td align=\"center\"><b>Kredit Markah</b></td>
											<td align=\"center\"><b>Jam Temu</b></td>
										</tr>
									</thead>
									<tbody>";
									
								// Initialise index number
                                $BIL		= 0;
								$MERIT		= 0;
								$T_HOUR		= 0;
								$MARKS		= 0;
								$T_MARKS	= 0;
								
								// create the query
								$sql	= "SELECT * FROM ATTENDANCE LEFT JOIN EVENT ON ATTENDANCE.ATT_EVN = EVENT.EVN_ID WHERE ATTENDANCE.ATT_STUD = $STD_ID ORDER BY EVENT.EVN_DATE";
								
								// execute query
								$result = mysql_query($sql) or die("SQL select statement failed");
								
								// iterate through all rows in result set
								while($row = mysql_fetch_array($result))
								{
									$BIL++;
									
									// display barcode
									$D_BC		= true;
									
									// extract specific fields
									$ATT_ID 	= $row["ATT_ID"];
									$ATT_EVN	= $row["ATT_EVN"];
									$ATT_TIME	= $row["ATT_TIME"];
									$ATT_CAT	= $row["ATT_CAT"];
									$ATT_CRDT	= $row["ATT_CRDT"];
									
									// Decode codes into string
									$sql_display = "SELECT EVN_NAME, EVN_HOUR, EVN_DATE FROM EVENT WHERE EVN_ID = $ATT_EVN";
									
									$result_dispay = mysql_query($sql_display);
									while($row = mysql_fetch_array($result_dispay))
									{
										$EVN_NAME	= $row["EVN_NAME"];
										$EVN_HOUR	= $row["EVN_HOUR"];
										$EVN_DATE	= $row["EVN_DATE"];
										
										// Date reformatting algorithm
										$DATE_FORMAT	= strtotime($EVN_DATE);
										$DDATE			= date("j F Y", $DATE_FORMAT);
									}
									
									echo "<tr>
											<td align=\"center\">$BIL</td>
											<td>$ATT_EVN - $EVN_NAME</td>
											<td align=\"center\">$DDATE</td>
											<td align=\"center\">$DATT_CAT[$ATT_CAT]</td>
											<td align=\"center\">$ATT_CRDT</td>
											<td align=\"center\">$EVN_HOUR jam</td>
										</tr>";
										
									$MERIT		+= $ATT_CRDT;
									$T_HOUR		+= $EVN_HOUR;
									$MARKS		= $ATT_CRDT * $EVN_HOUR;
									$T_MARKS	+= $MARKS;
								}
									
								if($D_BC == false)
								{
									echo "<tr><td align='center' colspan=\"6\"><i> Tiada kehadiran untuk dipaparkan </i></td></tr>";
								}
								else
								{
									echo "</tbody>
										<tbody>
											<tr>
												<td align='right' colspan=\"4\"><b>Jumlah Terkumpul : </b></td>
												<td align='center'><b>$MERIT</b></td>
												<td align='center'><b>$T_HOUR jam</b></td>
											</tr>
											<tr>
												<td align='right' colspan=\"4\"><b>Jumlah (Kredit Markah x Jam Temu) : </b></td>
												<td align='center' colspan=\"2\"><b>$T_MARKS</b></td>
											</tr>
											<tr>
												<td align='center' colspan=\"6\">
													<a href='print_rpt_stud.php?ID=$STD_ID' target=\"_blank\">
														<button class='btn btn-outline btn-success' type='button' value='Cetak'>Cetak</button>
													</a>
												</td>
											</tr>
										</tbody>";
								}
							}
							else
							{
								echo "<tr><td align='center'><i> Pelajar tidak dijumpai </i></td></tr>";
							}
							?>
							<!--</tbody>-->
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