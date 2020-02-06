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
		if(isset($_POST['AMD_ACC']))
		{
			$ID2		= mysql_real_escape_string($_POST['ID2']);
			$NO_ID		= mysql_real_escape_string($_POST['NO_ID']);
			$NAME		= mysql_real_escape_string($_POST['NAME']);
			$KL1		= mysql_real_escape_string($_POST['KL1']);
			$KL2		= mysql_real_escape_string($_POST['KL2']);
			$TYPE		= mysql_real_escape_string($_POST['TYPE']);
			
			if($KL1 == "my_old_password")
			{
				$sql="UPDATE ADMIN SET NO_ID = $NO_ID, NAME = '$NAME', TYPE = '$TYPE' WHERE NO_ID = '$ID2'";
			}
			else
			{
				if($KL1 == $KL2)
				{
					$sql="UPDATE ADMIN SET NO_ID = $NO_ID, NAME = '$NAME', PWORD = '$KL1', TYPE = '$TYPE' WHERE NO_ID = '$ID2'";
					
					// execute query
					$exe_sql = mysql_query($sql);
					
					// confirming the record is added
					if ($exe_sql)
					{
						echo '<html>
								<head>
									<script>
										window.alert("Pindaan akaun pentadbir berjaya!");
									</script>
									<meta http-equiv="refresh" content="0; url=list_admin.php" />
								</head>
							</html>';
					}
					else
					{
						echo "SQL insert statement failed.<br>" . mysql_error();
						echo '<html>
								<head>
									<script>
										window.alert("Pindaan akaun pentadbir gagal!");
										window.history.go(-1);
									</script>
								</head>
							</html>';
					}
				}
				else
				{
					echo '<html>
								<head>
									<script>
										window.alert("Kata laluan tidak sepadan!");
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
						<div class="panel-title">Pindaan Akaun Pentadbir</div>
					</div>
					<div class="content-box-large box-with-header">
					<?php
						if(isset($_GET['AMD']))
						{
							$PINDA_ID	= $_GET['AMD'];
							
							// create the query
							$sql = "SELECT * FROM ADMIN WHERE NO_ID = $PINDA_ID";
							
							// execute query
							$result = mysql_query($sql) or die("SQL select statement failed");
							
							// iterate through all rows in result set
							$row = mysql_fetch_array($result);
							
							// extract specific fields
							$NO_ID		= $row["NO_ID"];
							$NAME		= $row["NAME"];
							$TYPE		= $row["TYPE"];
					?>
						<form id="amd_admin" action="amd_admin.php" method ="POST">
							<input type="hidden" name="ID2" value="<?php echo $PINDA_ID; ?>">
							<input type="hidden" name="AMD_ACC" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nombor Pekerja</label>
									<input class="form-control" type="text" name="NO_ID" value="<?php echo $NO_ID; ?>">
								</div>
								<div class="form-group">
									<label>Nama Penuh Pentadbir</label>
									<input class="form-control" placeholder="Sila isikan nama pentadbir dengan penuh" type="text" name="NAME" value="<?php echo $NAME; ?>">
								</div>
								<div class="form-group">
									<label>Kata Laluan</label>
									<input class="form-control" type="password" name="KL1" value="my_old_password">
								</div>
								<div class="form-group">
									<label>Pengesahan Kata Laluan</label>
									<input class="form-control" type="password" name="KL2" value="my_old_password">
								</div>
								<div class="form-group">
									<label>Jenis Akaun</label>
									<select class="form-control" name="TYPE">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<option value="REDD" <?php if($TYPE == "REDD"){echo "selected";} ?>> Red - Penyelia Asrama </option>
										<option value="BLCK" <?php if($TYPE == "BLCK"){echo "selected";} ?>> Black - Penolong & Pengurus Asrama </option>
									</select>
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="amd_admin" value="Submit">Pinda</button>
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