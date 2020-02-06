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
		
		if(isset($_POST['DAFTAR']))
		{
			$EVN_NAME		= mysql_real_escape_string($_POST['EVN_NAME']);
			$EVN_ORG		= mysql_real_escape_string($_POST['EVN_ORG']);
			$EVN_TYPE		= mysql_real_escape_string($_POST['EVN_TYPE']);
			$EVN_HOUR		= mysql_real_escape_string($_POST['EVN_HOUR']);
			$EVN_VENUE		= mysql_real_escape_string($_POST['EVN_VENUE']);
			$EVN_DATE		= mysql_real_escape_string($_POST['EVN_DATE']);
			
			if($EVN_HOUR > $HOUR_LIM)
			{
				echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Jam temu program melebihi had ' . $HOUR_LIM . ' jam!");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
			else
			{
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
				
				$sql_evn_id		= "SELECT COUNT(EVN_NAME) FROM EVENT WHERE EVN_ID LIKE \"$DATE%\"";
				$result_evn_id	= mysql_query($sql_evn_id);
				$row_evn_id		= mysql_fetch_array($result_evn_id);
				$id_count		= $row_evn_id["COUNT(EVN_NAME)"];
				$id_inc			= $id_count + 1;
				
				if($id_inc < 10)
				{	$id_inc = "0" . $id_inc;	}
				
				$EVN_ID			= $DATE . $id_inc;
				
				$EVN_RB			= $_SESSION['pc_ident'];
				$EVN_RT			= $DATETIME;
				
				$sql="INSERT INTO EVENT (EVN_ID, EVN_NAME, EVN_ORG, EVN_TYPE, EVN_HOUR, EVN_VENUE, EVN_DATE, EVN_RB, EVN_RT) VALUES ('$EVN_ID', '$EVN_NAME', '$EVN_ORG', '$EVN_TYPE', $EVN_HOUR, '$EVN_VENUE', '$EVN_DATE', '$EVN_RB', '$EVN_RT')";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("Pendaftaran berjaya!\nProgram telah di simpan ke dalam pangkalan data.");
								</script>
								<meta http-equiv="refresh" content="0; url=evn_details.php?ID=' . $EVN_ID . '"/>
							</head>
						</html>';
				}
				else
				{
					echo "SQL insert statement failed.<br>" . mysql_error();
					echo '<html>
							<head>
								<script>
									window.alert("Pendaftaran program gagal!");
									window.history.go(-1);
								</script>
							</head>
						</html>';
				}
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
		  		<div class="col-md-8">
		  			<div class="content-box-header">
						<div class="panel-title">Pendaftaran Program Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="reg_event" action="evn_reg.php" method ="POST">
							<input type="hidden" name="DAFTAR" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nama Program</label>
									<input class="form-control" placeholder="Sila isikan nama program dengan penuh" type="text" name="EVN_NAME">
								</div>
								<div class="form-group">
									<label>Organisasi Penganjur</label>
									<select class="form-control" name="EVN_ORG">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<?php
										$sql_choose = "SELECT * FROM ORGANIZER ORDER BY ORG_NAME";
										$result_choose = mysql_query($sql_choose);
										while ($row = mysql_fetch_array($result_choose))
										{
											$ORG_ID		= $row["ORG_ID"];
											$ORG_NAME	= $row["ORG_NAME"];
											echo '<option value="' . $ORG_ID . '"> ' . $ORG_NAME . ' (' . $ORG_ID . ')</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Tahap Penganjuran</label>
									<select class="form-control" name="EVN_TYPE">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<option value="COLL"> Kolej </option>
										<option value="HEPA"> HEP / Akademik </option>
										<option value="FACT"> Fakulti </option>
										<option value="UNIV"> UiTM </option>
										<option value="MAND"> Program Wajib </option>
									</select>
								</div>
								<div class="form-group">
									<label>Jam Temu Program</label>
									<input class="form-control" type="number" name="EVN_HOUR">
								</div>
								<div class="form-group">
									<label>Lokasi Program Dianjurkan</label>
									<input class="form-control" placeholder="Contoh: Dewan Karyawan 1" type="text" name="EVN_VENUE">
								</div>
								<div class="form-group">
									<label>Tarikh Program Berlansung</label>
									<input class="form-control" type="date" name="EVN_DATE">
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="reg_event" value="Submit">Daftar</button>
							</div>
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
  </body>
</html>