<?php
	session_start();

	$enteredID	= trim((string) (is_string($_POST['uname'] ?? null) ? $_POST['uname'] : ''));
	$enteredPW	= (string) (is_string($_POST['pword'] ?? null) ? $_POST['pword'] : '');

	require_once __DIR__ . '/connectDB.php';

	// Logs the account in and goes to the main page.
	function login_as(string $name, string $type, string $ident): void
	{
		session_regenerate_id(true);
		$_SESSION['pc_login']	= "YES";
		$_SESSION['pc_name']	= $name;
		$_SESSION['pc_priv']	= $type;
		$_SESSION['pc_ident']	= $ident;

		header("Location: main.php");
		exit;
	}

	if (ctype_digit($enteredID) && $enteredPW !== '')
	{
		// Student account: student number, and the IC number (digits, without dashes) as the password.
		$row = db_row("SELECT STUD_ID, IC_NUM, NAME FROM STUDENT WHERE STUD_ID = ?", [$enteredID]);
		$enteredIC = str_replace(['-', ' '], '', $enteredPW);
		if ($row && ctype_digit($enteredIC) && (int) $enteredIC === (int) $row["IC_NUM"])
		{
			login_as($row["NAME"], "BLUE", (string) $row["STUD_ID"]);
		}

		// Admin account (REDD / BLCK): staff number and password.
		$row = db_row("SELECT NO_ID, PWORD, NAME, TYPE FROM ADMIN WHERE NO_ID = ?", [$enteredID]);
		if ($row)
		{
			$stored = $row["PWORD"];
			$hashed = password_get_info($stored)['algo'] !== null;
			$valid	= $hashed ? password_verify($enteredPW, $stored) : hash_equals($stored, $enteredPW);

			if ($valid)
			{
				// A password still in plain text (tools/hash-passwords.php not run yet) is hashed on its first login.
				if (!$hashed || password_needs_rehash($stored, PASSWORD_DEFAULT))
				{
					db_query("UPDATE ADMIN SET PWORD = ? WHERE NO_ID = ?", [password_hash($enteredPW, PASSWORD_DEFAULT), $row["NO_ID"]]);
				}
				login_as($row["NAME"], $row["TYPE"], (string) $row["NO_ID"]);
			}
		}
	}

	header("Location: index.php?err=failed");
	exit;
?>
