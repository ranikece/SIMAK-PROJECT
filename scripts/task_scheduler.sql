USE simak_terpadu;
SET GLOBAL event_scheduler = ON;
DELIMITER //
CREATE EVENT IF NOT EXISTS evt_backup_log_harian
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    INSERT INTO backup_log (nama_file, lokasi_file, metode, status, keterangan)
    VALUES (CONCAT('simak_terpadu_auto_', DATE_FORMAT(NOW(), '%Y%m%d_%H%i%s'), '.sql'), 'storage/backups/otomatis', 'EVENT', 'BERHASIL', 'Log backup otomatis dari MySQL Event Scheduler');
END//
DELIMITER ;
