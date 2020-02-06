<!DOCTYPE html>
<html>
  <head>
  <?php
		session_start();
		if(!$_SESSION['pc_login'] || $_SESSION['pc_priv'] != "BLCK")
		{
			header("Location: index.php?err=expired");
			exit;
		}
		$name	= $_SESSION['pc_name'];
		
		include('connectDB.php');
		include("dictionary.php");
		
		// Pinda Akaun
		if(isset($_POST['evn_amend']))
		{
			$ID2		= mysql_real_escape_string($_POST['ID2']);
			$EVN_NAME	= mysql_real_escape_string($_POST['EVN_NAME']);
			$EVN_ORG	= mysql_real_escape_string($_POST['EVN_ORG']);
			$EVN_TYPE	= mysql_real_escape_string($_POST['EVN_TYPE']);
			$EVN_HOUR	= mysql_real_escape_string($_POST['EVN_HOUR']);
			$EVN_VENUE	= mysql_real_escape_string($_POST['EVN_VENUE']);
			$EVN_DATE	= mysql_real_escape_string($_POST['EVN_DATE']);
			
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
				$sql="UPDATE EVENT SET EVN_NAME = '$EVN_NAME', EVN_ORG = '$EVN_ORG', EVN_TYPE = '$EVN_TYPE', EVN_HOUR = $EVN_HOUR, EVN_VENUE = '$EVN_VENUE', EVN_DATE = '$EVN_DATE' WHERE EVN_ID = '$ID2'";
			
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("Pindaan program berjaya!");
								</script>
								<meta http-equiv="refresh" content="0; url=evn_details.php?ID=' . $ID2 . '" />
							</head>
						</html>';
				}
				else
				{
					echo "SQL insert statement failed.<br>" . mysql_error();
					echo '<html>
							<head>
								<script>
									window.alert("Pindaan program gagal!");
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
						<div class="panel-title">Pindaan Butiran Program Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
					<?php
						if(isset($_GET['AMD']))
						{
							$PINDA_ID	= $_GET['AMD'];
							
							// create the query
							$sql = "SELECT * FROM EVENT WHERE EVN_ID = $PINDA_ID";
							
							// execute query
							$result = mysql_query($sql) or die("SQL select statement failed");
							
							// iterate through all rows in result set
							$row = mysql_fetch_array($result);
							
							// extract specific fields
							$EVN_NAME	= $row["EVN_NAME"];
							$EVN_ORG	= $row["EVN_ORG"];
							$EVN_TYPE	= $row["EVN_TYPE"];
							$EVN_HOUR	= $row["EVN_HOUR"];
							$EVN_VENUE	= $row["EVN_VENUE"];
							$EVN_DATE	= $row["EVN_DATE"];
							$EVN_TYPE	= $row["EVN_TYPE"];
					?>
						<form id="evn_amend" action="evn_amend.php" method ="POST">
							<input type="hidden" name="ID2" value="<?php echo $PINDA_ID; ?>">
							<input type="hidden" name="evn_amend" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nama Program</label>
									<input class="form-control" placeholder="Sila isikan nama program dengan penuh" type="text" name="EVN_NAME" value="<?php echo $EVN_NAME; ?>">
								</div>
								<div class="form-group">
									<label>Organisasi Penganjur</label>
									<select class="form-control" name="EVN_ORG">
										<option disabled> Sila pilih </option>
										<option disabled> </option>
										<?php
										$sql_choose = "SELECT * FROM ORGANIZER ORDER BY ORG_NAME";
										$result_choose = mysql_query($sql_choose);
										while ($row = mysql_fetch_array($result_choose))
										{
											$ORG_ID		= $row["ORG_ID"];
											$ORG_NAME	= $row["ORG_NAME"];
											
											$SEL_ORG = null;
											if($EVN_ORG == $ORG_ID)
											{	$SEL_ORG = "selected";	}
											
											echo '<option value="' . $ORG_ID . '" ' . $SEL_ORG . '> ' . $ORG_NAME . ' (' . $ORG_ID . ')</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Tahap Penganjuran</label>
									<select class="form-control" name="EVN_TYPE">
										<option disabled> Sila pilih </option>
										<option disabled> </option>
										<option value="COLL" <?php if($EVN_TYPE == "COLL"){ echo "selected"; } ?>> Kolej </option>
										<option value="HEPA" <?php if($EVN_TYPE == "HEPA"){ echo "selected"; } ?>> HEP / Akademik </option>
										<option value="FACT" <?php if($EVN_TYPE == "FACT"){ echo "selected"; } ?>> Fakulti </option>
										<option value="UNIV" <?php if($EVN_TYPE == "UNIV"){ echo "selected"; } ?>> UiTM </option>
										<option value="MAND" <?php if($EVN_TYPE == "MAND"){ echo "selected"; } ?>> Program Wajib </option>
									</select>
								</div>
								<div class="form-group">
									<label>Jam Temu Program</label>
									<input class="form-control" type="number" name="EVN_HOUR" value="<?php echo $EVN_HOUR; ?>">
								</div>
								<div class="form-group">
									<label>Lokasi Program Dianjurkan</label>
									<input class="form-control" placeholder="Contoh: Dewan Karyawan 1" type="text" name="EVN_VENUE" value="<?php echo $EVN_VENUE; ?>">
								</div>
								<div class="form-group">
									<label>Tarikh Program Berlansung</label>
									<input class="form-control" type="date" name="EVN_DATE"  value="<?php echo $EVN_DATE; ?>">
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="evn_amend" value="Submit">Hantar</button>
							</div>
						</form>
					<?php
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