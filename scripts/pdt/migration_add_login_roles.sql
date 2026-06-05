USE simak_terpadu;

ALTER TABLE users
MODIFY role ENUM('user','resepsionis','admin') NOT NULL DEFAULT 'user';

INSERT INTO users (nama, username, password, role, status)
SELECT 'Pasien Demo', 'pasien', '$2y$12$wpa/FfSh.faFiRdD9SUFaOCCkQf/7WSMq0EENcG7.YkeJRVecru3e', 'user', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'pasien');

UPDATE users
SET password = '$2y$12$kMEJuP8iRKVjRzYY6hQzOOJQoXpJtjnT6wzdT4O9rrUW9bbO73CTi'
WHERE username = 'admin' AND password = 'admin123';

UPDATE users
SET password = '$2y$12$fO.JsdPwu6H4cGSNTOimwuaw0WFBhcm2i8ZBBcdbP1m3674Iel4Si'
WHERE username = 'resepsionis' AND password = 'resepsionis123';
