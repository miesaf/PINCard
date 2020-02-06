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
		
		// Tambah Akaun
		if(isset($_POST['DAFTAR']))
		{	
			// Variables from tadbir_program.php
			$NO_ID		= mysql_real_escape_string($_POST['NO_ID']);
			$NAME		= mysql_real_escape_string($_POST['NAME']);
			$PWORD1		= mysql_real_escape_string($_POST['PWORD1']);
			$PWORD2		= mysql_real_escape_string($_POST['PWORD2']);
			$TYPE		= mysql_real_escape_string($_POST['TYPE']);
			
			if($PWORD1 == $PWORD2)
			{
				//SQL query command
				$sql="INSERT INTO ADMIN (NO_ID, NAME, PWORD, TYPE) VALUES ($NO_ID, '$NAME', '$PWORD1', '$TYPE')";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("Pendaftaran akaun pentadbir berjaya!");
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
									window.alert("Pendaftaran akaun admin gagal!");
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
						<div class="panel-title">Pendaftaran Akaun Pentadbir</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="reg_admin" action="reg_admin.php" method ="POST">
							<input type="hidden" name="DAFTAR" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nombor Pekerja</label>
									<input class="form-control" type="text" name="NO_ID">
								</div>
								<div class="form-group">
									<label>Nama Penuh Pentadbir</label>
									<input class="form-control" placeholder="Sila isikan nama pentadbir dengan penuh" type="text" name="NAME">
								</div>
								<div class="form-group">
									<label>Kata Laluan</label>
									<input class="form-control" type="password" name="PWORD1">
								</div>
								<div class="form-group">
									<label>Pengesahan Kata Laluan</label>
									<input class="form-control" type="password" name="PWORD2">
								</div>
								<div class="form-group">
									<label>Jenis Akaun</label>
									<select class="form-control" name="TYPE">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<option value="REDD"> Red - Penyelia Asrama </option>
										<option value="BLCK"> Black - Penolong & Pengurus Asrama </option>
									</select>
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="reg_admin" value="Submit">Daftar</button>
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