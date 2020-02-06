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
		  		<div class="col-md-8">
					<div class="content-box-header">
						<div class="panel-title">Butiran Program</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<tbody>
							<?php
							$ID		= $_GET['ID'];
							
							if(($ID != null) && is_numeric($ID))
							{
								// create the query
								$sql = "SELECT * FROM EVENT WHERE EVN_ID = $ID";
								
								// execute query
								$result = mysql_query($sql) or die("SQL select statement failed");
								
								// iterate through all rows in result set
								if($row = mysql_fetch_array($result))
								{									
									// extract specific fields
									$EVN_ID 	= $row["EVN_ID"];
									$EVN_NAME	= $row["EVN_NAME"];
									$EVN_ORG	= $row["EVN_ORG"];
									$EVN_TYPE	= $row["EVN_TYPE"];
									$EVN_HOUR	= $row["EVN_HOUR"];
									$EVN_VENUE	= $row["EVN_VENUE"];
									$EVN_DATE	= $row["EVN_DATE"];
									$EVN_RB		= $row["EVN_RB"];
									$EVN_RT		= $row["EVN_RT"];
									
									// Date reformatting algorithm
									$DATE_FORMAT	= strtotime($EVN_DATE);
									$DDATE			= date("j F Y", $DATE_FORMAT);
									
									// Date reformatting algorithm
									$DATE_FORMAT2	= strtotime($EVN_RT);
									$DDATE2			= date("g:i:s a, j F Y", $DATE_FORMAT2);
									
									// Decode codes into string
									$sql_display = "SELECT ORG_NAME FROM ORGANIZER WHERE ORG_ID = '$EVN_ORG'";
									$result_dispay = mysql_query($sql_display);
									while($row = mysql_fetch_array($result_dispay)) { $DEVN_ORG = $row["ORG_NAME"]; }
									
									$sql_display = "SELECT NAME FROM ADMIN WHERE NO_ID = $EVN_RB";
									$result_dispay = mysql_query($sql_display);
									while($row = mysql_fetch_array($result_dispay)) { $DEVN_RB = $row["NAME"]; }
									
									echo "	<tr>
												<td>ID Program</td>
												<td><b>$EVN_ID</b></td>
											</tr>
											<tr>
												<td>Nama Program</td>
												<td><b>$EVN_NAME</b></td>
											</tr>
											<tr>
												<td>Penganjur Program</td>
												<td><b>$EVN_ORG - $DEVN_ORG</b></td>
											</tr>
											<tr>
												<td>Tahap Pengajuran Program</td>
												<td><b>$DEVN_TYPE[$EVN_TYPE]</b></td>
											</tr>
											<tr>
												<td>Jam Temu Program</td>
												<td><b>$EVN_HOUR jam</b></td>
											</tr>
											<tr>
												<td>Lokasi Program</td>
												<td><b>$EVN_VENUE</b></td>
											</tr>
											<tr>
												<td>Tarikh Program</td>
												<td><b>$DDATE</b></td>
											</tr>
											<tr>
												<td>Program Didaftarkan Oleh</td>
												<td><b>$DEVN_RB</b></td>
											</tr>
											<tr>
												<td>Tarikh Pendaftaran Program</td>
												<td><b>$DDATE2</b></td>
											</tr>";
									if($_SESSION["pc_priv"] == "BLCK")
									{
										echo "<tr>
												<td align=\"center\" colspan=\"2\">
													<a href='evn_amend.php?AMD=$EVN_ID'>
														<button class='btn btn-outline btn-warning' type='button' value='Pinda'>Pinda</button>
													</a>
													<button class='btn btn-outline btn-danger' type='button' value='Padam' onClick='confirmDel(\"$EVN_ID\")'>Padam</button>
												</td>
											</tr>";
									}
								}
								else
								{
									echo "<tr><td align='center'><i> Program tidak dijumpai </i></td></tr>";
								}
							}
							else
							{
								echo "<tr><td align='center'><i> ID program tidak sah </i></td></tr>";
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
	
	<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Adakah anda pasti untuk memadam program ini?");
			if (del == true)
			{
				window.location.assign("evn_list.php?DEL=" + nums);
			} else 
			{
				alert("Program tidak di padam.");
			}
		}
	</script>
  </body>
</html>