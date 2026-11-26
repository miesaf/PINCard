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
		
		// Tambah penganjur
		if(isset($_POST['REG']))
		{
			$ORG_ID	= request_text($_POST, 'ORG_ID') ?? '';
			$ORG_NAME	= request_text($_POST, 'ORG_NAME') ?? '';
			
			if($ORG_ID === '' || $ORG_NAME === '')
			{
				alert_page("Sila lengkapkan kod dan nama!", null, true);
			}
			elseif(db_query("INSERT INTO ORGANIZER (ORG_ID, ORG_NAME) VALUES (?, ?)", [$ORG_ID, $ORG_NAME]))
			{
				alert_page("Pendaftaran penganjur berjaya!", "ad_org.php");
			}
			else
			{
				alert_page("Pendaftaran penganjur gagal!", null, true);
			}
		}
		
		// Pinda penganjur
		if(isset($_POST['AMD_ORG']))
		{
			$ID2	= request_text($_POST, 'ID2') ?? '';
			$ORG_ID	= request_text($_POST, 'ORG_ID') ?? '';
			$ORG_NAME	= request_text($_POST, 'ORG_NAME') ?? '';
			
			if($ORG_ID === '' || $ORG_NAME === '')
			{
				alert_page("Sila lengkapkan kod dan nama!", null, true);
			}
			elseif(db_query("UPDATE ORGANIZER SET ORG_ID = ?, ORG_NAME = ? WHERE ORG_ID = ?", [$ORG_ID, $ORG_NAME, $ID2]))
			{
				alert_page("Pindaan penganjur berjaya!", "ad_org.php");
			}
			else
			{
				alert_page("Pindaan penganjur gagal!", null, true);
			}
		}
		
		// Padam penganjur (refused while students or events still use it)
		$ID = request_text($_GET, 'DEL');
		if($ID !== null)
		{
			if (db_query("DELETE FROM ORGANIZER WHERE ORG_ID = ?", [$ID]) && mysqli_affected_rows($condb) === 1)
			{
				alert_page("Penganjur berjaya di padam!", "ad_org.php");
			}
			else
			{
				alert_page("Penganjur gagal di padam!", null, true);
			}
		}
		
		$result = db_query("SELECT * FROM ORGANIZER ORDER BY ORG_ID");
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
<?php
	if(!isset($_GET["AMD"]))
	{
?>
			<div class="row">
		  		<div class="col-md-10">
		  			<div class="content-box-header">
						<div class="panel-title">Pendaftaran Penganjur</div>
					</div>
					<div class="content-box-large box-with-header">
						<form id="reg_org" action="ad_org.php" method ="POST">
							<input type="hidden" name="REG" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Kod Penganjur</label>
									<input class="form-control" type="text" name="ORG_ID">
								</div>
								<div class="form-group">
									<label>Nama Penganjur</label>
									<input class="form-control" type="text" name="ORG_NAME">
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="reg_org" value="Submit">Daftar</button>
							</div>
						</form>
					</div>
		  		</div>
		  	</div>
<?php
	}
?>
		  	<div class="row">
<?php
	if(isset($_GET["AMD"]))
	{
?>
				<div class="col-md-8">
		  			<div class="content-box-header">
						<div class="panel-title">Pindaan Penganjur</div>
					</div>
					<div class="content-box-large box-with-header">
<?php
		$PINDA_ID	= request_text($_GET, 'AMD') ?? '';
		$row		= db_row("SELECT * FROM ORGANIZER WHERE ORG_ID = ?", [$PINDA_ID]);
		
		// extract specific fields
		$ORG_ID	= $row["ORG_ID"] ?? "";
		$ORG_NAME	= $row["ORG_NAME"] ?? "";
		
		if(!$row)
		{
			echo "<center><i> Penganjur tidak dijumpai </i></center>";
		}
		else
		{
?>
						<form id="amd_org" action="ad_org.php" method ="POST">
							<input type="hidden" name="ID2" value="<?php echo e($PINDA_ID); ?>">
							<input type="hidden" name="AMD_ORG" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Kod Penganjur</label>
									<input class="form-control" type="text" name="ORG_ID" value="<?php echo e($ORG_ID); ?>">
								</div>
								<div class="form-group">
									<label>Nama Penganjur</label>
									<input class="form-control" type="text" name="ORG_NAME" value="<?php echo e($ORG_NAME); ?>">
								</div>
							</fieldset>
							<div>
								<button class="btn btn-primary" type="submit" form="amd_org" value="Submit">Pinda</button>
							</div>
						</form>
<?php
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
						<div class="panel-title">Senarai Penganjur</div>
					</div>
					<div class="content-box-large box-with-header">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<td align="center">Bil.</td>
								<td align="center">Kod Penganjur</td>
								<td align="center">Nama Penganjur</td>
								<td align="center">Tindakan</td>
							</thead>
							<tbody>
						<?php
							// Initialise index number
							$BIL = 0;
							$D_BC	= false;
							
							// iterate through all rows in result set
							while ($row = mysqli_fetch_array($result))
							{
								$BIL++;
								$D_BC	= true;
								
								// extract specific fields
								$ORG_ID		= $row['ORG_ID'];
								$ORG_NAME	= $row['ORG_NAME'];
								
								// output student information
								echo "<tr>";
								echo "<td align=center>$BIL</td>";
								echo "<td align=center>" . e($ORG_ID) . "</td>";
								echo "
									<td>" . e($ORG_NAME) . "</td>
									<td align=\"center\">
										<a href='ad_org.php?AMD=" . e(urlencode($ORG_ID)) . "'>
											<button class='btn btn-outline btn-warning btn-sm' type='button' value='Pinda'>Pinda</button>
										</a>
										<button class='btn btn-outline btn-danger btn-sm' type='button' value='Padam' onClick='confirmDel(" . e(js((string) $ORG_ID)) . ")'>Padam</button>
									</td>";
								echo "</tr>";
							}
							
							if($D_BC == false)
							{
								echo "<tr><td align='center' colspan=\"4\"><i> Tiada penganjur untuk dipaparkan </i></td></tr>";
							}
						?>
							</tbody>
						</table>
					</div>
		  		</div>
<?php
	}
?>
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
	
	<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Adakah anda pasti untuk memadam penganjur ini?");
			if (del == true)
			{
				window.location.assign("ad_org.php?DEL=" + encodeURIComponent(nums));
			} else 
			{
				alert("Penganjur tidak di padam.");
			}
		}
	</script>
  </body>
</html>