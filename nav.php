					<li><a href="main.php"><i class="glyphicon glyphicon-home"></i> Laman Utama</a></li>
<?php
					if($_SESSION['pc_priv'] != "BLUE")
					{
?>
					<li class="submenu">
						 <a href="#">
							<i class="glyphicon glyphicon-calendar"></i> Program Pelajar
							<span class="caret pull-right"></span>
						 </a>
						 <!-- Sub menu -->
						 <ul>
							<li><a href="evn_reg.php">Daftar Program</a></li>
							<li><a href="evn_list.php">Senarai Program</a></li>
						</ul>
					</li>
					<li class="submenu">
						 <a href="#">
							<i class="glyphicon glyphicon-list"></i> Kehadiran
							<span class="caret pull-right"></span>
						 </a>
						 <!-- Sub menu -->
						 <ul>
							<li><a href="reg_attd_1.php">Daftar Kehadiran</a></li>
							<li><a href="list_attd_1.php">Senarai Kehadiran</a></li>
<?php
					}

					if($_SESSION['pc_priv'] != "BLUE")
					{
?>
						</ul>
					</li>
					<li class="submenu">
						 <a href="#">
							<i class="glyphicon glyphicon-user"></i> Pelajar 
							<span class="caret pull-right"></span>
						 </a>
						 <!-- Sub menu -->
						 <ul>
							<li><a href="reg_stud.php">Daftar Pelajar</a></li>
							<li><a href="imp_stud.php">Import Maklumat</a></li>
							<li><a href="list_stud.php">Senarai Pelajar</a></li>
						</ul>
					</li>
<?php
					}

					if($_SESSION['pc_priv'] == "BLCK")
					{
?>
					<li class="submenu">
						 <a href="#">
							<i class="glyphicon glyphicon-lock"></i> Akaun
							<span class="caret pull-right"></span>
						 </a>
						 <!-- Sub menu -->
						 <ul>
							<li><a href="reg_admin.php">Daftar Akaun</a></li>
							<li><a href="list_admin.php">Senarai Akaun</a></li>
						</ul>
					</li>
					<li class="submenu">
						 <a href="#">
							<i class="glyphicon glyphicon-wrench"></i> Tetapan
							<span class="caret pull-right"></span>
						 </a>
						 <!-- Sub menu -->
						 <ul>
							<li><a href="ad_prog.php">Tadbir Program Pengajian</a></li>
							<li><a href="ad_col.php">Tadbir Kolej</a></li>
							<li><a href="ad_org.php">Tadbir Penganjur</a></li>
						</ul>
					</li>
					<li class="submenu">
						<a href="#">
							<i class="glyphicon glyphicon-exclamation-sign"></i> Semester Baru
							<span class="caret pull-right"></span>
						</a>
						<!-- Sub menu -->
						<ul>
							<li><a href="res_attd.php">Reset Kehadiran</a></li>
						</ul>
						<ul>
							<li><a href="res_prog.php">Reset Program Pelajar</a></li>
						</ul>
						<ul>
							<li><a href="res_stud.php">Reset Pelajar</a></li>
						</ul>
					</li>
<?php
					}

					if($_SESSION['pc_priv'] == "BLUE")
					{
?>
					<li><a href="rpt_stud_view.php"><i class="glyphicon glyphicon-file"></i> Laporan Kehadiran</a></li>
<?php
					}
?>