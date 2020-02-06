<!DOCTYPE html>
<html>
  <head>
    <title>PINCard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">
	
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

	<?php
		session_start();
		if(isset($_SESSION['pc_login']))
		{
			header("Location: main.php");
			exit;
		}
		
		$problem = ""; // 2) Declare
		// $problem = $_GET["problem"];	// 1) Undefined index error
		if(isset($_GET["err"]))	// 3) The magic function
		{
			$problem = $_GET["err"];
		}
		$errormsg = "<font color='red'> ERROR: ";
		if($problem == "failed")
		{
			$errormsg = $errormsg . " Your username or password was incorrect!!";
		}
		if($problem == "inactive")
		{
			$errormsg = $errormsg . " Your account has been deactivated!!";
			?>
			<script>
				window.alert("Your account has been deactivated!\nPlease contact system administrator.");
			</script>
			<?php
		}
		if($problem == "expired")
		{
			$errormsg = $errormsg . " You haven't logged in yet!!";
		}
		if($problem == "server")
		{
			$errormsg = $errormsg . " Our server is currently down!!";
		}
		if($problem == "db")
		{
			$errormsg = $errormsg . " Failed to connect to the database!!";
		}
		$errormsg = $errormsg . "</font>";
	?>
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-bg">
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-12">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.php">PINCard</a></h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
							<form id="login" name="login" role="form" action="checkLogin.php" method="post">
			                <div class="social">
	                            <img src="images/Logo.png" width="100%"/>
	                            <div class="division">
								<?php
									if($problem != "")
									{
										echo '<div class="form-input">' . $errormsg . '<br><br></div>';
									}
								?>
	                            </div>
	                        </div>
			                <input class="form-control" type="text" name="uname" placeholder="Student ID">
			                <input class="form-control" type="password" name="pword" placeholder="Password">
			                <div class="action">
								<button class="btn btn-primary signup" type="submit" form="login" value="Login">Login</button>
			                </div>                
			            </div>
			        </div>

			        <div class="already">
						<img src="images/LOGO-PNG.png" width="15%">
						<br /><br />
			            <p>&copy; 2017 KIK PERSADA<br /><a href="#">Terms of use</a></p>
						<br />
						<p>Developed by Muhamad Amirul Ikhmal</p>
			        </div>
			    </div>
			</div>
		</div>
	</div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>