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
		
		if(isset($_POST['DAFTAR']))
		{
			[$F, $error] = read_event_form($_POST);

			if($error !== null)
			{
				alert_page($error, null, true);
			}
			else
			{
				// Event ID: date (yymmdd) + running number of the day (01, 02, ...), after the highest one used.
				$DATE	= (int) date("ymd");
				$last	= db_row("SELECT MAX(EVN_ID) AS LAST_ID FROM EVENT WHERE EVN_ID BETWEEN ? AND ?", [$DATE * 100, $DATE * 100 + 99])["LAST_ID"] ?? null;
				$EVN_ID	= $last ? (int) $last + 1 : $DATE * 100 + 1;

				$exe_sql = db_query("INSERT INTO EVENT (EVN_ID, EVN_NAME, EVN_ORG, EVN_TYPE, EVN_HOUR, EVN_VENUE, EVN_DATE, EVN_RB, EVN_RT) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
					[$EVN_ID, $F['EVN_NAME'], $F['EVN_ORG'], $F['EVN_TYPE'], $F['EVN_HOUR'], $F['EVN_VENUE'], $F['EVN_DATE'], $_SESSION['pc_ident'], date("Y-m-d H:i:s")]);

				// confirming the record is added
				if ($exe_sql)
				{
					alert_page("Pendaftaran berjaya!\nProgram telah di simpan ke dalam pangkalan data.", "evn_details.php?ID=" . $EVN_ID);
				}
				else
				{
					alert_page("Pendaftaran program gagal!", null, true);
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
                    <?php include __DIR__ . '/nav.php'; ?>
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
										$result_choose = db_query("SELECT * FROM ORGANIZER ORDER BY ORG_NAME");
										while ($row = mysqli_fetch_array($result_choose))
										{
											$ORG_ID		= $row["ORG_ID"];
											$ORG_NAME	= $row["ORG_NAME"];
											echo '<option value="' . e($ORG_ID) . '"> ' . e($ORG_NAME) . ' (' . e($ORG_ID) . ')</option>';
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