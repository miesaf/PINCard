# PINCard

A student-activity merit system (2017) for the residential colleges of UiTM Cawangan Johor, Kampus Segamat, by the
KIK PERSADA group. Hostel staff register student events and the students who attend them (one by one or from a CSV
file); each attendance earns merit credit by event level and type of involvement, and students log in to see and print
their own attendance report. Native PHP pages, MySQL, Bootstrap 3 (Bootstrap Admin Theme 3), UI in Malay.

Now an archived project: https://app.dimensikini.xyz/PINCard/ (PHP 8.5 since November 2026).

## Accounts

- **Blue** (student): student number, with the IC number (digits only) as the password. Sees their details and report.
- **Red** (Penyelia Asrama): events, attendance, students, bulk import.
- **Black** (Penolong & Pengurus Asrama): everything Red does, plus admin accounts, programmes, colleges, organisers and
  the new-semester resets.

## Setup

1. PHP 8.1+ (built for 8.5) with mysqli and fileinfo, and MySQL/MariaDB.
2. Create a database and import `sql/pincard_db_structure.sql` (tables and the programme, college and organiser lists).
3. Copy `config.example.php` to `config.php` and fill in the database credentials.
4. Add the first Black account: `php tools/add-admin.php <staff number> BLCK "<name>"` (asks for the password).

Upgrading a database from before November 2026: run `sql/2026-11-hash-passwords.sql`, then
`php tools/hash-passwords.php` (admin passwords were stored in plain text).

Bulk files: attendance takes one student number per line; the student import takes
`student number, name, IC number, programme code, semester, college code, room` per line. Both are CSV, up to 500 KB.

The `.htaccess` keeps `config.php`, `connectDB.php`, `sql/`, `tools/` and the notes from being served (Apache /
LiteSpeed).

## License

The admin theme is MIT-licensed (see `LICENSE`, Vincent Gabriel); the libraries in `vendors/` keep their own licenses.
