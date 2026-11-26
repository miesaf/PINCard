<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <head>
  <?php
		session_start();
		require_once __DIR__ . '/connectDB.php';
		require_once __DIR__ . '/dictionary.php';
		require_login();
		$name	= $_SESSION['pc_name'];
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	
	<style>
		table{
			border-collapse: collapse;
			//font-weight: bold;
		}
		
		table.utama {
			//width: 210mm;
		}
		
		table.utama tr td {
			padding: 5px;
		}
		
		table.tajuk tr td {
			padding: 0px;
		}
		
		td.but-right {
			width: 600px;
		}
		
		tr.lap {
			font-size: 18px;
		}
		
		tr.jt {
			background-color: #dddddd;
		}
		
		tr.butir {
			background-color: #dddddd;
			color: #000000;
		}
		
		body {
			font-family: 'Open Sans', sans-serif;
			font-size: 12px;
		}
		
		@page { size: portrait; }
	</style>
  </head>
  <body onload="window.print()">
	<table border="1" class="utama">
		<?php
		// A student sees only their own report.
		if($_SESSION["pc_priv"] == "BLUE")
		{
			$ID	= (int) $_SESSION["pc_ident"];
		}
		else
		{
			$ID	= request_id($_GET, 'ID');
		}
		
		$D_BC	= false;
		
		$row1	= $ID !== null ? db_row("SELECT * FROM STUDENT WHERE STUD_ID = ?", [$ID]) : null;
		
		if($row1)
		{
			$STD_ID		= $row1["STUD_ID"];
			$STD_NAME	= $row1["NAME"];
			$STD_IC		= $row1["IC_NUM"];
			$STD_PROG	= $row1["PROG"];
			$STD_SEM	= $row1["SEM"];
			$STD_COL	= $row1["KOLEJ"];
			$STD_ROOM	= $row1["ROOM"];
			
			$D_STD_PROG	= db_row("SELECT PROG FROM PROG WHERE ID_PROG = ?", [$STD_PROG])["PROG"] ?? "";
			$D_STD_COL	= db_row("SELECT KOLEJ FROM KOLEJ WHERE ID_KOLEJ = ?", [$STD_COL])["KOLEJ"] ?? "";
			
			echo "<thead>
					<tr class=\"butir-tajuk\">
						<td colspan=\"6\">
						<table border=\"0\" class=\"tajuk\">
							<tr class=\"lap\">
								<td align=\"center\" colspan=\"2\"><br><b>Laporan Kehadiran Aktiviti Pelajar</b><br><br></td>
							</tr>
							<tr>
								<td>Nombor Pelajar</td>
								<td class=\"but-right\">: " . e($STD_ID) . "</td>
							</tr>
							<tr>
								<td>Nama</td>
								<td class=\"but-right\">: " . e($STD_NAME) . "</td>
							</tr>
							<tr>
								<td>Nombor K/P</td>
								<td class=\"but-right\">: " . e($STD_IC) . "</td>
							</tr>
							<tr>
								<td>Program Pengajian &nbsp;&nbsp;</td>
								<td class=\"but-right\">: " . e($D_STD_PROG) . "</td>
							</tr>
							<tr>
								<td>Sem</td>
								<td class=\"but-right\">: " . e($STD_SEM) . "</td>
							</tr>
							<tr>
								<td>Kolej Kediaman</td>
								<td class=\"but-right\">: " . e($D_STD_COL) . "</td>
							</tr>
							<tr>
								<td>Nombor Bilik</td>
								<td class=\"but-right\">: " . e($STD_ROOM) . "</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr class=\"butir\">
						<td align=\"center\"><b>Bil</b></td>
						<td align=\"center\"><b>Program</b></td>
						<td align=\"center\"><b>Tarikh</b></td>
						<td align=\"center\"><b>Penglibatan</b></td>
						<td align=\"center\"><b>Kredit Markah</b></td>
						<td align=\"center\"><b>Jam Temu</b></td>
					</tr>
				</thead>
				<tbody class=\"uppercase\">";
			
			// Initialise index number
			$BIL		= 0;
			$MERIT		= 0;
			$T_HOUR		= 0;
			$T_MARKS	= 0;
			
			$result	= db_query("SELECT ATTENDANCE.*, EVENT.EVN_NAME, EVENT.EVN_HOUR, EVENT.EVN_DATE FROM ATTENDANCE LEFT JOIN EVENT ON ATTENDANCE.ATT_EVN = EVENT.EVN_ID WHERE ATTENDANCE.ATT_STUD = ? ORDER BY EVENT.EVN_DATE", [$STD_ID]);
			
			// iterate through all rows in result set
			while($row = mysqli_fetch_array($result))
			{
				$BIL++;
				
				// display barcode
				$D_BC		= true;
				
				$ATT_CRDT	= (int) $row["ATT_CRDT"];
				$EVN_HOUR	= (int) $row["EVN_HOUR"];
				$DDATE		= $row["EVN_DATE"] ? date("j F Y", strtotime($row["EVN_DATE"])) : "";
				
				echo "<tr>
						<td align=\"center\">$BIL</td>
						<td>" . e($row["ATT_EVN"] . " - " . $row["EVN_NAME"]) . "</td>
						<td align=\"center\">" . e($DDATE) . "</td>
						<td align=\"center\">" . e($DATT_CAT[$row["ATT_CAT"]] ?? $row["ATT_CAT"]) . "</td>
						<td align=\"center\">$ATT_CRDT</td>
						<td align=\"center\">$EVN_HOUR jam</td>
					</tr>";
				
				$MERIT		+= $ATT_CRDT;
				$T_HOUR		+= $EVN_HOUR;
				$T_MARKS	+= $ATT_CRDT * $EVN_HOUR;
			}
			
			if($D_BC == false)
			{
				echo "<tr><td align='center' colspan=\"6\"><br><i> Tiada kehadiran untuk dipaparkan </i><br><br></td></tr>";
			}
			else
			{
				echo "</tbody>
					<tbody>
						<tr class=\"jt\">
							<td align='right' colspan=\"4\"><b>Jumlah Terkumpul : </b></td>
							<td align='center'><b>$MERIT</b></td>
							<td align='center'><b>$T_HOUR jam</b></td>
						</tr>
						<tr>
							<td align='right' colspan=\"4\"><b>Jumlah (Kredit Markah x Jam Temu) : </b></td>
							<td align='center' colspan=\"2\"><b>$T_MARKS</b></td>
						</tr>
					</tbody>";
			}
		}
		else
		{
			echo "<tr><td align='center'><br><i> Pelajar tidak dijumpai </i><br><br></td></tr>";
		}
		?>

		<!--</tbody>-->
	</table>
  </body>
 </html>