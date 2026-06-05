CREATE USER IF NOT EXISTS 'adm_backup'@'localhost' IDENTIFIED BY 'admin123';
GRANT SELECT, SHOW VIEW, TRIGGER, LOCK TABLES, EVENT ON simak_terpadu.* TO 'adm_backup'@'localhost';
FLUSH PRIVILEGES;
