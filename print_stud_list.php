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
		
		$DIS_COL	= null;
		
		$SEL_COL	= request_text($_GET, "SEL_COL");
		if($SEL_COL !== null)
		{
			if($SEL_COL == "all")
			{
				$result = db_query("SELECT * FROM STUDENT ORDER BY NAME");
			}
			else
			{
				$result = db_query("SELECT * FROM STUDENT WHERE KOLEJ = ? ORDER BY NAME", [$SEL_COL]);
			}
			
						// Display college name array
			$result_dcol	= db_query("SELECT * FROM KOLEJ ORDER BY KOLEJ");
			while($row_dcol	= mysqli_fetch_array($result_dcol))
			{
				$DID_COL		= $row_dcol["ID_KOLEJ"];
				$DCOL2			= $row_dcol["KOLEJ"];
				$DCOL[$DID_COL]	= $DCOL2;
			}
			
			if($SEL_COL == "all")
			{	$DIS_COL	= null;	}
			else
			{	$DIS_COL	= " (" . ($DCOL[$SEL_COL] ?? $SEL_COL) . ")";	}
			
			// Display program name array
			$result_dprg	= db_query("SELECT * FROM PROG ORDER BY ID_PROG");
			while($row_dprg	= mysqli_fetch_array($result_dprg))
			{
				$DID_PRG		= $row_dprg["ID_PROG"];
				$DPRG2			= $row_dprg["PROG"];
				$DPRG[$DID_PRG]	= $DPRG2;
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
		
		@page { size: landscape; }
	</style>
</head>
<body onload="window.print()">
	<table border="1" class="utama">
		<thead>
			<tr align="center" class="butir-tajuk">
				<td colspan="9">
					<table class="tajuk">
						<tr>
							<td align="center" colspan="2" class="lap">
								<h2><b>Senarai Nama Pelajar Kolej<?php echo e($DIS_COL); ?></b></h2>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="butir">
				<td align="center"><b>Bil.</b></td>
				<td align="center"><b>No. Pelajar</b></td>
				<td align="center"><b>Nama Pelajar</b></td>
				<td align="center"><b>Program</b></td>
				<td align="center"><b>Semester</b></td>
				<td align="center"><b>Kolej (No. Bilik)</b></td>
				<td align="center"><b>Kredit Markah</b></td>
				<td align="center"><b>Jam Temu</b></td>
				<td align="center"><b>Jumlah<br>(Kredit Markah<br>x Jam Temu)</b></td>
			</tr>
		</thead>
		<tbody class="uppercase">
<?php
			// Papar table
			if($SEL_COL !== null)
			{            
				// Initialise index number
				$BIL = 0;
				$D_BC	= false;
				
				// iterate through all rows in result set
				while ($row = mysqli_fetch_array($result))
				{
					$BIL++;
					$D_BC	= true;
					
					// extract specific fields
					$STUD_ID	= $row['STUD_ID'];
					$STUD_NAME	= $row['NAME'];
					$STUD_PROG	= $row['PROG'];
					$STUD_SEM	= $row['SEM'];
					$STUD_COL	= $row['KOLEJ'];
					$STUD_ROOM	= $row['ROOM'];
					
					// Display subtitutes
					$D_STUD_PROG	= $DPRG[$STUD_PROG] ?? '';
					$D_STUD_COL		= $DCOL[$STUD_COL] ?? '';
					
					//Initialise
					$T_CMERIT	= 0;
					$T_CMARKS	= 0;
					$T_CHOUR	= 0;
					
					$result_catt	= db_query("SELECT ATTENDANCE.ATT_CAT, EVENT.EVN_TYPE, EVENT.EVN_HOUR FROM ATTENDANCE INNER JOIN EVENT ON ATTENDANCE.ATT_EVN = EVENT.EVN_ID WHERE ATTENDANCE.ATT_STUD = ?", [$STUD_ID]);
					while($row_catt	= mysqli_fetch_array($result_catt))
					{
						$CATT_CAT	= $row_catt["ATT_CAT"];
						$CATT_TYPE	= $row_catt["EVN_TYPE"];
						$CATT_HOUR	= $row_catt["EVN_HOUR"];
						
						// Formulate
						$CMERIT		= att_credit($CATT_TYPE, $CATT_CAT);
						$CMARKS		= $CMERIT * $CATT_HOUR;
						
						$T_CMERIT	+= $CMERIT;
						$T_CHOUR	+= $CATT_HOUR;
						$T_CMARKS	+= $CMARKS;
					}
					
					// output student information
					echo "<tr>";
					echo "<td align=center>$BIL</td>";
					echo "<td align=center>" . e($STUD_ID) . "</td>";
					echo "<td>" . e($STUD_NAME) . "</td>
						<td align=\"center\">" . e($STUD_PROG) . "</td>
						<td align=\"center\">" . e($STUD_SEM) . "</td>
						<td align=\"center\">" . e("$D_STUD_COL ($STUD_ROOM)") . "</td>
						<td align=\"center\">$T_CMERIT</td>
						<td align=\"center\">$T_CHOUR jam</td>
						<td align=\"center\">$T_CMARKS</td>";
					echo "</tr>";
				}
				
				if($D_BC == false)
				{
					echo "<tr><td align='center' colspan=\"9\"><br><i> Tiada pelajar untuk dipaparkan </i><br><br></td></tr>";
				}
			}
			else
			{
				echo "<tr><td colspan=\"9\" align='center'><br><i> Tiada pelajar untuk dipaparkan </i><br><br></td></tr>";
			}
?>
			</tbody>
		</table>
	</table>
</body>
</html>