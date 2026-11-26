<?php
// Adds an admin account, or sets a new password for one. Run from the PINCard folder:
//   php tools/add-admin.php <staff number> <REDD|BLCK> "<name>"
// and type the password when asked. BLCK (Penolong & Pengurus Asrama) manages accounts and settings,
// REDD (Penyelia Asrama) events, attendance and students.
if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit;
}

[, $id, $type, $name] = $argv + [null, null, null, null];
if (!ctype_digit((string) $id) || !in_array($type, ['REDD', 'BLCK'], true) || !$name) {
	fwrite(STDERR, "Usage: php tools/add-admin.php <staff number> <REDD|BLCK> \"<name>\"\n");
	exit(1);
}

require __DIR__ . '/../connectDB.php';

fwrite(STDOUT, "Password: ");
system('stty -echo 2>/dev/null');
$password = rtrim((string) fgets(STDIN), "\r\n");
system('stty echo 2>/dev/null');
fwrite(STDOUT, "\n");
if ($password === '') {
	fwrite(STDERR, "No password given.\n");
	exit(1);
}

$ok = db_query(
	"INSERT INTO ADMIN (NO_ID, PWORD, NAME, TYPE) VALUES (?, ?, ?, ?)
	 ON DUPLICATE KEY UPDATE PWORD = VALUES(PWORD), NAME = VALUES(NAME), TYPE = VALUES(TYPE)",
	[$id, password_hash($password, PASSWORD_DEFAULT), $name, $type]
);
echo $ok ? "Saved admin $id.\n" : "Failed: " . mysqli_error($condb) . "\n";
