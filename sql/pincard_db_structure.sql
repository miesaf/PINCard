-- PINCard database: schema (as on the server in 2026) and the lookup tables KOLEJ, ORGANIZER and PROG.
-- No accounts, students, events or attendance: add the first admin with tools/add-admin.php.

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

CREATE TABLE `ADMIN` (
  `NO_ID` int(10) NOT NULL,
  `PWORD` varchar(255) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `TYPE` varchar(4) NOT NULL,
  PRIMARY KEY (`NO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `ATTENDANCE` (
  `ATT_ID` int(10) NOT NULL AUTO_INCREMENT,
  `ATT_STUD` int(10) NOT NULL,
  `ATT_EVN` int(8) NOT NULL,
  `ATT_TIME` datetime NOT NULL,
  `ATT_CAT` int(1) NOT NULL,
  `ATT_CRDT` int(2) NOT NULL,
  PRIMARY KEY (`ATT_ID`),
  KEY `ATT_STUD` (`ATT_STUD`),
  KEY `ATT_EVN` (`ATT_EVN`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`ATT_STUD`) REFERENCES `STUDENT` (`STUD_ID`) ON UPDATE CASCADE,
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`ATT_EVN`) REFERENCES `EVENT` (`EVN_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `EVENT` (
  `EVN_ID` int(8) NOT NULL,
  `EVN_NAME` varchar(200) NOT NULL,
  `EVN_ORG` varchar(5) NOT NULL,
  `EVN_TYPE` varchar(4) NOT NULL DEFAULT 'HEPA',
  `EVN_HOUR` int(3) NOT NULL,
  `EVN_VENUE` text NOT NULL,
  `EVN_DATE` date NOT NULL,
  `EVN_RB` int(10) NOT NULL,
  `EVN_RT` datetime NOT NULL,
  PRIMARY KEY (`EVN_ID`),
  KEY `EVN_RB` (`EVN_RB`),
  KEY `EVN_ORG` (`EVN_ORG`),
  CONSTRAINT `event_ibfk_1` FOREIGN KEY (`EVN_ORG`) REFERENCES `ORGANIZER` (`ORG_ID`) ON UPDATE CASCADE,
  CONSTRAINT `event_ibfk_2` FOREIGN KEY (`EVN_RB`) REFERENCES `ADMIN` (`NO_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `KOLEJ` (
  `ID_KOLEJ` varchar(5) NOT NULL,
  `KOLEJ` varchar(30) NOT NULL,
  PRIMARY KEY (`ID_KOLEJ`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `ORGANIZER` (
  `ORG_ID` varchar(5) NOT NULL,
  `ORG_NAME` varchar(200) NOT NULL,
  PRIMARY KEY (`ORG_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `PROG` (
  `ID_PROG` varchar(5) NOT NULL,
  `PROG` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_PROG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `STUDENT` (
  `STUD_ID` int(10) NOT NULL,
  `NAME` varchar(70) NOT NULL,
  `IC_NUM` bigint(12) NOT NULL,
  `PROG` varchar(5) NOT NULL,
  `SEM` varchar(5) NOT NULL,
  `KOLEJ` varchar(5) NOT NULL,
  `ROOM` varchar(10) NOT NULL,
  PRIMARY KEY (`STUD_ID`),
  KEY `PROG` (`PROG`),
  KEY `KOLEJ` (`KOLEJ`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`PROG`) REFERENCES `PROG` (`ID_PROG`) ON UPDATE CASCADE,
  CONSTRAINT `student_ibfk_2` FOREIGN KEY (`KOLEJ`) REFERENCES `KOLEJ` (`ID_KOLEJ`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



INSERT INTO `KOLEJ` VALUES ('JASBE','Sulok Belingkung');
INSERT INTO `KOLEJ` VALUES ('JASRI','Sempana Riau');
INSERT INTO `KOLEJ` VALUES ('JATSA','Taming Sari');
INSERT INTO `KOLEJ` VALUES ('JBBAI','Baiduri');
INSERT INTO `KOLEJ` VALUES ('JBMUT','Mutiara');
INSERT INTO `KOLEJ` VALUES ('JBNIL','Nilam');
INSERT INTO `KOLEJ` VALUES ('JCINT','Intan');
INSERT INTO `KOLEJ` VALUES ('JCSAB','Sempana Alam Buana');
INSERT INTO `KOLEJ` VALUES ('JCSMK','Si Manja Kini');
INSERT INTO `KOLEJ` VALUES ('JCZAM','Zamrud');
INSERT INTO `KOLEJ` VALUES ('JNRES','Non-Resident (NR)');
INSERT INTO `KOLEJ` VALUES ('UNKWN','Tidak Diketahui');
INSERT INTO `ORGANIZER` VALUES ('JH01','Majlis Perwakilan Pelajar');
INSERT INTO `ORGANIZER` VALUES ('JH02','Persatuan Siswa/i Diploma Perakaunan (PERSIDA)');
INSERT INTO `ORGANIZER` VALUES ('JH03','Marketing Students Society Of UiTM Johor (MARKETEERS)');
INSERT INTO `ORGANIZER` VALUES ('JH04','Bachelor Of Accountancy Student Association (BACCSA)');
INSERT INTO `ORGANIZER` VALUES ('JH05','Nadi Diploma In Investment Analysis (NADIIA)');
INSERT INTO `ORGANIZER` VALUES ('JH06','Finance Student Association (FISA)');
INSERT INTO `ORGANIZER` VALUES ('JH07','Persatuan Mahasiswa Masjid As-Syakirin (PERMAS)');
INSERT INTO `ORGANIZER` VALUES ('JH08','Persatuan Seni Silat Cekak Pusaka Utz. Hanafi (PSSCPUH)');
INSERT INTO `ORGANIZER` VALUES ('JH09','Persatuan Bahasa Mandarin (PBM)');
INSERT INTO `ORGANIZER` VALUES ('JH10','Persatuan Sains Komputer (COMPASS)');
INSERT INTO `ORGANIZER` VALUES ('JH100','Jawatankuasa Perwakilan Kolej Firdaus UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH101','Jawatankuasa Perwakilan Kolej Salsabila UiTMCJ K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH102','Investment Management Student Association (IMSA)');
INSERT INTO `ORGANIZER` VALUES ('JH103','Persatuan Ikatan Mahasiswa Anak Seni (i-Mas) UiTMCJ K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH104','Makers Club (MAC) UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH105','Persatuan Badan Peer Kaunselor UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH106','Jabatan Pertahanan Awam UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH107','Kompeni Delta UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH108','Kompeni Charlie UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH109','Kompeni Echo UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH11','Business Society (DBS)');
INSERT INTO `ORGANIZER` VALUES ('JH110','Kompeni Bravo UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH111','Persatuan Penyayang UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH112','Kelab Technophyte (ROBOTIK) UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH113','Persatuan Bulan Sabit Merah UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH114','Kelab Debat & Pidato UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH115','Kelab Rekreasi UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH116','UiTM Pasir Gudang Football Club');
INSERT INTO `ORGANIZER` VALUES ('JH117','Persatuan Sukarelawan Bencana UiTM Johor');
INSERT INTO `ORGANIZER` VALUES ('JH118','Kelab Ping Pong UiTM Cawangan Johor Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH119','Kelab Bola Tampar UiTM Cawangan Johor Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH12','Diploma In Banking Association (DIBA)');
INSERT INTO `ORGANIZER` VALUES ('JH120','Persatuan Im-Flux UiTM Cawangan Johor Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH121','Modern Language Society UiTM Caw. Johor Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH122','Checkmate Club UiTM Cawangan Johor Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH123','Kompeni AlphaUiTM Cj Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH124','Persatuanjami\'Yah Al-Lughah Al-\'Arabiyah UiTMCJ Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH13','Diploma In Information Management Society (Diim Society)');
INSERT INTO `ORGANIZER` VALUES ('JH15','Transport Society (D\'Trans) UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH16','Kelab Dinamis');
INSERT INTO `ORGANIZER` VALUES ('JH17','Majlis Sukan Pelajar (MSP)');
INSERT INTO `ORGANIZER` VALUES ('JH18','Kelab Taekwondo (WTF)');
INSERT INTO `ORGANIZER` VALUES ('JH19','Persatuan Seni Silat Gayong Malaysia (PSSGM)');
INSERT INTO `ORGANIZER` VALUES ('JH20','Kelab Inspirasi');
INSERT INTO `ORGANIZER` VALUES ('JH21','Kelab Sahabat');
INSERT INTO `ORGANIZER` VALUES ('JH22','Kelab Debat & Pidato');
INSERT INTO `ORGANIZER` VALUES ('JH23','Perfoming Arts Club (PAC)');
INSERT INTO `ORGANIZER` VALUES ('JH25','Kelab Muhibbah');
INSERT INTO `ORGANIZER` VALUES ('JH27','Photography Club');
INSERT INTO `ORGANIZER` VALUES ('JH28','Kelab Bomba');
INSERT INTO `ORGANIZER` VALUES ('JH29','Kelab Alpha');
INSERT INTO `ORGANIZER` VALUES ('JH30','Kelab Bravo');
INSERT INTO `ORGANIZER` VALUES ('JH31','Kelab Kembara');
INSERT INTO `ORGANIZER` VALUES ('JH33','Kelab Charlie');
INSERT INTO `ORGANIZER` VALUES ('JH34','Kelab Brass Band');
INSERT INTO `ORGANIZER` VALUES ('JH35','Jabatan Pertahanan Awam (JPA3)');
INSERT INTO `ORGANIZER` VALUES ('JH36','Persatuan Bulan Sabit Malaysia (PBSM)');
INSERT INTO `ORGANIZER` VALUES ('JH38','Kelab Karate - Do');
INSERT INTO `ORGANIZER` VALUES ('JH39','Pertubuhan Seni Silat Sendeng Malaysia');
INSERT INTO `ORGANIZER` VALUES ('JH41','Islamic Society Of United Teenaders (In-Soft)');
INSERT INTO `ORGANIZER` VALUES ('JH42','Jawatankuasa Kepimpinan Komander Kesatria (JKKK)');
INSERT INTO `ORGANIZER` VALUES ('JH43','English Language Society (ELS)');
INSERT INTO `ORGANIZER` VALUES ('JH44','Kelab Al-Ibtikar');
INSERT INTO `ORGANIZER` VALUES ('JH45','Kelab Silat Olahraga');
INSERT INTO `ORGANIZER` VALUES ('JH46','Persatuan Quantitative Association (Quasar)');
INSERT INTO `ORGANIZER` VALUES ('JH47','Gabungan Pelajar Melayu Semenanjung (GPMS)');
INSERT INTO `ORGANIZER` VALUES ('JH48','Kelab Rekreasi');
INSERT INTO `ORGANIZER` VALUES ('JH49','Sekretariat Unit Rakan Muda');
INSERT INTO `ORGANIZER` VALUES ('JH50','Persatuan Usahawan Siswa Bistari');
INSERT INTO `ORGANIZER` VALUES ('JH51','Persatuan Diploma In Banking Association City (DIBA City) - Jb');
INSERT INTO `ORGANIZER` VALUES ('JH52','Kelab Usahawan');
INSERT INTO `ORGANIZER` VALUES ('JH53','Islamic Rhythm And Management (IRAMA)');
INSERT INTO `ORGANIZER` VALUES ('JH54','Persatuan Islamic Banking Students Association (IBSA)');
INSERT INTO `ORGANIZER` VALUES ('JH55','Kelab Bowling');
INSERT INTO `ORGANIZER` VALUES ('JH56','Persatuan Creative And Critical Thinking Associations (CREITHA)');
INSERT INTO `ORGANIZER` VALUES ('JH57','Persatuan Massive Bussiness Affiliation (MBA) UiTMCJ K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH58','Persatuan Jawatankuasa Sukan Pelajar Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH59','Jawatankuasa Perwakilan Kolej Jb');
INSERT INTO `ORGANIZER` VALUES ('JH60','Kelab Badminton');
INSERT INTO `ORGANIZER` VALUES ('JH61','Kelab Ekspedisi & Rekreasi - Jb');
INSERT INTO `ORGANIZER` VALUES ('JH62','Persatuan Accounting Community Empire (ACE) - Jb');
INSERT INTO `ORGANIZER` VALUES ('JH63','Kelab Pengguna');
INSERT INTO `ORGANIZER` VALUES ('JH64','Kelab Tracking, Travelling & Charity');
INSERT INTO `ORGANIZER` VALUES ('JH65','Kelab The. M.A. - Jb');
INSERT INTO `ORGANIZER` VALUES ('JH66','Kelab Akuatik');
INSERT INTO `ORGANIZER` VALUES ('JH67','Kelab Catur');
INSERT INTO `ORGANIZER` VALUES ('JH68','Persatuan \'Law Society\'');
INSERT INTO `ORGANIZER` VALUES ('JH69','Kelab Al-Falah');
INSERT INTO `ORGANIZER` VALUES ('JH70','Kelab Kumpulan Rakan Siswa Jasa Malysia (KARISMA)');
INSERT INTO `ORGANIZER` VALUES ('JH71','Persatuan Sahabat Nr');
INSERT INTO `ORGANIZER` VALUES ('JH72','Entrepreneur Club (UiTMCJ Kampus Pasir Gudang)');
INSERT INTO `ORGANIZER` VALUES ('JH73','Persatuan Silat Seni Gayong Malaysia (UiTMCJ Kampus Pasir Gudang)');
INSERT INTO `ORGANIZER` VALUES ('JH74','Kelab Anak Seni Warisan Abad (KASWARA)');
INSERT INTO `ORGANIZER` VALUES ('JH75','Kelab Kebudayaan Dan Kesenian Islam (Kampus Bandaraya-Jb)');
INSERT INTO `ORGANIZER` VALUES ('JH76','Kelab Usahawan Siswazah Tani (MyAgrosis)');
INSERT INTO `ORGANIZER` VALUES ('JH77','J/Kuasa Perwakilan Komander Kesatria UiTM Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH78','Persatuan Pengurusan Maklumat Dan Rekod (IMARC)');
INSERT INTO `ORGANIZER` VALUES ('JH79','Kelab Golf Pelajar UiTM Segamat');
INSERT INTO `ORGANIZER` VALUES ('JH80','Persatuan Photography & Media');
INSERT INTO `ORGANIZER` VALUES ('JH81','Kelab Sudoku');
INSERT INTO `ORGANIZER` VALUES ('JH82','UiTM Johor Handball Titans Club');
INSERT INTO `ORGANIZER` VALUES ('JH83','Kelab Enactus');
INSERT INTO `ORGANIZER` VALUES ('JH84','Johore Fire Volleyball Club.');
INSERT INTO `ORGANIZER` VALUES ('JH85','Persatuan Peer Kaunselor (PEERS)');
INSERT INTO `ORGANIZER` VALUES ('JH86','Persatuan Pencak Silat UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH87','Persatuan Seni Silat Cekak Malaysia UiTM Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH88','Persatuan Taekwondo UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH89','Kelab 1m4u UiTMCJ Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH90','Persatuan Seni Silat Cekak Ustaz Hanafi UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH91','Electrical Student Technology Club (ESTECH) UiTMCJ K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH92','Civil Engineering Club (CIVEC) UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH93','Persatuan Bahasa Cina Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH94','Kelab Olahraga UiTMCJ Kampus Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH95','Chemical Engineering Student Society (CHESS) UiTMcj K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH96','Kelab Fx');
INSERT INTO `ORGANIZER` VALUES ('JH97','Persatuan Legasi Mahasiswa Pusat Islam (LPI) UiTMCJ K. Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH98','Persatuan Mahasiswa & Mahasiswi Kejuruteraan Mekanikal UiTMCJ K.Pasir Gudang');
INSERT INTO `ORGANIZER` VALUES ('JH99','Jawatankuasa Perwakilan Non-Resident (JPNR) UiTMCJ K. Pasir Gudang');
INSERT INTO `PROG` VALUES ('AC110','Diploma Perakaunan');
INSERT INTO `PROG` VALUES ('AC220','Ijazah Sarjana Muda Perakaunan (Kepujian)');
INSERT INTO `PROG` VALUES ('BM111','Diploma Pengajian Perniagaan');
INSERT INTO `PROG` VALUES ('BM114','Diploma Analisis Pelaburan');
INSERT INTO `PROG` VALUES ('BM119','Diploma Pengajian Perbankan');
INSERT INTO `PROG` VALUES ('BM220','Sarjana Muda Pentadbiran Perniagaan (Kepujian) (Pemasaran)');
INSERT INTO `PROG` VALUES ('BM222','Sarjana Muda Pentadbiran Perniagaan (Kepujian) (Kewangan)');
INSERT INTO `PROG` VALUES ('BM229','Sarjana Muda Pentadbiran Perniagaan (Kepujian) (Perbankan Islam)');
INSERT INTO `PROG` VALUES ('BM240','Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Pemasaran');
INSERT INTO `PROG` VALUES ('BM242','Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Kewangan');
INSERT INTO `PROG` VALUES ('BM249','Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Perbankan Islam');
INSERT INTO `PROG` VALUES ('BM251','Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Pengurusan Pelaburan');
INSERT INTO `PROG` VALUES ('CS110','Diploma Sains Komputer');
INSERT INTO `PROG` VALUES ('CS113','Diploma Sains Kuantitatif');
INSERT INTO `PROG` VALUES ('CS143','Diploma Sains Matematik');
INSERT INTO `PROG` VALUES ('IM110','Diploma Pengurusan Maklumat');
INSERT INTO `PROG` VALUES ('IM226','Bachelor of Science (Hons) Resource Centre Management');
INSERT INTO `PROG` VALUES ('IM246','Ijazah Sarjana Muda Sains Maklumat (Kepujian) Pengurusan Rekod');
INSERT INTO `PROG` VALUES ('PD002','Pra-Perdagangan');

SET FOREIGN_KEY_CHECKS = 1;
