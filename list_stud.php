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
		
		if(isset($_GET["SEL_COL"]))
		{
			$SEL_COL	= $_GET["SEL_COL"];
			
			if($SEL_COL == "all")
			{
				$sql	= "SELECT * FROM STUDENT ORDER BY NAME";
			}
			else
			{
				$sql	= "SELECT * FROM STUDENT WHERE KOLEJ = \"$SEL_COL\" ORDER BY NAME";
			}
			
			$result = mysql_query($sql) or die("SQL select statement failed");
		}
		
		// Padam Pelajar
		if(isset($_POST['DEL_ID']))
		{
			// get ID value
			$DEL_ID		= $_POST['DEL_ID'];
			$SCOL_ID	= $_POST['DEL'];
			
			$sql_del	= null;
			
			$I_DEL	= 0;
			$I_MAX	= count($DEL_ID);
			
			while($I_DEL < $I_MAX)
			{
				$DEL_ID_S	= mysql_real_escape_string($DEL_ID[$I_DEL]);
				
				$sql_del	= "DELETE FROM STUDENT WHERE STUD_ID = $DEL_ID_S;";
				// delete the entry
				$result = mysql_query("$sql_del"); 
				
				$I_DEL++;
			}
			
			// delete the entry
			//$result = mysql_query("$sql_del"); 
			
			// check for deletion
			if ($result)
			{
			   echo '<html>
						<head>
							<script>
								window.alert("Pelajar berjaya di padam!");
							</script>
							<meta http-equiv="refresh" content="0; url=list_stud.php?SEL_COL=' . $SCOL_ID . '" />
						</head>
					</html>';
			}
			else
			{
				//echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Kehadiran gagal di padam!");
								window.history.go(-1);
							</script>
						</head>
					</html>';
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
                    <?php include("nav.php"); ?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-10">
		  	<div class="row">
		  		<div class="col-md-10">
					<div class="content-box-header">
						<div class="panel-title">Senarai Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<table>
						<form id="SEL_COL" action="list_stud.php" method="GET">
							<tbody>
								<tr>
									<td align="right" valign="center">Sila pilih kolej :</td>
									<td><select class="form-control input-sm" name="SEL_COL">
											<?php
											$SEL	= null;
											$S_ALL	= null;
											if(isset($_GET['SEL_COL']))
											{
												$SEL	= $_GET['SEL_COL'];
												
												if($SEL == "all")
												{
													$S_ALL	= "selected";
												}
											}
											?>
											<option value="all" <?php print($S_ALL); ?>> Semua </option>
											<option disabled> </option>
											<?php
											$sql_choose = "SELECT * FROM KOLEJ ORDER BY KOLEJ";
											$result_choose = mysql_query($sql_choose);
											while ($row = mysql_fetch_array($result_choose))
											{
												$ID_KOLEJ	= $row["ID_KOLEJ"];
												$KOLEJ	= $row["KOLEJ"];
												
												if($SEL == $ID_KOLEJ)
												{
													echo '<option value="' . $ID_KOLEJ . '" selected> ' . $KOLEJ . ' (' . $ID_KOLEJ . ')</option>';
												}
												else
												{
													echo '<option value="' . $ID_KOLEJ . '"> ' . $KOLEJ . ' (' . $ID_KOLEJ . ')</option>';
												}
											}
											?>
										</select>
									</td>
									<td>&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" type="submit" form="SEL_COL" value="Submit">Papar</button></td>
								</tr>
							</tbody>
						</form>
						</table>
						<br>
						<form id="DEL_STD" action="list_stud.php?SEL_COL=<?php echo $SEL; ?>" method ="POST" onsubmit="return validate(this);">
							<input type="hidden" name="DEL" value="<?php echo $SEL; ?>">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<td align="center">Padam</td>
									<td align="center">Bil.</td>
									<td align="center">No. Pelajar</td>
									<td align="center">Nama Pelajar</td>
									<td align="center">Program</td>
									<td align="center">Semester</td>
									<td align="center">Kolej (No. Bilik)</td>
									<td align="center">Kredit Markah</td>
									<td align="center">Jam Temu</td>
									<td align="center">Jumlah<br>(Kredit Markah x Jam Temu)</td>
								</thead>
								<tbody>
							<?php
								// Papar table
								if(isset($_GET['SEL_COL']))
								{            
									// Initialise index number
									$BIL = 0;
									$D_BC	= false;
									
									// Display college name array
									$sql_dcol		= "SELECT * FROM KOLEJ ORDER BY KOLEJ";
									$result_dcol	= mysql_query($sql_dcol);
									while($row_dcol	= mysql_fetch_array($result_dcol))
									{
										$DID_COL		= $row_dcol["ID_KOLEJ"];
										$DCOL2			= $row_dcol["KOLEJ"];
										$DCOL[$DID_COL]	= $DCOL2;
									}
									
									// Display program name array
									$sql_dprg		= "SELECT * FROM PROG ORDER BY ID_PROG";
									$result_dprg	= mysql_query($sql_dprg);
									while($row_dprg	= mysql_fetch_array($result_dprg))
									{
										$DID_PRG		= $row_dprg["ID_PROG"];
										$DPRG2			= $row_dprg["PROG"];
										$DPRG[$DID_PRG]	= $DPRG2;
									}
									
									// iterate through all rows in result set
									while ($row = mysql_fetch_array($result))
									{
										$BIL++;
										$D_BC	= true;
										
										// extract specific fields
										$STUD_ID	= $row['STUD_ID'];
										$STUD_NAME	= $row['NAME'];
										$STUD_PROG	= $row['PROG'];
										$STUD_SEM	= $row['SEM'];
										$STUD_COL	= $row['KOLEJ'];
										$STUD_ROOM	= $row['ROOM'];
										
										// Display subtitutes
										$D_STUD_PROG	= $DPRG[$STUD_PROG];
										$D_STUD_COL		= $DCOL[$STUD_COL];
										
										//Initialise
										$T_CMERIT	= 0;
										$T_CMARKS	= 0;
										$T_CHOUR	= 0;
										
										$sql_att		= "SELECT ATTENDANCE.ATT_CAT, EVENT.EVN_TYPE, EVENT.EVN_HOUR FROM ATTENDANCE INNER JOIN EVENT ON ATTENDANCE.ATT_EVN = EVENT.EVN_ID WHERE ATTENDANCE.ATT_STUD = $STUD_ID";
										$result_catt	= mysql_query($sql_att);
										while($row_catt	= mysql_fetch_array($result_catt))
										{
											$CATT_CAT	= $row_catt["ATT_CAT"];
											$CATT_TYPE	= $row_catt["EVN_TYPE"];
											$CATT_HOUR	= $row_catt["EVN_HOUR"];
											
											// Formulate
											$CMERIT		= $ATT_CREDIT[$CATT_TYPE][$CATT_CAT];
											$CMARKS		= $CMERIT * $CATT_HOUR;
											
											$T_CMERIT	+= $CMERIT;
											$T_CHOUR	+= $CATT_HOUR;
											$T_CMARKS	+= $CMARKS;
										}
										
										// output student information
										echo "<tr>";
										echo "<td align=\"center\"><input type=\"checkbox\" name=\"DEL_ID[]\" value=\"$STUD_ID\"></td>";
										echo "<td align=center>$BIL</td>";
										echo "<td align=center>$STUD_ID</td>";
										echo "<td>
												<a target='_blank' href='stud_details.php?ID=$STUD_ID'>$STUD_NAME</a>
											</td>
											<td align=\"center\">$STUD_PROG</td>
											<td align=\"center\">$STUD_SEM</font></td>
											<td align=\"center\">$D_STUD_COL ($STUD_ROOM)</font></td>
											<td align=\"center\">$T_CMERIT</font></td>
											<td align=\"center\">$T_CHOUR jam</font>
											<td align=\"center\">$T_CMARKS</font></td>";
										echo "</tr>";
										
									}
									
									if($D_BC == false)
									{
										echo "<tr><td align='center' colspan=\"10\"><i> Tiada pelajar untuk dipaparkan </i></td></tr>";
									}
									else
									{
										echo "<tr>
												<td align='center' colspan=\"10\">
													<a href='print_stud_list.php?SEL_COL=$SEL_COL' target=\"_blank\">
														<button class='btn btn-outline btn-success' type='button' value='Cetak'>Cetak</button>
													</a>
												</td>
											</tr>";
									}
								}
								else
								{
									echo "<tr><td colspan=\"10\" align='center'><i> Tiada pelajar untuk dipaparkan </i></td></tr>";
								}
							?>
								</tbody>
							</table>
							<?php
								if($D_BC == true)
								{
									echo "<div>
											<button class=\"btn btn-danger pull-left\" type=\"submit\" form=\"DEL_STD\" value=\"Submit\">Padam</button>
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
			var del = confirm("Adakah anda pasti untuk memadam pelajar yang dipilih?");
			if (del == true)
			{
				return true;
			} else 
			{
				alert("Pelajar tidak di padam.");
				return false;
			}
		}
	</script>
  </body>
</html>