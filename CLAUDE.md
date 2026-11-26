# CLAUDE.md

Project memory for Claude Code. Keep it short and current. Never commit credentials, server addresses, account names or
database names; the hosting details live in the owner's private notes.

## What this is

**PINCard** (2017, KIK PERSADA): a student-activity merit system for the residential colleges of UiTM Cawangan Johor,
Kampus Segamat. Native PHP pages on Bootstrap Admin Theme 3 (Bootstrap 3), MySQL, UI in Malay. **Archived project** in
the owner's project archive (https://app.dimensikini.xyz/PINCard/). The repo is **public**.

- History: written in 2017 with mysql_*, moved to mysqli on app.dimensikini.xyz (Aug 2020 / Feb 2021, PHP 7.4). Ported to
  PHP 8.5 on 2026-11-26 (ClickUp `86d4e29q0`, under `86d4e29pu`). The folder alone runs on PHP 8.5 through its own
  `.htaccess` (`SetHandler application/x-lsphp85`).

## Data

Tables (`sql/pincard_db_structure.sql`): `ADMIN` (`NO_ID`, `PWORD` hash, `TYPE` REDD/BLCK), `STUDENT` (`STUD_ID`,
`IC_NUM` bigint — also the student's password), `EVENT` (`EVN_ID` = yymmdd + 2-digit running number, `EVN_TYPE`
COLL/HEPA/FACT/UNIV/MAND, `EVN_HOUR` up to 42, `EVN_RB` registering admin), `ATTENDANCE` (`ATT_CAT` 1-3, `ATT_CRDT`
credit stored at registration), lookups `PROG`, `KOLEJ`, `ORGANIZER`. Foreign keys (ON UPDATE CASCADE, delete
restricted): a student, event, admin or lookup still in use cannot be deleted. The host MySQL is case-sensitive: table
names are upper case. Student rows hold names and IC numbers: never copy real data into the repo or an issue.

Merit rules (`dictionary.php`): `$ATT_CREDIT[level][category]` via `att_credit()`, `$HOUR_LIM`; reports total credit,
hours and credit × hours. The credit is always computed on the server, never taken from a form.

Roles (session `pc_priv`): BLUE student (own details and report only), REDD events/attendance/students/import, BLCK
also accounts, lookups and the semester resets (`res_*.php`). `nav.php` builds the menu per role.

## Code conventions (2026 port — keep them)

- `connectDB.php` reads `config.php` (git-ignored; copy `config.example.php`), sets `mysqli_report(MYSQLI_REPORT_OFF)`,
  a non-strict `sql_mode` and the `Asia/Kuala_Lumpur` time zone, and defines `db_query($sql, [$params])` (prepared
  statements), `db_row()`, `db_map()`, `e()` (HTML escape), `js()` (JS literal), `alert_page()` (the pop-up + redirect
  the pages use), `request_text()` / `request_id()` / `request_ids()`, `read_csv_upload()` and
  `require_login(...$types)`. `dictionary.php` holds the lookups plus `read_event_form()` and `read_student()`.
  Never interpolate request data into SQL.
- Session keys are prefixed `pc_` (other apps on the host share the session cookie); the bulk forms keep their checked
  rows in the session (`pc_ram_*`, `pc_ims_*`) between the check and the register step.
- Admin passwords: `password_hash()` / `password_verify()`; a plain-text one left in the DB is hashed on first login.
- Pages start with `<?php ob_start(); ?>` so `session_start()` and `header()` work after HTML.
- CSV uploads are read from the temp file and never stored (no upload folder).
- `.htaccess` denies `config*.php`, `connectDB.php`, `dictionary.php`, `nav.php`, `sql/`, `tools/`, `*.sql`, `*.md`,
  `*.docx`, `LICENSE*`, logs. `vendors/` is third-party JS (its PHP demo scripts were removed).
- No AI attribution in commits or PRs.

## Local test

PHP 8.5 with mysqli and a local MariaDB (the owner uses EnvKit). Import a dump, run `sql/2026-11-hash-passwords.sql`
and `php tools/hash-passwords.php`, add an admin with `tools/add-admin.php`, write `config.php`, then
`php -d display_errors=1 -d error_reporting=-1 -S 127.0.0.1:8133 -t .`. The built-in server ignores `.htaccess`, so
check the deny rules on the real host.

## Deploy

No git on the host. Upload `master` with `git archive master | ssh … 'tar -x -C …/public_html/PINCard'`; `config.php`
(mode 600) stays on the server and is never overwritten. Schema changes go in `sql/` and are run by hand. Every change
on the server needs the owner's OK: show the exact commands first. Smoke test: pages answer 200 on PHP 8.5, and
`config.php`, `connectDB.php`, `sql/`, `tools/`, `error_log` answer 403.
