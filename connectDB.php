<?php
	// Database connection. The credentials live in config.php (not in git); copy config.example.php to start.
	mysqli_report(MYSQLI_REPORT_OFF);
	date_default_timezone_set('Asia/Kuala_Lumpur');

	$pincard_config = require __DIR__ . '/config.php';
	$condb = mysqli_connect($pincard_config['db_host'], $pincard_config['db_user'], $pincard_config['db_pass'], $pincard_config['db_name']);
	unset($pincard_config);

	if (!$condb)
	{
		http_response_code(500);
		die("Database connection failed.");
	}

	// The 2017 code relies on MySQL's non-strict mode.
	mysqli_query($condb, "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

	// Runs a prepared statement: a mysqli_result for SELECT, true or false otherwise.
	function db_query(string $sql, array $params = [])
	{
		global $condb;
		return mysqli_execute_query($condb, $sql, $params);
	}

	// The first row of a SELECT as an array, or null.
	function db_row(string $sql, array $params = []): ?array
	{
		$result = db_query($sql, $params);
		$row = $result ? mysqli_fetch_assoc($result) : null;
		return $row ?: null;
	}

	// A lookup map (first column => second column) from a two-column SELECT.
	function db_map(string $sql, array $params = []): array
	{
		$map = [];
		$result = db_query($sql, $params);
		while ($result && ($row = mysqli_fetch_row($result)))
		{
			$map[$row[0]] = $row[1];
		}
		return $map;
	}

	// Escapes a value for HTML output.
	function e($value): string
	{
		return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}

	// A value as a JavaScript literal, safe inside a <script> block (wrap in e() inside an attribute).
	function js($value): string
	{
		return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE);
	}

	// The pop-up message the pages show after an action, then an optional redirect or a step back.
	function alert_page(string $message, ?string $url = null, bool $back = false): void
	{
		echo '<script>window.alert(' . js($message) . ');' . ($back ? ' window.history.go(-1);' : '') . '</script>';
		if ($url !== null)
		{
			echo '<meta http-equiv="refresh" content="0; url=' . e($url) . '" />';
		}
	}

	// A text value from $_GET or $_POST, trimmed, or null when it is missing (or not a single value).
	function request_text(array $source, string $key): ?string
	{
		$value = $source[$key] ?? null;
		return is_string($value) ? trim($value) : null;
	}

	// A positive whole-number ID from $_GET or $_POST, or null.
	function request_id(array $source, string $key): ?int
	{
		$id = filter_var($source[$key] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
		return $id === false ? null : $id;
	}

	// A list of positive whole-number IDs from a checkbox array (name="X[]"); anything else is dropped.
	function request_ids(array $source, string $key): array
	{
		$values = $source[$key] ?? [];
		if (!is_array($values))
		{
			return [];
		}
		$ids = [];
		foreach ($values as $value)
		{
			$id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
			if ($id !== false)
			{
				$ids[] = $id;
			}
		}
		return array_values(array_unique($ids));
	}

	// Reads the CSV file uploaded in $_FILES[$field] (bulk forms, up to 500 KB, never stored): its non-empty
	// rows as arrays of trimmed strings, or null after telling the user what is wrong.
	function read_csv_upload(string $field, string $delimiter): ?array
	{
		$file = $_FILES[$field] ?? null;
		$error = null;
		if (!is_array($file) || !is_string($file['name'] ?? null) || !is_string($file['tmp_name'] ?? null)
			|| ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE)
		{
			$error = "RALAT: Sila pilih fail CSV!";
		}
		elseif (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'csv')
		{
			$error = "RALAT: Fail harus dalam format CSV!";
		}
		elseif (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $file['size'] > 500000)
		{
			$error = "RALAT: Saiz fail melebihi 500KB!";
		}
		elseif ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name']))
		{
			$error = "RALAT: Fail gagal dimuat naik!";
		}
		elseif ($file['size'] <= 0)
		{
			$error = "RALAT: Saiz fail adalah sifar/tiada data!";
		}
		elseif (function_exists('finfo_open'))
		{
			$mime = (string) finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['tmp_name']);
			if (!str_starts_with($mime, 'text/') && !in_array($mime, ['application/csv', 'application/vnd.ms-excel'], true))
			{
				$error = "RALAT: Fail harus dalam format CSV!";
			}
		}
		if ($error !== null)
		{
			alert_page($error, null, true);
			return null;
		}

		$rows = [];
		$handle = fopen($file['tmp_name'], 'r');
		while (($data = fgetcsv($handle, 10000, $delimiter, '"', '')) !== false)
		{
			$data = array_map(fn($value) => trim((string) $value), $data);
			if (!$rows)
			{
				$data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);	// Excel's UTF-8 byte order mark
			}
			if (implode('', $data) !== '')
			{
				$rows[] = $data;
			}
		}
		fclose($handle);
		return $rows;
	}

	// Sends a visitor who is not logged in to the login page, and one whose account type is not
	// listed (BLUE = student, REDD = penyelia, BLCK = pengurus) to the main page.
	function require_login(string ...$types): void
	{
		if (empty($_SESSION['pc_login']))
		{
			header("Location: index.php?err=expired");
			exit;
		}
		if ($types && !in_array($_SESSION['pc_priv'] ?? '', $types, true))
		{
			header("Location: main.php");
			exit;
		}
	}
?>
