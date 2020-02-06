-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2017 at 04:25 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `pincard`
--

-- --------------------------------------------------------

--
-- Table structure for table `ADMIN`
--

CREATE TABLE `ADMIN` (
  `NO_ID` int(10) NOT NULL,
  `PWORD` varchar(100) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `TYPE` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ADMIN`
--

INSERT INTO `ADMIN` (`NO_ID`, `PWORD`, `NAME`, `TYPE`) VALUES
(200, 'red', 'Aizad (Penyelia Asrama)', 'REDD'),
(300, 'black', 'Amirul (Penolong & Pengurus Asrama)', 'BLCK'),
(2001, 'red', 'Mohamad Faried Bin Abd Shukor', 'REDD'),
(2002, 'red', 'Zulkiflee Bin Yeop', 'REDD'),
(2003, 'red', 'Ahmaz Ikhwan Bin Ahmad', 'REDD'),
(3001, 'black', 'Abd Rahim Bin Abd Majid', 'BLCK'),
(3002, 'black', 'Rafidah Binti Ab. Mutalib', 'BLCK'),
(3003, 'black', 'Mohd Najib Bin Abd Rahman', 'BLCK'),
(3004, 'black', 'Pozi Binti Mohd Nor', 'BLCK'),
(3005, 'black', 'Zalina Binti Haji. Hitam', 'BLCK'),
(3006, 'black', 'Nor Azlina Binti Ishak', 'BLCK'),
(3007, 'black', 'Noryasmin Binti Zulkarnain', 'BLCK');

-- --------------------------------------------------------

--
-- Table structure for table `ATTENDANCE`
--

CREATE TABLE `ATTENDANCE` (
  `ATT_ID` int(10) NOT NULL,
  `ATT_STUD` int(10) NOT NULL,
  `ATT_EVN` int(8) NOT NULL,
  `ATT_TIME` datetime NOT NULL,
  `ATT_CAT` int(1) NOT NULL,
  `ATT_CRDT` int(2) NOT NULL,
  `ATT_STAT` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ATTENDANCE`
--

INSERT INTO `ATTENDANCE` (`ATT_ID`, `ATT_STUD`, `ATT_EVN`, `ATT_TIME`, `ATT_CAT`, `ATT_CRDT`, `ATT_STAT`) VALUES
(1, 2013000001, 17070202, '2017-07-07 17:58:41', 1, 10, ''),
(2, 2014000001, 17070202, '2017-07-07 17:58:48', 2, 5, ''),
(3, 2015000001, 17070202, '2017-07-07 17:59:26', 3, 3, ''),
(4, 2013000001, 17070204, '2017-07-07 18:01:31', 3, 3, ''),
(5, 2014000001, 17070204, '2017-07-07 18:01:39', 1, 10, ''),
(6, 2015000001, 17070204, '2017-07-07 18:01:45', 2, 5, ''),
(7, 2013000001, 17070201, '2017-07-25 23:38:40', 1, 10, ''),
(8, 2013000001, 17070203, '2017-07-25 23:38:51', 3, 3, ''),
(9, 2013000001, 17071201, '2017-07-25 23:38:58', 2, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `EVENT`
--

CREATE TABLE `EVENT` (
  `EVN_ID` int(8) NOT NULL,
  `EVN_NAME` varchar(200) NOT NULL,
  `EVN_ORG` varchar(5) NOT NULL,
  `EVN_VENUE` text NOT NULL,
  `EVN_DATE` date NOT NULL,
  `EVN_TYPE` varchar(4) NOT NULL DEFAULT 'HEPA',
  `EVN_RB` int(10) NOT NULL,
  `EVN_RT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `EVENT`
--

INSERT INTO `EVENT` (`EVN_ID`, `EVN_NAME`, `EVN_ORG`, `EVN_VENUE`, `EVN_DATE`, `EVN_TYPE`, `EVN_RB`, `EVN_RT`) VALUES
(17070201, 'Hari Eksplorasi Pertolongan Cemas', 'JH36', 'Sekitar UiTM Cawangan Johor Kampus Segamat', '2017-07-05', 'HEPA', 300, '2017-07-02 22:21:12'),
(17070202, 'Titian Kasih Sejunjung Harapan', 'JH36', 'Teluk Gorek Chalet & Campsite, Mersing', '2017-07-18', 'HEPA', 300, '2017-07-02 22:24:29'),
(17070203, 'Hari Kemahiran Pertolongan Cemas', 'JH36', 'Padang Kawad', '2017-07-02', 'HEPA', 300, '2017-07-02 22:42:47'),
(17070204, 'Hari Tautan Ukhwah Kampus Pasir Gudang', 'JH98', 'Sekitar UiTM Cawangan Johor Kampus Segamat', '2017-07-28', 'HEPA', 3004, '2017-07-03 23:14:25'),
(17071201, 'Lestari Cinta Menara Gading', 'JH04', 'Dewan Karyawan 1', '2017-07-23', 'HEPA', 300, '2017-07-12 12:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `KOLEJ`
--

CREATE TABLE `KOLEJ` (
  `ID_KOLEJ` varchar(5) NOT NULL,
  `KOLEJ` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `KOLEJ`
--

INSERT INTO `KOLEJ` (`ID_KOLEJ`, `KOLEJ`) VALUES
('JASBE', 'Sulok Belingkung'),
('JASRI', 'Sempana Riau'),
('JATSA', 'Taming Sari'),
('JBBAI', 'Baiduri'),
('JBMUT', 'Mutiara'),
('JBNIL', 'Nilam'),
('JCINT', 'Intan'),
('JCSAB', 'Sempana Alam Buana'),
('JCSMK', 'Si Manja Kini'),
('JNRES', 'Non-Resident (NR)'),
('UNKWN', 'Tidak Diketahui');

-- --------------------------------------------------------

--
-- Table structure for table `ORGANIZER`
--

CREATE TABLE `ORGANIZER` (
  `ORG_ID` varchar(5) NOT NULL,
  `ORG_NAME` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ORGANIZER`
--

INSERT INTO `ORGANIZER` (`ORG_ID`, `ORG_NAME`) VALUES
('JH01', 'Majlis Perwakilan Pelajar'),
('JH02', 'Persatuan Siswa/i Diploma Perakaunan (PERSIDA)'),
('JH03', 'Marketing Students Society Of UiTM Johor (MARKETEERS)'),
('JH04', 'Bachelor Of Accountancy Student Association (BACCSA)'),
('JH05', 'Nadi Diploma In Investment Analysis (NADIIA)'),
('JH06', 'Finance Student Association (FISA)'),
('JH07', 'Persatuan Mahasiswa Masjid As-Syakirin (PERMAS)'),
('JH08', 'Persatuan Seni Silat Cekak Pusaka Utz. Hanafi (PSSCPUH)'),
('JH09', 'Persatuan Bahasa Mandarin (PBM)'),
('JH10', 'Persatuan Sains Komputer (COMPASS)'),
('JH100', 'Jawatankuasa Perwakilan Kolej Firdaus UiTMCJ Kampus Pasir Gudang'),
('JH101', 'Jawatankuasa Perwakilan Kolej Salsabila UiTMCJ K. Pasir Gudang'),
('JH102', 'Investment Management Student Association (IMSA)'),
('JH103', 'Persatuan Ikatan Mahasiswa Anak Seni (i-Mas) UiTMCJ K. Pasir Gudang'),
('JH104', 'Makers Club (MAC) UiTMCJ Kampus Pasir Gudang'),
('JH105', 'Persatuan Badan Peer Kaunselor UiTMCJ Kampus Pasir Gudang'),
('JH106', 'Jabatan Pertahanan Awam UiTMCJ Kampus Pasir Gudang'),
('JH107', 'Kompeni Delta UiTMCJ Kampus Pasir Gudang'),
('JH108', 'Kompeni Charlie UiTMCJ Kampus Pasir Gudang'),
('JH109', 'Kompeni Echo UiTMCJ Kampus Pasir Gudang'),
('JH11', 'Business Society (DBS)'),
('JH110', 'Kompeni Bravo UiTMCJ Kampus Pasir Gudang'),
('JH111', 'Persatuan Penyayang UiTMCJ Kampus Pasir Gudang'),
('JH112', 'Kelab Technophyte (ROBOTIK) UiTMCJ Kampus Pasir Gudang'),
('JH113', 'Persatuan Bulan Sabit Merah UiTMCJ Kampus Pasir Gudang'),
('JH114', 'Kelab Debat & Pidato UiTMCJ Kampus Pasir Gudang'),
('JH115', 'Kelab Rekreasi UiTMCJ Kampus Pasir Gudang'),
('JH116', 'UiTM Pasir Gudang Football Club'),
('JH117', 'Persatuan Sukarelawan Bencana UiTM Johor'),
('JH118', 'Kelab Ping Pong UiTM Cawangan Johor Kampus Pasir Gudang'),
('JH119', 'Kelab Bola Tampar UiTM Cawangan Johor Kampus Pasir Gudang'),
('JH12', 'Diploma In Banking Association (DIBA)'),
('JH120', 'Persatuan Im-Flux UiTM Cawangan Johor Kampus Pasir Gudang'),
('JH121', 'Modern Language Society UiTM Caw. Johor Kampus Pasir Gudang'),
('JH122', 'Checkmate Club UiTM Cawangan Johor Kampus Pasir Gudang'),
('JH123', 'Kompeni AlphaUiTM Cj Kampus Pasir Gudang'),
('JH124', 'Persatuanjami''Yah Al-Lughah Al-''Arabiyah UiTMCJ Pasir Gudang'),
('JH13', 'Diploma In Information Management Society (Diim Society)'),
('JH15', 'Transport Society (D''Trans) UiTMCJ Kampus Pasir Gudang'),
('JH16', 'Kelab Dinamis'),
('JH17', 'Majlis Sukan Pelajar (MSP)'),
('JH18', 'Kelab Taekwondo (WTF)'),
('JH19', 'Persatuan Seni Silat Gayong Malaysia (PSSGM)'),
('JH20', 'Kelab Inspirasi'),
('JH21', 'Kelab Sahabat'),
('JH22', 'Kelab Debat & Pidato'),
('JH23', 'Perfoming Arts Club (PAC)'),
('JH25', 'Kelab Muhibbah'),
('JH27', 'Photography Club'),
('JH28', 'Kelab Bomba'),
('JH29', 'Kelab Alpha'),
('JH30', 'Kelab Bravo'),
('JH31', 'Kelab Kembara'),
('JH33', 'Kelab Charlie'),
('JH34', 'Kelab Brass Band'),
('JH35', 'Jabatan Pertahanan Awam (JPA3)'),
('JH36', 'Persatuan Bulan Sabit Malaysia (PBSM)'),
('JH38', 'Kelab Karate - Do'),
('JH39', 'Pertubuhan Seni Silat Sendeng Malaysia'),
('JH41', 'Islamic Society Of United Teenaders (In-Soft)'),
('JH42', 'Jawatankuasa Kepimpinan Komander Kesatria (JKKK)'),
('JH43', 'English Language Society (ELS)'),
('JH44', 'Kelab Al-Ibtikar'),
('JH45', 'Kelab Silat Olahraga'),
('JH46', 'Persatuan Quantitative Association (Quasar)'),
('JH47', 'Gabungan Pelajar Melayu Semenanjung (GPMS)'),
('JH48', 'Kelab Rekreasi'),
('JH49', 'Sekretariat Unit Rakan Muda'),
('JH50', 'Persatuan Usahawan Siswa Bistari'),
('JH51', 'Persatuan Diploma In Banking Association City (DIBA City) - Jb'),
('JH52', 'Kelab Usahawan'),
('JH53', 'Islamic Rhythm And Management (IRAMA)'),
('JH54', 'Persatuan Islamic Banking Students Association (IBSA)'),
('JH55', 'Kelab Bowling'),
('JH56', 'Persatuan Creative And Critical Thinking Associations (CREITHA)'),
('JH57', 'Persatuan Massive Bussiness Affiliation (MBA) UiTMCJ K. Pasir Gudang'),
('JH58', 'Persatuan Jawatankuasa Sukan Pelajar Kampus Pasir Gudang'),
('JH59', 'Jawatankuasa Perwakilan Kolej Jb'),
('JH60', 'Kelab Badminton'),
('JH61', 'Kelab Ekspedisi & Rekreasi - Jb'),
('JH62', 'Persatuan Accounting Community Empire (ACE) - Jb'),
('JH63', 'Kelab Pengguna'),
('JH64', 'Kelab Tracking, Travelling & Charity'),
('JH65', 'Kelab The. M.A. - Jb'),
('JH66', 'Kelab Akuatik'),
('JH67', 'Kelab Catur'),
('JH68', 'Persatuan ''Law Society'''),
('JH69', 'Kelab Al-Falah'),
('JH70', 'Kelab Kumpulan Rakan Siswa Jasa Malysia (KARISMA)'),
('JH71', 'Persatuan Sahabat Nr'),
('JH72', 'Entrepreneur Club (UiTMCJ Kampus Pasir Gudang)'),
('JH73', 'Persatuan Silat Seni Gayong Malaysia (UiTMCJ Kampus Pasir Gudang)'),
('JH74', 'Kelab Anak Seni Warisan Abad (KASWARA)'),
('JH75', 'Kelab Kebudayaan Dan Kesenian Islam (Kampus Bandaraya-Jb)'),
('JH76', 'Kelab Usahawan Siswazah Tani (MyAgrosis)'),
('JH77', 'J/Kuasa Perwakilan Komander Kesatria UiTM Kampus Pasir Gudang'),
('JH78', 'Persatuan Pengurusan Maklumat Dan Rekod (IMARC)'),
('JH79', 'Kelab Golf Pelajar UiTM Segamat'),
('JH80', 'Persatuan Photography & Media'),
('JH81', 'Kelab Sudoku'),
('JH82', 'UiTM Johor Handball Titans Club'),
('JH83', 'Kelab Enactus'),
('JH84', 'Johore Fire Volleyball Club.'),
('JH85', 'Persatuan Peer Kaunselor (PEERS)'),
('JH86', 'Persatuan Pencak Silat UiTMCJ Kampus Pasir Gudang'),
('JH87', 'Persatuan Seni Silat Cekak Malaysia UiTM Kampus Pasir Gudang'),
('JH88', 'Persatuan Taekwondo UiTMCJ Kampus Pasir Gudang'),
('JH89', 'Kelab 1m4u UiTMCJ Pasir Gudang'),
('JH90', 'Persatuan Seni Silat Cekak Ustaz Hanafi UiTMCJ Kampus Pasir Gudang'),
('JH91', 'Electrical Student Technology Club (ESTECH) UiTMCJ K. Pasir Gudang'),
('JH92', 'Civil Engineering Club (CIVEC) UiTMCJ Kampus Pasir Gudang'),
('JH93', 'Persatuan Bahasa Cina Kampus Pasir Gudang'),
('JH94', 'Kelab Olahraga UiTMCJ Kampus Pasir Gudang'),
('JH95', 'Chemical Engineering Student Society (CHESS) UiTMcj K. Pasir Gudang'),
('JH96', 'Kelab Fx'),
('JH97', 'Persatuan Legasi Mahasiswa Pusat Islam (LPI) UiTMCJ K. Pasir Gudang'),
('JH98', 'Persatuan Mahasiswa & Mahasiswi Kejuruteraan Mekanikal UiTMCJ K.Pasir Gudang'),
('JH99', 'Jawatankuasa Perwakilan Non-Resident (JPNR) UiTMCJ K. Pasir Gudang');

-- --------------------------------------------------------

--
-- Table structure for table `PROG`
--

CREATE TABLE `PROG` (
  `ID_PROG` varchar(5) NOT NULL,
  `PROG` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PROG`
--

INSERT INTO `PROG` (`ID_PROG`, `PROG`) VALUES
('AC110', 'Diploma Perakaunan'),
('AC220', 'Ijazah Sarjana Muda Perakaunan (Kepujian)'),
('BM111', 'Diploma Pengajian Perniagaan'),
('BM114', 'Diploma Analisis Pelaburan'),
('BM119', 'Diploma Pengajian Perbankan'),
('BM240', 'Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Pemasaran'),
('BM242', 'Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Kewangan'),
('BM249', 'Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Perbankan Islam'),
('BM251', 'Ijazah Sarjana Muda Pentadbiran Perniagaan (Kepujian) Pengurusan Pelaburan'),
('CS110', 'Diploma Sains Komputer'),
('CS113', 'Diploma Sains Kuantitatif'),
('CS143', 'Diploma Sains Matematik'),
('IM110', 'Diploma Pengurusan Maklumat'),
('IM246', 'Ijazah Sarjana Muda Sains Maklumat (Kepujian) Pengurusan Rekod'),
('PD002', 'Pra-Perdagangan');

-- --------------------------------------------------------

--
-- Table structure for table `STUDENT`
--

CREATE TABLE `STUDENT` (
  `STUD_ID` int(10) NOT NULL,
  `NAME` varchar(70) NOT NULL,
  `IC_NUM` bigint(12) NOT NULL,
  `PROG` varchar(5) NOT NULL,
  `SEM` varchar(5) NOT NULL,
  `KOLEJ` varchar(5) NOT NULL,
  `ROOM` varchar(10) NOT NULL,
  `PWORD` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `STUDENT`
--

INSERT INTO `STUDENT` (`STUD_ID`, `NAME`, `IC_NUM`, `PROG`, `SEM`, `KOLEJ`, `ROOM`, `PWORD`) VALUES
(2013000001, 'Muhamad Haizad Bin Mohd Anuar', 950101010101, 'CS110', '5', 'JATSA', 'TS C-145', '12345'),
(2014000001, 'Muhamad Zulfikri Bin Md Zin', 960101010101, 'IM110', '2', 'JASBE', 'SB-019-B', '12345'),
(2015000001, 'Mohamad Khilmie Bin Kasbolah', 970101010101, 'CS143', '3', 'JASRI', 'SR C-337', '12345'),
(2016000001, 'Afif Salman Bin Ahmad Adzlan', 960101010101, 'AC110', '3', 'JASBE', 'SB-031-A', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD PRIMARY KEY (`NO_ID`);

--
-- Indexes for table `ATTENDANCE`
--
ALTER TABLE `ATTENDANCE`
  ADD PRIMARY KEY (`ATT_ID`),
  ADD KEY `ATT_STUD` (`ATT_STUD`),
  ADD KEY `ATT_EVN` (`ATT_EVN`);

--
-- Indexes for table `EVENT`
--
ALTER TABLE `EVENT`
  ADD PRIMARY KEY (`EVN_ID`),
  ADD KEY `EVN_RB` (`EVN_RB`),
  ADD KEY `EVN_ORG` (`EVN_ORG`);

--
-- Indexes for table `KOLEJ`
--
ALTER TABLE `KOLEJ`
  ADD PRIMARY KEY (`ID_KOLEJ`);

--
-- Indexes for table `ORGANIZER`
--
ALTER TABLE `ORGANIZER`
  ADD PRIMARY KEY (`ORG_ID`);

--
-- Indexes for table `PROG`
--
ALTER TABLE `PROG`
  ADD PRIMARY KEY (`ID_PROG`);

--
-- Indexes for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD PRIMARY KEY (`STUD_ID`),
  ADD KEY `PROG` (`PROG`),
  ADD KEY `KOLEJ` (`KOLEJ`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ATTENDANCE`
--
ALTER TABLE `ATTENDANCE`
  MODIFY `ATT_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ATTENDANCE`
--
ALTER TABLE `ATTENDANCE`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`ATT_STUD`) REFERENCES `STUDENT` (`STUD_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`ATT_EVN`) REFERENCES `EVENT` (`EVN_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `EVENT`
--
ALTER TABLE `EVENT`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`EVN_ORG`) REFERENCES `ORGANIZER` (`ORG_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`EVN_RB`) REFERENCES `ADMIN` (`NO_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`PROG`) REFERENCES `PROG` (`ID_PROG`) ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`KOLEJ`) REFERENCES `KOLEJ` (`ID_KOLEJ`) ON UPDATE CASCADE;
