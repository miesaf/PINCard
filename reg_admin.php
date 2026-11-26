<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <head>
  <?php
		session_start();
		require_once __DIR__ . '/connectDB.php';
		require_once __DIR__ . '/dictionary.php';
		require_login('BLCK');
		$name	= $_SESSION['pc_name'];
		
		// Tambah Akaun
		if(isset($_POST['DAFTAR']))
		{	
			$NO_ID		= request_id($_POST, 'NO_ID');
			$NAME		= request_text($_POST, 'NAME') ?? '';
			$PWORD1		= (string) (is_string($_POST['PWORD1'] ?? null) ? $_POST['PWORD1'] : '');
			$PWORD2		= (string) (is_string($_POST['PWORD2'] ?? null) ? $_POST['PWORD2'] : '');
			$TYPE		= request_text($_POST, 'TYPE') ?? '';
			
			if($NO_ID === null || $NAME === '' || $PWORD1 === '' || !in_array($TYPE, ["REDD", "BLCK"], true))
			{
				alert_page("Sila lengkapkan nombor pekerja, nama, kata laluan dan jenis akaun!", null, true);
			}
			elseif(!hash_equals($PWORD1, $PWORD2))
			{
				alert_page("Kata laluan tidak sepadan!", null, true);
			}
			elseif(db_query("INSERT INTO ADMIN (NO_ID, NAME, PWORD, TYPE) VALUES (?, ?, ?, ?)", [$NO_ID, $NAME, password_hash($PWORD1, PASSWORD_DEFAULT), $TYPE]))
			{
				alert_page("Pendaftaran akaun pentadbir berjaya!", "list_admin.php");
			}
			else
			{
				alert_page("Pendaftaran akaun admin gagal!", null, true);
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