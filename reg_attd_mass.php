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
		
		if(isset($_GET["CHECK_EVN"]))
		{
			$EVN	= $_GET["CHECK_EVN"];
		}
		else
		{
			$EVN	= $_GET['EVN'];
		}
		
		$D_BC	= false;
		
		if(isset($_POST["REG_ATT"]))
		{
			$ATT_EVN	= mysql_real_escape_string($_POST['ATT_EVN']);
			$ATT_CAT	= mysql_real_escape_string($_POST['ATT_CAT']);
			$ATT_CRDT	= mysql_real_escape_string($_POST['ATT_CRDT']);
			
			$ATT_BER	=	$_SESSION["pc_ram_b"];
			$ATT_GAG	=	$_SESSION["pc_ram_g"];
			
			unset($_SESSION["pc_ram_b"]);
			unset($_SESSION["pc_ram_g"]);
			
			// Event ID Generator
			$h = "8";	// to rematch time with Malaysian GMT +8 time (please truncate the +/- sign)
			$hm = $h * 60; 
			$ms = $hm * 60;
			$DATE = gmdate("ymd", time()+($ms));	// use (-) for -ve GMT and (+) for +ve GMT
			$DATETIME = gmdate("Y-m-d H:i:s", time()+($ms));	// use (-) for -ve GMT and (+) for +ve GMT
						/* 	-----------------------
							Timestamp Configuration
							-----------------------
							Y - Full year
							m - month with leading zero
							d - date with leading zero
							H - 24-hour format hour
							i - minute with leading zero
							s - second with leading zero
							
							d - The day of the month (from 01 to 31)
							D - A textual representation of a day (three letters)
							j - The day of the month without leading zeros (1 to 31)
							l (lowercase 'L') - A full textual representation of a day
							N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
							S - The English ordinal suffix for the day of the month (2 characters st, nd, rd or th. Works well with j)
							w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
							z - The day of the year (from 0 through 365)
							W - The ISO-8601 week number of year (weeks starting on Monday)
							F - A full textual representation of a month (January through December)
							m - A numeric representation of a month (from 01 to 12)
							M - A short textual representation of a month (three letters)
							n - A numeric representation of a month, without leading zeros (1 to 12)
							t - The number of days in the given month
							L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
							o - The ISO-8601 year number
							Y - A four digit representation of a year
							y - A two digit representation of a year
							a - Lowercase am or pm
							A - Uppercase AM or PM
							B - Swatch Internet time (000 to 999)
							g - 12-hour format of an hour (1 to 12)
							G - 24-hour format of an hour (0 to 23)
							h - 12-hour format of an hour (01 to 12)
							H - 24-hour format of an hour (00 to 23)
							i - Minutes with leading zeros (00 to 59)
							s - Seconds, with leading zeros (00 to 59)
							u - Microseconds (added in PHP 5.2.2)
							e - The timezone identifier (Examples: UTC, GMT, Atlantic/Azores)
							I (capital i) - Whether the date is in daylights savings time (1 if Daylight Savings Time, 0 otherwise)
							O - Difference to Greenwich time (GMT) in hours (Example: +0100)
							P - Difference to Greenwich time (GMT) in hours:minutes (added in PHP 5.1.3)
							T - Timezone abbreviations (Examples: EST, MDT)
							Z - Timezone offset in seconds. The offset for timezones west of UTC is negative (-43200 to 50400)
							c - The ISO-8601 date (e.g. 2013-05-05T16:34:42+00:00)
							r - The RFC 2822 formatted date (e.g. Fri, 12 Apr 2013 12:01:05 +0200)
							U - The seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
							
							# Please reconfigure server time to:
							timezone - Asia/Kuala_Lumour
							latitude = 3.133333
							longitude = 101.683333
							
							#date() - will fetch server date
							#gmdate() - will fetch GMT 0:00 date
						*/
			// End of Event ID Generator
			
			$ATT_TIME		= $DATETIME;
			
			$VER_ID		= array();
			$VER_STAT	= array();
			
			for($I_ATT=0; $I_ATT<count($ATT_BER); $I_ATT++)
			{
				$sqlz="SELECT ATT_STUD, ATT_EVN FROM ATTENDANCE WHERE ATT_STUD = $ATT_BER[$I_ATT] AND ATT_EVN = $ATT_EVN";
				
				// execute query
				$exe_sqlz = mysql_query($sqlz);
				
				if(mysql_fetch_array($exe_sqlz))
				{
					array_push($VER_ID, $ATT_BER[$I_ATT]);
					array_push($VER_STAT, "DUP");
				}
				else
				{
					$sql="INSERT INTO ATTENDANCE (ATT_STUD, ATT_EVN, ATT_TIME, ATT_CAT, ATT_CRDT) VALUES ($ATT_BER[$I_ATT], $ATT_EVN, '$ATT_TIME', $ATT_CAT, $ATT_CRDT)";
					
					// execute query
					$exe_sql = mysql_query($sql);
					
					if ($exe_sql)
					{
						array_push($VER_ID, $ATT_BER[$I_ATT]);
						array_push($VER_STAT, "BER");
					}
					else
					{
						array_push($VER_ID, $ATT_BER[$I_ATT]);
						array_push($VER_STAT, "GAG");
					}
				}
			}
			
			array_merge($VER_ID, $ATT_GAG);
			
			for($G_ATT=0; $G_ATT<count($ATT_GAG); $G_ATT++)
			{
				array_push($VER_ID, $ATT_GAG[$G_ATT]);
				array_push($VER_STAT, "GAG");
			}
			
			$_SESSION["pc_rar_id"]		= $VER_ID;
			$_SESSION["pc_rar_stat"]	= $VER_STAT;
			
			// confirming the record is added
			if ((count($ATT_BER) + count($ATT_GAG)) == count($VER_ID))
			{
				echo '<html>
						<head>
							<script>
								window.alert("Jangan muat semula (refresh) halaman ini! Sila semak semula laporan pendaftaran berikut!");
							</script>
							<meta http-equiv="refresh" content="0; url=rpt_attd_mass.php?EVN=' . $ATT_EVN . '&CAT=' . $ATT_CAT . '&CRDT=' . $ATT_CRDT . '"/>
						</head>
					</html>';
			}
			else
			{
				echo '<html>
						<head>
							<script>
								window.alert("Jangan muat semula (refresh) halaman ini!\n\nRALAT: Sila semak semula laporan pendaftaran berikut!");
							</script>
							<meta http-equiv="refresh" content="0; url=rpt_attd_mass.php?EVN=' . $ATT_EVN . '&CAT=' . $ATT_CAT . '&CRDT=' . $ATT_CRDT . '"/>
						</head>
					</html>';
			}
		}
		
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
		
		unset($_SESSION["pc_ram_b"]);
		unset($_SESSION["pc_ram_g"]);
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
<?php
				if(!isset($_POST["CHECK_STUD"]))
				{
?>
		  		<div class="col-md-8">
					<div class="content-box-header">
						<div class="panel-title">Daftar Kehadiran Pelajar (Pukal)</div>
					</div>
					<div class="content-box-large box-with-header">
<?php
						if($D_BC == true)
						{
?>
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
									<td>Tahap Pengajuran</td>
									<td><b><?php echo $DEVN_TYPE[$EVN_TYPE]; ?></b></td>
								</tr>
								<tr>
									<td>Jam Temu Program</td>
									<td><b><?php echo "$EVN_HOUR jam"; ?></b></td>
								</tr>
								<tr>
									<td>Tarikh Program</td>
									<td><b><?php echo $DDATE; ?></b></td>
								</tr>
							</tbody>
						</table>
						<form id="check_stud" action="reg_attd_mass.php?EVN=<?php echo $EVN_ID; ?>" method ="POST" enctype="multipart/form-data">
							<input type="hidden" name="CHECK_STUD" value="YES">
							<input type="hidden" name="CHECK_EVN" value="<?php echo $EVN_ID; ?>">
							<fieldset>
								<div class="form-group">
									<label>Pilih Fail Yang Mengandungi Data Pukal</label>
									<input class="form-control" type="file" name="imfile">
									<p class="help-block">
										Sila muat naik fail berformat <b>CSV</b> dan bersaiz kurang <b>500KB</b> sahaja!
									</p>
								</div>
								<b>Format susunan data didalam fail CSV :</b><br>Nombor Pelajar sahaja diisi secara kebawah (isi column A sahaja)
								</p>
								<div class="form-group">
									<label>Kategori Penglibatan</label>
									<select class="form-control input-sm" name="CHECK_CAT">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<option value="1"> Penganjur / AJK </option>
										<option value="2"> Peserta </option>
										<option value="3"> Penonton </option>
									</select>
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="check_stud" value="Submit">Semak</button>
							</div>
						</form>
<?php
						}
						else
						{
							echo "<center><i> Program tidak wujud </i></center>";
						}
?>
					</div>
				</div>
<?php
				}
				else
				{
?>
				<div class="col-md-10">
					<div class="content-box-header">
						<div class="panel-title">Pengesahan Maklumat (Daftar Kehadiran Pelajar)</div>
					</div>
					<div class="content-box-large box-with-header">
<?php
					if(isset($_POST["CHECK_CAT"]))
					{
						echo '<html>
								<head>
									<script>
										window.alert("Jangan muat semula (refresh) halaman ni dan sila semak pengesahan maklumat pelajar sebelum daftar!.");
									</script>
								</head>
							</html>';
							
						// fetch student id
						$CHECK_EVN	= $_POST["CHECK_EVN"];
						$CHECK_CAT	= $_POST["CHECK_CAT"];
						
						// Merit Credit Formulae
						$ATT_CRDT	= $ATT_CREDIT["$EVN_TYPE"][$CHECK_CAT];
?>
						<form id="reg_attd" action="reg_attd_mass.php?EVN=<?php echo $EVN_ID; ?>" method ="POST">
							<input type="hidden" name="REG_ATT" value="YES">
							<input type="hidden" name="ATT_EVN" value="<?php echo $CHECK_EVN; ?>">
							<input type="hidden" name="ATT_CAT" value="<?php echo $CHECK_CAT; ?>">
							<input type="hidden" name="ATT_CRDT" value="<?php echo $ATT_CRDT; ?>">
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
<?php
						$uploadOk = true;
		
						$target_file = $_FILES["imfile"]["name"];
						
						$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
						
						// Check file type
						if(($fileType == "csv") || ($fileType == "CSV"))
						{
							$uploadOk = true;
						}
						else
						{
							$uploadOk = false;
							
							echo "<script type=\"text/javascript\">
									alert(\"RALAT: Fail harus dalam format CSV!\");
								</script>";
						}
						
						// Check file size less than 500KB
						if($_FILES["imfile"]["size"] > 500000)
						{
							$uploadOk = false;
							
							echo "<script type=\"text/javascript\">
									alert(\"RALAT: Saiz fail melebihi 500KB!\");
								</script>";
						}
						
						// Check file size not empty
						if($_FILES["imfile"]["size"] <= 0)
						{
							$uploadOk = false;
							
							echo "<script type=\"text/javascript\">
									alert(\"RALAT: Saiz fail adalah sifar/tiada data!\");
								</script>";
						}
						
						// Proceed to read uploaded file
						if($uploadOk)
						{
							$filename=$_FILES["imfile"]["tmp_name"];
							
							$file = fopen($filename, "r");
							
							$BIL_T	= 0;
							$BIL_W	= 0;
							$BIL_G	= 0;
							
							$PEL_B	= array();
							$PEL_G	= array();
?>
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>Bil.</td>
										<td>No. Pelajar</td>
										<td>Nama Pelajar</td>
										<td>Semester</td>
										<td>Kolej Kediaman</td>
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
							
							while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
							{
								$BIL_T++;
								
								$getData[0]	= mysql_real_escape_string($getData[0]);
								
								$NO_PEL[]	= $getData[0];
								
								$check_sql	= "SELECT STUD_ID, NAME, SEM, KOLEJ, ROOM FROM STUDENT WHERE STUD_ID = $getData[0]";
								
								$NAME		= null;
								$SEM		= null;
								$ROOM		= null;
								
								// execute query
								$check_result = mysql_query($check_sql) or die("SQL select statement failed");
								
								// iterate through all rows in result set
								if($crow = mysql_fetch_array($check_result))
								{
									$BIL_W++;
									array_push($PEL_B, $getData[0]);
									
									// extract specific fields
									$STUD_ID 	= $crow["STUD_ID"];
									$NAME		= $crow["NAME"];
									$SEM		= $crow["SEM"];
									$KOLEJ		= $crow["KOLEJ"];
									$ROOM		= $crow["ROOM"];
									
									// Merit Credit Formulae
									$ATT_CRDT	= $ATT_CREDIT["$EVN_TYPE"][$CHECK_CAT];
?>
									<tr>
										<td align="center"><?php echo $BIL_T; ?></td>
										<td align="center"><?php echo $getData[0]; ?></td>
										<td><?php echo $NAME; ?></td>
										<td align="center"><?php echo $SEM; ?></td>
										<td><?php echo "$D_KOLEJ[$KOLEJ] ($ROOM)"; ?></td>
									</tr>
<?php
								}
								else
								{
									$BIL_G++;
									array_push($PEL_G, $getData[0]);
?>
									<tr>
										<td align="center"><font color="red"><b><i><?php echo $BIL_T; ?></i></b></font></td>
										<td align="center"><font color="red"><b><i><?php echo $getData[0]; ?></i></b></font></td>
										<td colspan="3"><font color="red"><b><i>Pelajar tidak wujud! Kehadiran tidak akan direkodkan.</i></b></font></td>
									</tr>
<?php
								}
								
							}
							
							fclose($file);
							
							$_SESSION["pc_ram_b"] = $PEL_B;
							$_SESSION["pc_ram_g"] = $PEL_G;

						}
						else
						{
							echo "<script type=\"text/javascript\">
									window.history.go(-1);
								</script>";
						}
?>
								</tbody>
							</table>
						<div>
							<button class="btn btn-primary" type="submit" form="reg_attd" value="Submit">Daftar Kehadiran</button>
						</div>
						</form>
<?php
					}
					else
					{
						echo '<html>
								<head>
									<script>
										window.alert("Sila pilih kategori penglibatan!");
										window.history.go(-1);
									</script>
								</head>
							</html>';
					}
				}
?>
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