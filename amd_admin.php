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
		
		// Pinda Akaun
		if(isset($_POST['AMD_ACC']))
		{
			$ID2		= request_id($_POST, 'ID2');
			$NO_ID		= request_id($_POST, 'NO_ID');
			$NAME		= request_text($_POST, 'NAME') ?? '';
			$KL1		= (string) (is_string($_POST['KL1'] ?? null) ? $_POST['KL1'] : '');
			$KL2		= (string) (is_string($_POST['KL2'] ?? null) ? $_POST['KL2'] : '');
			$TYPE		= request_text($_POST, 'TYPE') ?? '';
			
			// "my_old_password" (the form's placeholder value) or an empty field keeps the password.
			$KEEP_PW	= ($KL1 === "my_old_password" || $KL1 === "") && ($KL2 === "my_old_password" || $KL2 === "");
			
			if($ID2 === null || $NO_ID === null || $NAME === '' || !in_array($TYPE, ["REDD", "BLCK"], true))
			{
				alert_page("Sila lengkapkan nombor pekerja, nama dan jenis akaun!", null, true);
			}
			elseif(!$KEEP_PW && !hash_equals($KL1, $KL2))
			{
				alert_page("Kata laluan tidak sepadan!", null, true);
			}
			else
			{
				if($KEEP_PW)
				{
					$exe_sql = db_query("UPDATE ADMIN SET NO_ID = ?, NAME = ?, TYPE = ? WHERE NO_ID = ?", [$NO_ID, $NAME, $TYPE, $ID2]);
				}
				else
				{
					$exe_sql = db_query("UPDATE ADMIN SET NO_ID = ?, NAME = ?, PWORD = ?, TYPE = ? WHERE NO_ID = ?", [$NO_ID, $NAME, password_hash($KL1, PASSWORD_DEFAULT), $TYPE, $ID2]);
				}
				
				// confirming the record is changed
				if ($exe_sql)
				{
					// Your own account: the session follows a new number, name or type.
					if($ID2 === (int) $_SESSION['pc_ident'])
					{
						$_SESSION['pc_ident']	= (string) $NO_ID;
						$_SESSION['pc_name']	= $NAME;
						$_SESSION['pc_priv']	= $TYPE;
					}
					alert_page("Pindaan akaun pentadbir berjaya!", "list_admin.php");
				}
				else
				{
					alert_page("Pindaan akaun pentadbir gagal!", null, true);
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
						<div class="panel-title">Pindaan Akaun Pentadbir</div>
					</div>
					<div class="content-box-large box-with-header">
					<?php
						$PINDA_ID	= request_id($_GET, 'AMD');
						$row		= $PINDA_ID !== null ? db_row("SELECT * FROM ADMIN WHERE NO_ID = ?", [$PINDA_ID]) : null;
						if(!$row)
						{
							echo "<center><i> Akaun tidak dijumpai </i></center>";
						}
						else
						{
							// extract specific fields
							$NO_ID		= $row["NO_ID"];
							$NAME		= $row["NAME"];
							$TYPE		= $row["TYPE"];
					?>
						<form id="amd_admin" action="amd_admin.php" method ="POST">
							<input type="hidden" name="ID2" value="<?php echo e($PINDA_ID); ?>">
							<input type="hidden" name="AMD_ACC" value="YES">
							<fieldset>
								<div class="form-group">
									<label>Nombor Pekerja</label>
									<input class="form-control" type="text" name="NO_ID" value="<?php echo e($NO_ID); ?>">
								</div>
								<div class="form-group">
									<label>Nama Penuh Pentadbir</label>
									<input class="form-control" placeholder="Sila isikan nama pentadbir dengan penuh" type="text" name="NAME" value="<?php echo e($NAME); ?>">
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