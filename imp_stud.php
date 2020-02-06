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
	
	$D_BC	= false;
	
	if(isset($_POST["REG_STD"]))
	{		
		$STD_BER	=	$_SESSION["pc_ims_b"];
		$STD_GAG	=	$_SESSION["pc_ims_g"];
		
		unset($_SESSION["pc_ims_b"]);
		unset($_SESSION["pc_ims_g"]);
		
		$VER_ID		= array();
		$VER_STAT	= array();
		
		for($I_STD=0; $I_STD<count($STD_BER); $I_STD++)
		{
			$STD_IMP	= $STD_BER[$I_STD];
			
			$sqlz="SELECT NAME FROM STUDENT WHERE STUD_ID = $STD_IMP[0]";
			
			// execute query
			$exe_sqlz = mysql_query($sqlz);
			
			if(mysql_fetch_array($exe_sqlz))
			{
				array_push($VER_ID, $STD_BER[$I_STD]);
				array_push($VER_STAT, "DUP");
			}
			else
			{
				$sql="INSERT INTO STUDENT (STUD_ID, NAME, IC_NUM, PROG, SEM, KOLEJ, ROOM) VALUES ($STD_IMP[0], '$STD_IMP[1]', $STD_IMP[2], '$STD_IMP[3]', '$STD_IMP[4]', '$STD_IMP[5]', '$STD_IMP[6]')";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				if ($exe_sql)
				{
					array_push($VER_ID, $STD_BER[$I_STD]);
					array_push($VER_STAT, "BER");
				}
				else
				{
					array_push($VER_ID, $STD_BER[$I_STD]);
					array_push($VER_STAT, "GAG");
				}
			}
		}
		
		array_merge($VER_ID, $STD_GAG);
		
		for($G_STD=0; $G_STD<count($STD_GAG); $G_STD++)
		{
			array_push($VER_ID, $STD_GAG[$G_STD]);
			array_push($VER_STAT, "GAG");
		}
		
		$_SESSION["pc_rim_id"]		= $VER_ID;
		$_SESSION["pc_rim_stat"]	= $VER_STAT;
		
		// confirming the record is added
		if ((count($STD_BER) + count($STD_GAG)) == count($VER_ID))
		{
			echo '<html>
					<head>
						<script>
							window.alert("Jangan muat semula (refresh) halaman ini! Sila semak semula laporan pendaftaran berikut!");
						</script>
						<meta http-equiv="refresh" content="0; url=rpt_imp_stud.php"/>
					</head>
				</html>';
		}
		else
		{
			echo '<html>
					<head>
						<script>
							window.alert("Jangan muat semula (refresh) halaman ini!\n\nRALAT: Sila semak semula laporan pendaftaran berikut!");
						</script>
						<meta http-equiv="refresh" content="0; url=rpt_imp_stud.php"/>
					</head>
				</html>';
		}
	}
	
	unset($_SESSION["pc_ims_b"]);
	unset($_SESSION["pc_ims_g"]);
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
				echo '<html>
						<head>
							<script>
								window.alert("Jangan muat semula (refresh) halaman ni dan sila semak pengesahan maklumat pelajar sebelum daftar!.");
							</script>
						</head>
					</html>';

				$uploadOk = true;

				$target_file = $_FILES["imfile"]["name"];
				
				$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
				
				// Check file type
				if(($fileType == "csv") || ($fileType == "CSV"))
				{
					$uploadOk = true;
				}
				else
				{
					$uploadOk = false;
					
					echo "<script type=\"text/javascript\">
							alert(\"RALAT: Fail harus dalam format CSV!\");
						</script>";
				}
				
				// Check file size less than 500KB
				if($_FILES["imfile"]["size"] > 500000)
				{
					$uploadOk = false;
					
					echo "<script type=\"text/javascript\">
							alert(\"RALAT: Saiz fail melebihi 500KB!\");
						</script>";
				}
				
				// Check file size not empty
				if($_FILES["imfile"]["size"] <= 0)
				{
					$uploadOk = false;
					
					echo "<script type=\"text/javascript\">
							alert(\"RALAT: Saiz fail adalah sifar/tiada data!\");
						</script>";
				}
				
				// Proceed to read uploaded file
				if($uploadOk)
				{
					$filename=$_FILES["imfile"]["tmp_name"];
					
					$file = fopen($filename, "r");
					
					$BIL_T	= 0;
					$BIL_W	= 0;
					$BIL_B	= 0;
					
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
					// Display organizer name array
					$sql_dkol		= "SELECT * FROM KOLEJ";
					$result_dkol	= mysql_query($sql_dkol);
					while($row_dkol	= mysql_fetch_array($result_dkol))
					{
						$KOL1			= $row_dkol["ID_KOLEJ"];
						$KOL2			= $row_dkol["KOLEJ"];
						$D_KOLEJ[$KOL1]	= $KOL2;
					}
					
					// Display organizer name array
					$sql_dprog		= "SELECT * FROM PROG";
					$result_dprog	= mysql_query($sql_dprog);
					while($row_dprog	= mysql_fetch_array($result_dprog))
					{
						$PROG1			= $row_dprog["ID_PROG"];
						$PROG2			= $row_dprog["PROG"];
						$D_PROG[$PROG1]	= $PROG2;
					}
					
					while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
					{
						$BIL_T++;
						
						$getData[0]	= mysql_real_escape_string($getData[0]);
						
						$NO_PEL[]	= $getData[0];
						
						$check_sql	= "SELECT STUD_ID FROM STUDENT WHERE STUD_ID = $getData[0]";
						
						// execute query
						$check_result = mysql_query($check_sql) or die("SQL select statement failed");
						
						// iterate through all rows in result set
						if($crow = mysql_fetch_array($check_result))
						{
							$BIL_W++;
							array_push($PEL_G, $getData);
?>
									<tr>
										<td align="center"><font color="red"><b><i><?php echo $BIL_T; ?></i></b></font></td>
										<td align="center"><font color="red"><b><i><?php echo $getData[0]; ?></i></b></font></td>
										<td><font color="red"><b><i><?php echo $getData[1]; ?></i></b></font></td>
										<td colspan="4"><font color="red"><b><i>Duplikasi! Pelajar tidak akan didaftarkan.</i></b></font></td>
									</tr>
<?php
						}
						else
						{
							$BIL_B++;
							array_push($PEL_B, $getData);
							
							// extract specific fields
							$STUD_ID 	= $getData[0];
							$NAME		= $getData[1];
							$IC_NUM		= $getData[2];
							$PROG		= $getData[3];
							$SEM		= $getData[4];
							$KOLEJ		= $getData[5];
							$ROOM		= $getData[6];
?>
									<tr>
										<td align="center"><?php echo $BIL_T; ?></td>
										<td align="center"><?php echo $STUD_ID; ?></td>
										<td><?php echo $NAME; ?></td>
										<td align="center"><?php echo $IC_NUM; ?></td>
										<td><?php echo "$PROG - $D_PROG[$PROG]"; ?></td>
										<td align="center"><?php echo $SEM; ?></td>
										<td><?php echo "$D_KOLEJ[$KOLEJ] ($ROOM)"; ?></td>
									</tr>
<?php
						}
						
					}
					
					fclose($file);
					
					$_SESSION["pc_ims_b"] = $PEL_B;
					$_SESSION["pc_ims_g"] = $PEL_G;

				}
				else
				{
					echo "<script type=\"text/javascript\">
							window.history.go(-1);
						</script>";
				}
?>
								</tbody>
							</table>
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