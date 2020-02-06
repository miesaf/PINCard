<?php
	session_start();
	// $name = $_SESSION['name'];
	unset($_SESSION['pc_login']);
	unset($_SESSION['pc_name']);
	unset($_SESSION['pc_priv']);
	unset($_SESSION['pc_ident']);
	// session_destroy();
?>
<html>
<head>
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
	
	<script>
		window.alert("You have been logged out from the system!\nThank you for using the system.");
	</script>
	<?php
		//$name = $_GET['name'];
	?>
</head>
<body>
		<!--<h1> Goodbye</h1>-->
		<meta http-equiv="refresh" content="0; url=index.php" />
</body>
</html>