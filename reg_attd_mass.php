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
			// The student numbers checked on the previous step (kept in the session, not in the form).
			$ATT_BER	= $_SESSION["pc_ram_b"] ?? [];
			$ATT_GAG	= $_SESSION["pc_ram_g"] ?? [];
			$ATT_EVN	= $_SESSION["pc_ram_evn"] ?? null;
			$ATT_CAT	= $_SESSION["pc_ram_cat"] ?? null;
			
			unset($_SESSION["pc_ram_b"], $_SESSION["pc_ram_g"], $_SESSION["pc_ram_evn"], $_SESSION["pc_ram_cat"]);
			
			$EVN_ROW	= $ATT_EVN !== null ? db_row("SELECT EVN_TYPE FROM EVENT WHERE EVN_ID = ?", [$ATT_EVN]) : null;
			
			if(!$EVN_ROW || !isset($DATT_CAT[(int) $ATT_CAT]) || (!$ATT_BER && !$ATT_GAG))
			{
				alert_page("Tiada data untuk didaftarkan. Sila muat naik fail semula.", "reg_attd_2.php?EVN=" . (int) $EVN);
			}
			else
			{
				$ATT_CRDT	= att_credit($EVN_ROW["EVN_TYPE"], $ATT_CAT);
				$ATT_TIME	= date("Y-m-d H:i:s");
				
				$VER_ID		= array();
				$VER_STAT	= array();
				
				foreach($ATT_BER as $STUD)
				{
					$VER_ID[]	= $STUD;
					if(db_row("SELECT ATT_ID FROM ATTENDANCE WHERE ATT_STUD = ? AND ATT_EVN = ?", [$STUD, $ATT_EVN]))
					{
						$VER_STAT[]	= "DUP";
					}
					elseif(db_query("INSERT INTO ATTENDANCE (ATT_STUD, ATT_EVN, ATT_TIME, ATT_CAT, ATT_CRDT) VALUES (?, ?, ?, ?, ?)", [$STUD, $ATT_EVN, $ATT_TIME, $ATT_CAT, $ATT_CRDT]))
					{
						$VER_STAT[]	= "BER";
					}
					else
					{
						$VER_STAT[]	= "GAG";
					}
				}
				
				foreach($ATT_GAG as $STUD)
				{
					$VER_ID[]	= $STUD;
					$VER_STAT[]	= "GAG";
				}
				
				$_SESSION["pc_rar_id"]		= $VER_ID;
				$_SESSION["pc_rar_stat"]	= $VER_STAT;
				
				alert_page("Jangan muat semula (refresh) halaman ini! Sila semak semula laporan pendaftaran berikut!", "rpt_attd_mass.php?EVN=" . $ATT_EVN . "&CAT=" . $ATT_CAT);
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
		
		unset($_SESSION["pc_ram_b"], $_SESSION["pc_ram_g"], $_SESSION["pc_ram_evn"], $_SESSION["pc_ram_cat"]);

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
						<form id="check_stud" action="reg_attd_mass.php?EVN=<?php echo e($EVN_ID); ?>" method ="POST" enctype="multipart/form-data">
							<input type="hidden" name="CHECK_STUD" value="YES">
							<input type="hidden" name="CHECK_EVN" value="<?php echo e($EVN_ID); ?>">
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
					$CHECK_CAT	= request_id($_POST, "CHECK_CAT");
					if($D_BC == false)
					{
						echo "<center><i> Program tidak wujud </i></center>";
					}
					elseif(!isset($DATT_CAT[(int) $CHECK_CAT]))
					{
						alert_page("Sila pilih kategori penglibatan!", null, true);
					}
					elseif(($CSV_ROWS = read_csv_upload("imfile", ";")) !== null)
					{
						alert_page("Jangan muat semula (refresh) halaman ni dan sila semak pengesahan maklumat pelajar sebelum daftar!.");
						
						// Merit Credit Formulae
						$ATT_CRDT	= att_credit($EVN_TYPE, $CHECK_CAT);
?>
						<form id="reg_attd" action="reg_attd_mass.php?EVN=<?php echo e($EVN_ID); ?>" method ="POST">
							<input type="hidden" name="REG_ATT" value="YES">
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
								</tbody>
							</table>
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
						$BIL_T	= 0;
						$PEL_B	= array();
						$PEL_G	= array();
						
						// Display college name array
						$D_KOLEJ	= db_map("SELECT ID_KOLEJ, KOLEJ FROM KOLEJ");
						
						foreach($CSV_ROWS as $getData)
						{
							$BIL_T++;
							
							// Student number only, in column A (anything after a comma is ignored)
							$NO_PEL	= trim(explode(",", $getData[0])[0]);
							$crow	= ctype_digit($NO_PEL) ? db_row("SELECT STUD_ID, NAME, SEM, KOLEJ, ROOM FROM STUDENT WHERE STUD_ID = ?", [$NO_PEL]) : null;
							
							if($crow)
							{
								$PEL_B[]	= (int) $crow["STUD_ID"];
?>
									<tr>
										<td align="center"><?php echo e($BIL_T); ?></td>
										<td align="center"><?php echo e($crow["STUD_ID"]); ?></td>
										<td><?php echo e($crow["NAME"]); ?></td>
										<td align="center"><?php echo e($crow["SEM"]); ?></td>
										<td><?php echo e(($D_KOLEJ[$crow["KOLEJ"]] ?? $crow["KOLEJ"]) . " (" . $crow["ROOM"] . ")"); ?></td>
									</tr>
<?php
							}
							else
							{
								$PEL_G[]	= $NO_PEL;
?>
									<tr>
										<td align="center"><font color="red"><b><i><?php echo e($BIL_T); ?></i></b></font></td>
										<td align="center"><font color="red"><b><i><?php echo e($NO_PEL); ?></i></b></font></td>
										<td colspan="3"><font color="red"><b><i>Pelajar tidak wujud! Kehadiran tidak akan direkodkan.</i></b></font></td>
									</tr>
<?php
							}
						}
						
						$_SESSION["pc_ram_b"]	= $PEL_B;
						$_SESSION["pc_ram_g"]	= $PEL_G;
						$_SESSION["pc_ram_evn"]	= (int) $EVN_ID;
						$_SESSION["pc_ram_cat"]	= $CHECK_CAT;
?>
								</tbody>
							</table>
						<div>
							<button class="btn btn-primary" type="submit" form="reg_attd" value="Submit">Daftar Kehadiran</button>
						</div>
						</form>
<?php
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