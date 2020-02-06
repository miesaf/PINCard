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
			$STUD_ID	= mysql_real_escape_string($_POST['STUD_ID']);
			$NAME		= mysql_real_escape_string($_POST['NAME']);
			$IC_NUM		= mysql_real_escape_string($_POST['IC_NUM']);
			$PROG		= mysql_real_escape_string($_POST['PROG']);
			$SEM		= mysql_real_escape_string($_POST['SEM']);
			$KOLEJ		= mysql_real_escape_string($_POST['KOLEJ']);
			$ROOM		= mysql_real_escape_string($_POST['ROOM']);
			
			$sql="INSERT INTO STUDENT (STUD_ID, NAME, IC_NUM, PROG, SEM, KOLEJ, ROOM) VALUES ($STUD_ID, \"$NAME\", $IC_NUM, \"$PROG\", \"$SEM\", \"$KOLEJ\", \"$ROOM\")";
			
			// execute query
			$exe_sql = mysql_query($sql);
			
			// confirming the record is added
			if ($exe_sql)
			{
				echo '<html>
						<head>
							<script>
								window.alert("Pendaftaran berjaya!\nPelajar telah didaftarkan ke dalam pangkalan data.");
							</script>
							<meta http-equiv="refresh" content="0; url=stud_details.php?ID=' . $STUD_ID . '"/>
						</head>
					</html>';
			}
			else
			{
				echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Pendaftaran pelajar gagal!");
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
						<div class="panel-title">Pendaftaran Pelajar</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="reg_stud" action="reg_stud.php" method ="POST">
							<input type="hidden" name="DAFTAR" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nombor Pelajar</label>
									<input class="form-control" placeholder="2013xxxxxx" type="text" name="STUD_ID">
								</div>
								<div class="form-group">
									<label>Nama Penuh Pelajar</label>
									<input class="form-control" placeholder="Sila isikan nama pelajar dengan penuh" type="text" name="NAME">
								</div>
								<div class="form-group">
									<label>Nombor Kad Pengenalan Awam Pelajar</label>
									<input class="form-control" placeholder="Sila masukkan nombor kad pengenalan tanpa sengkang. Contoh: 950101010101" type="text" name="IC_NUM">
								</div>
								<div class="form-group">
									<label>Program Pengajian</label>
									<select class="form-control" name="PROG">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<?php
										$sql_choose = "SELECT * FROM PROG ORDER BY PROG";
										$result_choose = mysql_query($sql_choose);
										while ($row = mysql_fetch_array($result_choose))
										{
											$ID_PROG	= $row["ID_PROG"];
											$PROG		= $row["PROG"];
											echo '<option value="' . $ID_PROG . '"> ' . $ID_PROG . ' - ' . $PROG . '</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Semester Semasa</label>
									<input class="form-control" type="number" name="SEM">
								</div>
								<div class="form-group">
									<label>Kolej Kediaman</label>
									<select class="form-control" name="KOLEJ">
										<option disabled selected> Sila pilih </option>
										<option disabled> </option>
										<?php
										$sql_choose = "SELECT * FROM KOLEJ ORDER BY KOLEJ";
										$result_choose = mysql_query($sql_choose);
										while ($row = mysql_fetch_array($result_choose))
										{
											$ID_KOLEJ	= $row["ID_KOLEJ"];
											$KOLEJ		= $row["KOLEJ"];
											echo '<option value="' . $ID_KOLEJ . '"> ' . $KOLEJ . '</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Nombor Bilik</label>
									<input class="form-control" placeholder="Contoh: SB-031-B" type="text" name="ROOM">
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="reg_stud" value="Submit">Daftar</button>
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