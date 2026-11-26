-- 2026-11 PHP 8.5 port: admin passwords are stored with password_hash(); widen PWORD for future algorithms.
-- Run once, then run tools/hash-passwords.php to hash the existing plain-text passwords.
ALTER TABLE ADMIN MODIFY PWORD varchar(255) NOT NULL;
