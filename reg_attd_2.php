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
		
		$EVN	= request_id($_GET, "CHECK_EVN") ?? request_id($_GET, 'EVN');
		
		$D_BC	= false;
		
		if(isset($_POST["REG_ATT"]))
		{
			$ATT_STUD	= request_id($_POST, 'ATT_STUD');
			$ATT_EVN	= request_id($_POST, 'ATT_EVN');
			$ATT_CAT	= request_id($_POST, 'ATT_CAT');
			$EVN_ROW	= $ATT_EVN !== null ? db_row("SELECT EVN_TYPE FROM EVENT WHERE EVN_ID = ?", [$ATT_EVN]) : null;
			
			if($ATT_STUD === null || !$EVN_ROW || !isset($DATT_CAT[(int) $ATT_CAT]))
			{
				alert_page("Pendaftaran kehadiran gagal!", null, true);
			}
			elseif(db_row("SELECT ATT_ID FROM ATTENDANCE WHERE ATT_STUD = ? AND ATT_EVN = ?", [$ATT_STUD, $ATT_EVN]))
			{
				alert_page("Duplikasi rekod kehadiran! Nombor pelajar pernah didaftarkan bagi program ini.", null, true);
			}
			else
			{
				// The merit credit comes from the event level and the category, never from the form.
				$exe_sql = db_query("INSERT INTO ATTENDANCE (ATT_STUD, ATT_EVN, ATT_TIME, ATT_CAT, ATT_CRDT) VALUES (?, ?, ?, ?, ?)",
					[$ATT_STUD, $ATT_EVN, date("Y-m-d H:i:s"), $ATT_CAT, att_credit($EVN_ROW["EVN_TYPE"], $ATT_CAT)]);
				
				// confirming the record is added
				if ($exe_sql)
				{
					alert_page("Pendaftaran kehadiran berjaya!\nKehadiran telah direkodkan ke dalam pangkalan data.", "reg_attd_2.php?EVN=" . $ATT_EVN);
				}
				else
				{
					alert_page("Pendaftaran kehadiran gagal!", null, true);
				}
			}
		}
		
		if($EVN !== null && ($row = db_row("SELECT * FROM EVENT WHERE EVN_ID = ?", [$EVN])))
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
			$DDATE			= date("j F Y", strtotime($EVN_DATE));
			
			// Display organizer name
			$D_EVN_ORG	= db_row("SELECT ORG_NAME FROM ORGANIZER WHERE ORG_ID = ?", [$EVN_ORG])["ORG_NAME"] ?? "";
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
									<td><b><?php echo e("$EVN_ID - $EVN_NAME"); ?></b></td>
								</tr>
								<tr>
									<td>Penganjur</td>
									<td><b><?php echo e("$EVN_ORG - $D_EVN_ORG"); ?></b></td>
								</tr>
								<tr>
									<td>Tahap Pengajuran</td>
									<td><b><?php echo e($DEVN_TYPE[$EVN_TYPE] ?? ''); ?></b></td>
								</tr>
								<tr>
									<td>Jam Temu Program</td>
									<td><b><?php echo e("$EVN_HOUR jam"); ?></b></td>
								</tr>
								<tr>
									<td>Tarikh Program</td>
									<td><b><?php echo e($DDATE); ?></b></td>
								</tr>
							</tbody>
						</table>
						<form id="check_stud" action="reg_attd_2.php?EVN=<?php echo e($EVN_ID); ?>" method ="GET">
							<input type="hidden" name="CHECK_STUD" value="YES">
							<input type="hidden" name="CHECK_EVN" value="<?php echo e($EVN_ID); ?>">
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
								<button class="btn btn-primary" type="submit" form="check_stud" value="Submit">Semak</button><a href="reg_attd_mass.php?EVN=<?php echo e($EVN_ID); ?>"><button class="btn btn-warning pull-right" type="button">Daftar Secara Pukal</button></a>
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
						$CHECK_ID	= request_id($_GET, "CHECK_ID");
						$CHECK_EVN	= $EVN;
						$CHECK_CAT	= request_id($_GET, "CHECK_CAT");
						
						if($D_BC == false)
						{
							echo "<center><i> Program tidak wujud </i></center>";
						}
						elseif(!isset($DATT_CAT[(int) $CHECK_CAT]))
						{
							alert_page("Sila pilih kategori penglibatan!", null, true);
						}
						// iterate through all rows in result set
						elseif($CHECK_ID !== null && ($crow = db_row("SELECT STUD_ID, NAME, SEM, KOLEJ, ROOM FROM STUDENT WHERE STUD_ID = ?", [$CHECK_ID])))
						{								
							// extract specific fields
							$STUD_ID 	= $crow["STUD_ID"];
							$NAME		= $crow["NAME"];
							$SEM		= $crow["SEM"];
							$KOLEJ		= $crow["KOLEJ"];
							$ROOM		= $crow["ROOM"];
							
							// Display college name
							$D_KOLEJ	= db_row("SELECT KOLEJ FROM KOLEJ WHERE ID_KOLEJ = ?", [$KOLEJ])["KOLEJ"] ?? "";
							
							// Merit Credit Formulae
							$ATT_CRDT	= att_credit($EVN_TYPE, $CHECK_CAT);
					?>
						<form id="reg_attd" action="reg_attd_2.php?EVN=<?php echo e($EVN); ?>" method ="POST">
							<input type="hidden" name="REG_ATT" value="YES">
							<input type="hidden" name="ATT_STUD" value="<?php echo e($STUD_ID); ?>">
							<input type="hidden" name="ATT_EVN" value="<?php echo e($CHECK_EVN); ?>">
							<input type="hidden" name="ATT_CAT" value="<?php echo e($CHECK_CAT); ?>">
							<table class="table table-bordered table-hover table-striped">
							<tbody>
								<tr>
									<td>Program</td>
									<td><b><?php echo e("$EVN_ID - $EVN_NAME"); ?></b></td>
								</tr>
								<tr>
									<td>Penganjur</td>
									<td><b><?php echo e("$EVN_ORG - $D_EVN_ORG"); ?></b></td>
								</tr>
								<tr>
									<td>Tarikh Program</td>
									<td><b><?php echo e($DDATE); ?></b></td>
								</tr>
								<tr>
									<td>Kategori Penglibatan</td>
									<td><b><?php echo e($DATT_CAT[(int) $CHECK_CAT] ?? ''); ?></b></td>
								</tr>
								<tr>
									<td>Kredit Pemarkahan</td>
									<td><b><?php echo e($ATT_CRDT); ?></b></td>
								</tr>
								<tr>
									<td>Pelajar</td>
									<td><b><?php echo e("$STUD_ID - $NAME (Semester $SEM)"); ?></b></td>
								</tr>
								<tr>
									<td>Kolej Kediaman</td>
						<td><b><?php echo e($D_KOLEJ); if($ROOM != null){ echo e(" ($ROOM)"); } ?></b></td>
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