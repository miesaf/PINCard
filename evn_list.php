<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <head>
  <?php
		session_start();
		require_once __DIR__ . '/connectDB.php';
		require_once __DIR__ . '/dictionary.php';
		require_login('REDD', 'BLCK');
		$name	= $_SESSION['pc_name'];
		
		// Padam Program (BLCK only; an event that still has attendance records cannot be deleted)
		$ID_DEL = request_id($_GET, 'DEL');
		if($ID_DEL !== null && $_SESSION['pc_priv'] == "BLCK")
		{
			if (db_query("DELETE FROM EVENT WHERE EVN_ID = ?", [$ID_DEL]) && mysqli_affected_rows($condb) === 1)
			{
				alert_page("Program berjaya di padam!", "evn_list.php?SEL_ORG=all");
			}
			else
			{
				alert_page("Program gagal di padam!", null, true);
			}
		}
		
		$SEL_ORG	= request_text($_GET, "SEL_ORG");
		if($SEL_ORG !== null)
		{
			if($SEL_ORG == "all")
			{
				$result = db_query("SELECT * FROM EVENT ORDER BY EVN_DATE DESC");
			}
			else
			{
				$result = db_query("SELECT * FROM EVENT WHERE EVN_ORG = ? ORDER BY EVN_DATE DESC", [$SEL_ORG]);
			}
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
                    <?php include __DIR__ . '/nav.php'; ?>
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
											if($SEL_ORG !== null)
											{
												$SEL	= $SEL_ORG;
												
												if($SEL == "all")
												{
													$S_ALL	= "selected";
												}
											}
											?>
											<option value="all" <?php echo e($S_ALL); ?>> Semua </option>
											<option disabled> </option>
											<?php
											$result_choose = db_query("SELECT * FROM ORGANIZER ORDER BY ORG_NAME");
											while ($row = mysqli_fetch_array($result_choose))
											{
												$ORG_ID		= $row["ORG_ID"];
												$ORG_NAME	= $row["ORG_NAME"];
												
												if($SEL == $ORG_ID)
												{
													echo '<option value="' . e($ORG_ID) . '" selected> ' . e($ORG_NAME) . ' (' . e($ORG_ID) . ')</option>';
												}
												else
												{
													echo '<option value="' . e($ORG_ID) . '"> ' . e($ORG_NAME) . ' (' . e($ORG_ID) . ')</option>';
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
                            if($SEL_ORG !== null)
                            {            
                                // Initialise index number
                                $BIL = 0;
								$D_BC	= false;
								
								// Display organizer name array
                                $result_dorg	= db_query("SELECT * FROM ORGANIZER ORDER BY ORG_ID");
                                while($row_dorg	= mysqli_fetch_array($result_dorg))
                                {
                                    $DID_ORG		= $row_dorg["ORG_ID"];
                                    $DORG2			= $row_dorg["ORG_NAME"];
                                    $DORG[$DID_ORG]	= $DORG2;
								}
								
								// iterate through all rows in result set
                                while ($row = mysqli_fetch_array($result))
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
                                    $D_EVN_ORG	= $DORG[$EVN_ORG] ?? '';
									
									// Date reformatting algorithm
									$DATE_FORMAT	= strtotime($EVN_DATE);
									$DDATE			= date("j F Y", $DATE_FORMAT);
                                    
                                    // output student information
                                    echo "<tr>";
                                    echo "<td align=center>$BIL</td>";
                                    echo "<td>
											<a target='_blank' href='evn_details.php?ID=" . e($EVN_ID) . "'>" . e($EVN_NAME) . "</a>
										</td>
										<td align=\"center\">" . e($DDATE) . "</td>
										<td>" . e($EVN_VENUE) . "</td>
										<td>" . e($D_EVN_ORG) . "</td>";
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