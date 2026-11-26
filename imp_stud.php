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
	
	$D_BC	= false;
	
	if(isset($_POST["REG_STD"]))
	{
		// The rows checked on the previous step (kept in the session, not in the form):
		// pc_ims_b = rows to register, pc_ims_g = rows refused then (duplicate or invalid).
		$STD_BER	= $_SESSION["pc_ims_b"] ?? [];
		$STD_GAG	= $_SESSION["pc_ims_g"] ?? [];
		
		unset($_SESSION["pc_ims_b"], $_SESSION["pc_ims_g"]);
		
		$VER_ID		= array();
		$VER_STAT	= array();
		
		if(!$STD_BER && !$STD_GAG)
		{
			alert_page("Tiada data untuk didaftarkan. Sila muat naik fail semula.", "imp_stud.php");
		}
		
		foreach($STD_BER as $STD_IMP)
		{
			[$F, $error]	= read_student($STD_IMP);
			$VER_ID[]		= $STD_IMP;
			
			if($error !== null)
			{
				$VER_STAT[]	= "GAG";
			}
			elseif(db_row("SELECT STUD_ID FROM STUDENT WHERE STUD_ID = ?", [$F['STUD_ID']]))
			{
				$VER_STAT[]	= "DUP";
			}
			elseif(db_query("INSERT INTO STUDENT (STUD_ID, NAME, IC_NUM, PROG, SEM, KOLEJ, ROOM) VALUES (?, ?, ?, ?, ?, ?, ?)", array_values($F)))
			{
				$VER_STAT[]	= "BER";
			}
			else
			{
				$VER_STAT[]	= "GAG";
			}
		}
		
		foreach($STD_GAG as [$STD_IMP, $STAT])
		{
			$VER_ID[]	= $STD_IMP;
			$VER_STAT[]	= $STAT;
		}
		
		if($VER_ID)
		{
			$_SESSION["pc_rim_id"]		= $VER_ID;
			$_SESSION["pc_rim_stat"]	= $VER_STAT;
			
			alert_page("Jangan muat semula (refresh) halaman ini! Sila semak semula laporan pendaftaran berikut!", "rpt_imp_stud.php");
		}
	}
	
	unset($_SESSION["pc_ims_b"], $_SESSION["pc_ims_g"]);

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
						<div class="panel-title">Pendaftaran Pelajar (Pukal)</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="check_stud" action="imp_stud.php" method ="POST" enctype="multipart/form-data">
							<input type="hidden" name="CHECK_STUD" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Pilih Fail Yang Mengandungi Data Pukal</label>
									<input class="form-control" type="file" name="imfile">
									<p class="help-block">
										Sila muat naik fail berformat <b>CSV</b> dan bersaiz kurang <b>500KB</b> sahaja!
									</p>
								</div>
								<p>
								<b>Format susunan data didalam fail CSV :</b><br>No. Pelajar | Nama Pelajar | Nombor K/P | Kod Program Pengajian | Semester Semasa | Kod Kolej Kediaman | No. Bilik
								</p>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="check_stud" value="Submit">Muat Naik</button>
							</div>
						</form>
					</div>
				</div>
<?php
			}
			else
			{
?>
				<div class="col-md-10">
					<div class="content-box-header">
						<div class="panel-title">Pengesahan Maklumat Pelajar(Pendaftaran Secara Pukal)</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="reg_stud" action="imp_stud.php" method ="POST">
							<input type="hidden" name="REG_STD" value="YES">
<?php
				// Proceed to read uploaded file
				if(($CSV_ROWS = read_csv_upload("imfile", ",")) !== null)
				{
					alert_page("Jangan muat semula (refresh) halaman ni dan sila semak pengesahan maklumat pelajar sebelum daftar!.");
					
					$BIL_T	= 0;
					
					$PEL_B	= array();
					$PEL_G	= array();
?>
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<td>Bil.</td>
										<td>No. Pelajar</td>
										<td>Nama Pelajar</td>
										<td>No. K/P</td>
										<td>Program</td>
										<td>Semester</td>
										<td>Kolej Kediaman</td>
									</tr>
								</thead>
								<tbody>
<?php
					// Display college and programme name arrays
					$D_KOLEJ	= db_map("SELECT ID_KOLEJ, KOLEJ FROM KOLEJ");
					$D_PROG		= db_map("SELECT ID_PROG, PROG FROM PROG");
					
					foreach($CSV_ROWS as $getData)
					{
						$BIL_T++;
						
						[$F, $error] = read_student($getData);
						$getData	= array_values($F);
						
						if($error === null && db_row("SELECT STUD_ID FROM STUDENT WHERE STUD_ID = ?", [$F['STUD_ID']]))
						{
							$error		= "Duplikasi! Pelajar tidak akan didaftarkan.";
							$PEL_G[]	= [$getData, "DUP"];
						}
						elseif($error !== null)
						{
							$error		.= " Pelajar tidak akan didaftarkan.";
							$PEL_G[]	= [$getData, "GAG"];
						}
						
						if($error !== null)
						{
?>
									<tr>
										<td align="center"><font color="red"><b><i><?php echo e($BIL_T); ?></i></b></font></td>
										<td align="center"><font color="red"><b><i><?php echo e($F['STUD_ID']); ?></i></b></font></td>
										<td><font color="red"><b><i><?php echo e($F['NAME']); ?></i></b></font></td>
										<td colspan="4"><font color="red"><b><i><?php echo e($error); ?></i></b></font></td>
									</tr>
<?php
						}
						else
						{
							$PEL_B[]	= $getData;
?>
									<tr>
										<td align="center"><?php echo e($BIL_T); ?></td>
										<td align="center"><?php echo e($F['STUD_ID']); ?></td>
										<td><?php echo e($F['NAME']); ?></td>
										<td align="center"><?php echo e($F['IC_NUM']); ?></td>
										<td><?php echo e($F['PROG'] . " - " . ($D_PROG[$F['PROG']] ?? '')); ?></td>
										<td align="center"><?php echo e($F['SEM']); ?></td>
										<td><?php echo e(($D_KOLEJ[$F['KOLEJ']] ?? '') . " (" . $F['ROOM'] . ")"); ?></td>
									</tr>
<?php
						}
					}
					
					$_SESSION["pc_ims_b"] = $PEL_B;
					$_SESSION["pc_ims_g"] = $PEL_G;
?>
								</tbody>
							</table>
<?php
				}
?>
						<div>
							<button class="btn btn-primary" type="submit" form="reg_stud" value="Submit">Daftar Pelajar</button>
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