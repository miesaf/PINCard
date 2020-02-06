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
			$ATT_STUD	= mysql_real_escape_string($_POST['ATT_STUD']);
			$ATT_EVN	= mysql_real_escape_string($_POST['ATT_EVN']);
			$ATT_CAT	= mysql_real_escape_string($_POST['ATT_CAT']);
			$ATT_CRDT	= mysql_real_escape_string($_POST['ATT_CRDT']);
			
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
			
			$sqlz="SELECT ATT_STUD, ATT_EVN FROM ATTENDANCE WHERE ATT_STUD = $ATT_STUD AND ATT_EVN = $ATT_EVN";
			
			// execute query
			$exe_sqlz = mysql_query($sqlz);
			
			if(mysql_fetch_array($exe_sqlz))
			{
				echo '<html>
						<head>
							<script>
								window.alert("Duplikasi rekod kehadiran! Nombor pelajar pernah didaftarkan bagi program ini.");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
			else
			{
				$sql="INSERT INTO ATTENDANCE (ATT_STUD, ATT_EVN, ATT_TIME, ATT_CAT, ATT_CRDT) VALUES ($ATT_STUD, $ATT_EVN, '$ATT_TIME', $ATT_CAT, $ATT_CRDT)";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("Pendaftaran kehadiran berjaya!\nKehadiran telah direkodkan ke dalam pangkalan data.");
								</script>
								<meta http-equiv="refresh" content="0; url=reg_attd_2.php?EVN=' . $ATT_EVN . '"/>
							</head>
						</html>';
				}
				else
				{
					echo "SQL insert statement failed.<br>" . mysql_error();
					echo '<html>
							<head>
								<script>
									window.alert("Pendaftaran kehadiran gagal!");
									window.history.go(-1);
								</script>
							</head>
						</html>';
				}
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
				if(!isset($_GET["CHECK_STUD"]))
				{
					?>
		  		<div class="col-md-8">
					<div class="content-box-header">
						<div class="panel-title">Daftar Kehadiran Pelajar</div>
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
						<form id="check_stud" action="reg_attd_2.php?EVN=<?php echo $EVN_ID; ?>" method ="GET">
							<input type="hidden" name="CHECK_STUD" value="YES">
							<input type="hidden" name="CHECK_EVN" value="<?php echo $EVN_ID; ?>">
							<fieldset>
								<div class="form-group">
									<label>Nombor Pelajar</label>
									<input class="form-control" placeholder="Sila masukkan nombor pelajar" type="text" name="CHECK_ID" autofocus>
								</div>
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
								<button class="btn btn-primary" type="submit" form="check_stud" value="Submit">Semak</button><a href="reg_attd_mass.php?EVN=<?php echo $EVN_ID; ?>"><button class="btn btn-warning pull-right" type="button">Daftar Secara Pukal</button></a>
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
				<div class="col-md-8">
					<div class="content-box-header">
						<div class="panel-title">Pengesahan Maklumat (Daftar Kehadiran Pelajar)</div>
					</div>
					<div class="content-box-large box-with-header">
					<?php
						// fetch student id
						$CHECK_ID	= $_GET["CHECK_ID"];
						$CHECK_EVN	= $_GET["CHECK_EVN"];
						$CHECK_CAT	= $_GET["CHECK_CAT"];
						
						// create the query
						$check_sql	= "SELECT STUD_ID, NAME, SEM, KOLEJ, ROOM FROM STUDENT WHERE STUD_ID = $CHECK_ID";
						
						// execute query
						$check_result = mysql_query($check_sql) or die("SQL select statement failed");
						
						// iterate through all rows in result set
						if($crow = mysql_fetch_array($check_result))
						{								
							// extract specific fields
							$STUD_ID 	= $crow["STUD_ID"];
							$NAME		= $crow["NAME"];
							$SEM		= $crow["SEM"];
							$KOLEJ		= $crow["KOLEJ"];
							$ROOM		= $crow["ROOM"];
							
							// Display organizer name array
							$sql_dkol		= "SELECT KOLEJ FROM KOLEJ WHERE ID_KOLEJ = \"$KOLEJ\"";
							$result_dkol	= mysql_query($sql_dkol);
							while($row_dkol	= mysql_fetch_array($result_dkol))
							{
								$D_KOLEJ	= $row_dkol["KOLEJ"];
							}
							
							// Merit Credit Formulae
							$ATT_CRDT	= $ATT_CREDIT["$EVN_TYPE"][$CHECK_CAT];
							
							// Date reformatting algorithm
							$DATE_FORMAT	= strtotime($EVN_DATE);
							$DDATE			= date("j F Y", $DATE_FORMAT);
					?>
						<form id="reg_attd" action="reg_attd_2.php?EVN=<?php echo $EVN; ?>" method ="POST">
							<input type="hidden" name="REG_ATT" value="YES">
							<input type="hidden" name="ATT_STUD" value="<?php echo $STUD_ID; ?>">
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
								<tr>
									<td>Pelajar</td>
									<td><b><?php echo "$STUD_ID - $NAME (Semester $SEM)"; ?></b></td>
								</tr>
								<tr>
									<td>Kolej Kediaman</td>
						<td><b><?php echo "$D_KOLEJ"; if($ROOM != null){ echo " ($ROOM)"; } ?></b></td>
								</tr>
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
							echo "<center><i> Maklumat pelajar tiada di dalam pangkalan data </i></center>";
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