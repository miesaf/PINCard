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
		
		$ID		= request_id($_GET, 'EVN');
		
		// Padam kehadiran (only records of this event)
		if(isset($_POST['DEL_ID']))
		{
			$DEL_ID = request_ids($_POST, 'DEL_ID');
			$EVN_ID = request_id($_POST, 'DEL');
			
			$deleted = 0;
			foreach($DEL_ID as $DEL_ID_S)
			{
				if (db_query("DELETE FROM ATTENDANCE WHERE ATT_ID = ? AND ATT_EVN = ?", [$DEL_ID_S, $EVN_ID]))
				{
					$deleted += mysqli_affected_rows($condb);
				}
			}
			
			// check for deletion
			if ($DEL_ID && $deleted === count($DEL_ID))
			{
				alert_page("Kehadiran berjaya di padam!", "list_attd_2.php?EVN=" . $EVN_ID);
			}
			else
			{
				alert_page("Kehadiran gagal di padam!", null, true);
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
						<div class="panel-title">Senarai Kehadiran Program</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="DEL_ATTD" action="list_attd_2.php?EVN=<?php echo e($ID); ?>" method ="POST" onsubmit="return validate(this);">
							<input type="hidden" name="DEL" value="<?php echo e($ID); ?>">
							<table class="table table-bordered table-hover table-striped">
								<tbody>
								<?php
								$C_EVN	= false;
								$D_BC	= false;
								
								if($ID !== null && ($row1 = db_row("SELECT * FROM EVENT WHERE EVN_ID = ?", [$ID])))
								{
									$C_EVN		= true;
									
									$EVN_ID		= $row1["EVN_ID"];
									$EVN_NAME	= $row1["EVN_NAME"];
									$EVN_ORG	= $row1["EVN_ORG"];
									$EVN_TYPE	= $row1["EVN_TYPE"];
									$EVN_HOUR	= $row1["EVN_HOUR"];
									$EVN_VENUE	= $row1["EVN_VENUE"];
									$EVN_DATE	= $row1["EVN_DATE"];
									
									// Date reformatting algorithm
									$DDATE			= date("j F Y", strtotime($EVN_DATE));
								}
								
								if($C_EVN == true)
								{
									$ORG_NAME	= db_row("SELECT ORG_NAME FROM ORGANIZER WHERE ORG_ID = ?", [$EVN_ORG])["ORG_NAME"] ?? "";
									
									echo "<thead>
											<tr>
												<td colspan=\"6\">
												<table>
													<tr>
														<td colspan=\"2\"><b>Senarai Kehadiran Program</b></td>
													</tr>
													<tr>
														<td>Nama Program</td>
														<td>: " . e($EVN_NAME) . "</td>
													</tr>
													<tr>
														<td>Anjuran</td>
														<td>: " . e($ORG_NAME) . "</td>
													</tr>
													<tr>
														<td>Tahap Penganjuran</td>
														<td>: " . e($DEVN_TYPE[$EVN_TYPE] ?? $EVN_TYPE) . "</td>
													</tr>
													<tr>
														<td>Jam Temu Program</td>
														<td>: " . e($EVN_HOUR) . " jam</td>
													</tr>
													<tr>
														<td>Tarikh Berlansung</td>
														<td>: " . e($DDATE) . "</td>
													</tr>
													<tr>
														<td>Tempat Berlansung</td>
														<td>: " . e($EVN_VENUE) . "</td>
													</tr>
												</table>
												</td>
											</tr>
											<tr>
												<td align=\"center\">Bil</td>
												<td align=\"center\">No Pelajar</td>
												<td align=\"center\">Nama</td>
												<td align=\"center\">Penglibatan</td>
												<td align=\"center\">Kredit Markah</td>
												<td align=\"center\">Padam</td>
											</tr>
										</thead>
										<tbody>";
										
									// Initialise index number
									$BIL = 0;
									
									$result = db_query("SELECT ATTENDANCE.*, STUDENT.NAME FROM ATTENDANCE LEFT JOIN STUDENT ON STUDENT.STUD_ID = ATTENDANCE.ATT_STUD WHERE ATT_EVN = ? ORDER BY ATT_CAT, ATT_STUD", [$ID]);
									
									// iterate through all rows in result set
									while($row = mysqli_fetch_array($result))
									{
										$BIL++;
										
										// display barcode
										$D_BC		= true;
										
										echo "<tr>
												<td align=\"center\">" . e($BIL) . "</td>
												<td align=\"center\">" . e($row["ATT_STUD"]) . "</td>
												<td><a target=\"_blank\" href=\"stud_details.php?ID=" . e($row["ATT_STUD"]) . "\">" . e($row["NAME"]) . "</a></td>
												<td align=\"center\">" . e($DATT_CAT[$row["ATT_CAT"]] ?? $row["ATT_CAT"]) . "</td>
												<td align=\"center\">" . e($row["ATT_CRDT"]) . "</td>
												<td align=\"center\"><input type=\"checkbox\" name=\"DEL_ID[]\" value=\"" . e($row["ATT_ID"]) . "\"></td>
											</tr>";
									}
										
									if($D_BC == false)
									{
										echo "<tr><td align='center' colspan=\"6\"><i> Tiada kehadiran untuk dipaparkan </i></td></tr>";
									}
								}
								else
								{
									echo "<tr><td align='center'><i> Program tidak dijumpai </i></td></tr>";
								}
								?>

								</tbody>
							</table>
							<?php
								if($D_BC == true)
								{
									echo "<div>
											<button class=\"btn btn-danger pull-right\" type=\"submit\" form=\"DEL_ATTD\" value=\"Submit\">Padam</button>
										</div><br><br>";
								}
							?>
						</form>
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
		function validate(form)
		{
			var del = confirm("Adakah anda pasti untuk memadam kehadiran yang dipilih?");
			if (del == true)
			{
				return true;
			} else 
			{
				alert("Kehadiran tidak di padam.");
				return false;
			}
		}
	</script>
  </body>
</html>