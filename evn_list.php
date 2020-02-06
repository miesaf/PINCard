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
		
		// Padam Akaun
		if(isset($_GET['DEL']) && $_SESSION['pc_priv'] == "BLCK")
		{
			// get ID value
			$ID_DEL = mysql_real_escape_string($_GET['DEL']);
			
			// delete the entry
			$result = mysql_query("DELETE FROM EVENT WHERE EVN_ID = $ID_DEL"); 
			
			// check for deletion
			if ($result)
			{
			   echo '<html>
						<head>
							<script>
								window.alert("Program berjaya di padam!");
							</script>
							<meta http-equiv="refresh" content="0; url=evn_details.php?ID=' . $ID_DEL . '" />
						</head>
					</html>';
			}
			else
			{
				//echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Program gagal di padam!");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
		}
		
		if(isset($_GET["SEL_ORG"]))
		{
			$SEL_ORG	= $_GET["SEL_ORG"];
			
			if($SEL_ORG == "all")
			{
				$sql	= "SELECT * FROM EVENT ORDER BY EVN_DATE DESC";
			}
			else
			{
				$sql	= "SELECT * FROM EVENT WHERE EVN_ORG = \"$SEL_ORG\" ORDER BY EVN_DATE DESC";
			}
			
			$result = mysql_query($sql) or die("SQL select statement failed");
		}
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
						<div class="panel-title">Senarai Program</div>
					</div>
					<div class="content-box-large box-with-header">
						<table>
						<form id="sel_org" action="evn_list.php" method="GET">
							<tbody>
								<tr>
									<td align="right" valign="center">Sila pilih penganjur :</td>
									<td><select class="form-control input-sm" name="SEL_ORG">
											<?php
											$SEL	= null;
											$S_ALL	= null;
											if(isset($_GET['SEL_ORG']))
											{
												$SEL	= $_GET['SEL_ORG'];
												
												if($SEL == "all")
												{
													$S_ALL	= "selected";
												}
											}
											?>
											<option value="all" <?php print($S_ALL); ?>> Semua </option>
											<option disabled> </option>
											<?php
											$sql_choose = "SELECT * FROM ORGANIZER ORDER BY ORG_NAME";
											$result_choose = mysql_query($sql_choose);
											while ($row = mysql_fetch_array($result_choose))
											{
												$ORG_ID		= $row["ORG_ID"];
												$ORG_NAME	= $row["ORG_NAME"];
												
												if($SEL == $ORG_ID)
												{
													echo '<option value="' . $ORG_ID . '" selected> ' . $ORG_NAME . ' (' . $ORG_ID . ')</option>';
												}
												else
												{
													echo '<option value="' . $ORG_ID . '"> ' . $ORG_NAME . ' (' . $ORG_ID . ')</option>';
												}
											}
											?>
										</select>
									</td>
									<td>&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" type="submit" form="sel_org" value="Submit">Papar</button></td>
								</tr>
							</tbody>
						</form>
						</table>
						<br>
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<td align="center">Bil.</td>
								<td align="center">Nama Program</td>
								<td align="center">Tarikh Program</td>
								<td align="center">Tempat Program</td>
								<td align="center">Anjuran</td>
							</thead>
							<tbody>
						<?php
							// Papar table
                            if(isset($_GET['SEL_ORG']))
                            {            
                                // Initialise index number
                                $BIL = 0;
								$D_BC	= false;
								
								// Display organizer name array
                                $sql_dorg		= "SELECT * FROM ORGANIZER ORDER BY ORG_ID";
                                $result_dorg	= mysql_query($sql_dorg);
                                while($row_dorg	= mysql_fetch_array($result_dorg))
                                {
                                    $DID_ORG		= $row_dorg["ORG_ID"];
                                    $DORG2			= $row_dorg["ORG_NAME"];
                                    $DORG[$DID_ORG]	= $DORG2;
								}
								
								// Display registrar name array
                                $sql_dadm		= "SELECT NO_ID, NAME FROM ADMIN ORDER BY NO_ID";
                                $result_dadm	= mysql_query($sql_dadm);
                                while($row_dadm	= mysql_fetch_array($result_dadm))
                                {
                                    $DID_ADM		= $row_dadm["NO_ID"];
                                    $DADM2			= $row_dadm["NAME"];
                                    $DADM[$DID_ADM]	= $DADM2;
								}
								
								// iterate through all rows in result set
                                while ($row = mysql_fetch_array($result))
                                {
                                    $BIL++;
									$D_BC	= true;
                                    
                                    // extract specific fields
                                    $EVN_ID		= $row['EVN_ID'];
                                    $EVN_NAME	= $row['EVN_NAME'];
                                    $EVN_ORG	= $row['EVN_ORG'];
                                    $EVN_VENUE	= $row['EVN_VENUE'];
                                    $EVN_DATE	= $row['EVN_DATE'];
									$EVN_RB		= $row['EVN_RB'];
									$EVN_RT		= $row['EVN_RT'];
                                    
                                    // Display subtitutes
                                    $D_EVN_ORG	= $DORG[$EVN_ORG];
                                    $D_EVN_RB	= $DADM[$EVN_RB];
									
									// Date reformatting algorithm
									$DATE_FORMAT	= strtotime($EVN_DATE);
									$DDATE			= date("j F Y", $DATE_FORMAT);
                                    
                                    // output student information
                                    echo "<tr>";
                                    echo "<td align=center>$BIL</td>";
                                    echo "<td>
											<a target='_blank' href='evn_details.php?ID=$EVN_ID'>$EVN_NAME</a>
										</td>
										<td align=\"center\">$DDATE</td>
										<td>$EVN_VENUE</td>
										<td>$D_EVN_ORG</font></td>";
                                    echo "</tr>";
                                }
								
								if($D_BC == false)
								{
									echo "<tr><td align='center' colspan=\"5\"><i> Tiada program untuk dipaparkan </i></td></tr>";
								}
							}
							else
							{
								echo "<tr><td colspan=\"5\" align='center'><i> Tiada program untuk dipaparkan </i></td></tr>";
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