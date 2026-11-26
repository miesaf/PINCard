<?php
// One-time: hashes every plain-text admin password in ADMIN. Run from the PINCard folder after
// sql/2026-11-hash-passwords.sql:  php tools/hash-passwords.php
if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit;
}

require __DIR__ . '/../connectDB.php';

$rows = db_query("SELECT NO_ID, PWORD FROM ADMIN");
$hashed = 0;
$skipped = 0;
foreach (mysqli_fetch_all($rows, MYSQLI_ASSOC) as $row) {
	if (password_get_info($row['PWORD'])['algo'] !== null) {
		$skipped++;
		continue;
	}
	db_query("UPDATE ADMIN SET PWORD = ? WHERE NO_ID = ?", [password_hash($row['PWORD'], PASSWORD_DEFAULT), $row['NO_ID']]);
	$hashed++;
}

echo "Hashed: $hashed, already hashed: $skipped\n";
