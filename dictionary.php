<?php
	$ACC_TYPE["BLUE"]	= "Blue (Pelajar)";
	$ACC_TYPE["REDD"]	= "Red (Penyelia Asrama)";
	$ACC_TYPE["BLCK"]	= "Black (Penolong & Pengurus Asrama)";

	/*
	$COLL_NAME["JASBE"]	= "Sulok Belingkung";
	$COLL_NAME["JASRI"]	= "Sempana Riau";
	$COLL_NAME["JATSA"]	= "Taming Sari";
	$COLL_NAME["JBBAI"]	= "Baiduri";
	$COLL_NAME["JBMUT"]	= "Mutiara";
	$COLL_NAME["JBNIL"]	= "Nilam";
	$COLL_NAME["JCINT"]	= "Intan";
	$COLL_NAME["JCSAB"]	= "Sempana Alam Buana";
	$COLL_NAME["JCSMK"]	= "Si Manja Kini";
	$COLL_NAME["JNRES"]	= "Non-Resident (NR)";
	$COLL_NAME["UNKWN"]	= "Unknown";

	
	$PROG_NAME["AC110"]	= "Diploma in Accountancy";
	$PROG_NAME["AC220"]	= "Bachelor of Accountancy (Hons)";
	$PROG_NAME["BM111"]	= "Diploma in Business Studies";
	$PROG_NAME["BM114"]	= "Diploma in Investment Analysis ";
	$PROG_NAME["BM119"]	= "Diploma in Banking Studies";
	$PROG_NAME["BM240"]	= "Bachelor of Business Administration (Hons) Marketing";
	$PROG_NAME["BM242"]	= "Bachelor of Business Administration (Hons) Finance";
	$PROG_NAME["BM249"]	= "Bachelor of Business Administration (Hons) Islamic Banking";
	$PROG_NAME["BM251"]	= "Bachelor of Business Administration (Hons) Investment Management";
	$PROG_NAME["CS110"]	= "Diploma in Computer Science";
	$PROG_NAME["CS113"]	= "Diploma in Quantitative Science";
	$PROG_NAME["CS143"]	= "Diploma in Mathematical Science";
	$PROG_NAME["IM110"]	= "Diploma in Information Management";
	$PROG_NAME["IM246"]	= "Bachelor of Information Science (Hons) Records Management";
	$PROG_NAME["PD002"]	= "Pre-Commerce";
	*/
	
	// Event Organization Stage Category
	$DEVN_TYPE["COLL"]	= "Kolej";
	$DEVN_TYPE["HEPA"]	= "HEP / Akademik";
	$DEVN_TYPE["FACT"]	= "Fakulti";
	$DEVN_TYPE["UNIV"]	= "UiTM";
	$DEVN_TYPE["MAND"]	= "Program Wajib";
	
	// Display Attendance Type (Type of Student Involvement)
	$DATT_CAT[1]	= "Penganjur / AJK";
	$DATT_CAT[2]	= "Peserta";
	$DATT_CAT[3]	= "Penonton";
	
	// Dislay Attendance Credit Merit Value
	$ATT_CREDIT["COLL"][1]	= 10;
	$ATT_CREDIT["COLL"][2]	= 5;
	$ATT_CREDIT["COLL"][3]	= 3;
	$ATT_CREDIT["HEPA"][1]	= 10;
	$ATT_CREDIT["HEPA"][2]	= 5;
	$ATT_CREDIT["HEPA"][3]	= 3;
	$ATT_CREDIT["FACT"][1]	= 10;
	$ATT_CREDIT["FACT"][2]	= 5;
	$ATT_CREDIT["FACT"][3]	= 5;
	$ATT_CREDIT["UNIV"][1]	= 20;
	$ATT_CREDIT["UNIV"][2]	= 10;
	$ATT_CREDIT["UNIV"][3]	= 5;
	$ATT_CREDIT["MAND"][1]	= 10;
	$ATT_CREDIT["MAND"][2]	= 5;
	$ATT_CREDIT["MAND"][3]	= 5;
	
	// Merit Credit Formulae: the credit for an event level and an involvement category (0 if unknown)
	function att_credit($evn_type, $att_cat): int
	{
		global $ATT_CREDIT;
		return $ATT_CREDIT[(string) $evn_type][(int) $att_cat] ?? 0;
	}
	
	// Event hour credit limit
	$HOUR_LIM	= 42;

	// Reads the event form (evn_reg.php, evn_amend.php): [fields, error message or null].
	function read_event_form(array $post): array
	{
		global $DEVN_TYPE, $HOUR_LIM;
		$f = [
			'EVN_NAME'	=> request_text($post, 'EVN_NAME') ?? '',
			'EVN_ORG'	=> request_text($post, 'EVN_ORG') ?? '',
			'EVN_TYPE'	=> request_text($post, 'EVN_TYPE') ?? '',
			'EVN_HOUR'	=> filter_var($post['EVN_HOUR'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]),
			'EVN_VENUE'	=> request_text($post, 'EVN_VENUE') ?? '',
			'EVN_DATE'	=> request_text($post, 'EVN_DATE') ?? '',
		];
		$date = DateTime::createFromFormat('!Y-m-d', $f['EVN_DATE']);
		if ($f['EVN_NAME'] === '' || $f['EVN_ORG'] === '' || !isset($DEVN_TYPE[$f['EVN_TYPE']])
			|| $f['EVN_HOUR'] === false || !$date || $date->format('Y-m-d') !== $f['EVN_DATE'])
		{
			return [$f, "Sila lengkapkan nama, penganjur, tahap, jam temu dan tarikh program!"];
		}
		if ($f['EVN_HOUR'] > $HOUR_LIM)
		{
			return [$f, "Jam temu program melebihi had " . $HOUR_LIM . " jam!"];
		}
		return [$f, null];
	}
	
	// Checks a student's details (reg_stud.php, imp_stud.php), given in the order
	// STUD_ID, NAME, IC_NUM, PROG, SEM, KOLEJ, ROOM: [fields, error message or null].
	function read_student(array $values): array
	{
		$values = array_pad(array_values($values), 7, '');
		$f = array_combine(['STUD_ID', 'NAME', 'IC_NUM', 'PROG', 'SEM', 'KOLEJ', 'ROOM'],
			array_map(fn($value) => is_string($value) ? trim($value) : '', array_slice($values, 0, 7)));
		$f['IC_NUM'] = str_replace(['-', ' '], '', $f['IC_NUM']);	// IC number without dashes

		if (!ctype_digit($f['STUD_ID']) || strlen($f['STUD_ID']) > 10 || (int) $f['STUD_ID'] > 2147483647 || (int) $f['STUD_ID'] === 0)
		{
			return [$f, "Nombor pelajar tidak sah!"];
		}
		if ($f['NAME'] === '' || !ctype_digit($f['IC_NUM']) || strlen($f['IC_NUM']) > 12)
		{
			return [$f, "Nama atau nombor kad pengenalan tidak sah!"];
		}
		if (!db_row("SELECT ID_PROG FROM PROG WHERE ID_PROG = ?", [$f['PROG']]) || !db_row("SELECT ID_KOLEJ FROM KOLEJ WHERE ID_KOLEJ = ?", [$f['KOLEJ']]))
		{
			return [$f, "Kod program pengajian atau kolej kediaman tidak wujud!"];
		}
		return [$f, null];
	}

	// Daftar Kehadiran Pukal
	$D_STAT["DUP"]	= "<font color=\"#ff9900\"><b>Duplikasi pendaftaran! Kehadiran tidak didaftarkan.</b></font>";
	$D_STAT["BER"]	= "<font color=\"#2eb82e\"><b>Pendaftaran berjaya!</b></font>";
	$D_STAT["GAG"]	= "<font color=\"red\"><b>Pendafataran gagal!</b></font>";
	
	// Daftar Pelajar Pukal
	$IMP_STAT["DUP"]	= "<font color=\"#ff9900\"><b>Duplikasi pendaftaran! Pelajar tidak didaftarkan.</b></font>";
	$IMP_STAT["BER"]	= "<font color=\"#2eb82e\"><b>Pendaftaran berjaya!</b></font>";
	$IMP_STAT["GAG"]	= "<font color=\"red\"><b>Pendafataran gagal!</b></font>";
?>