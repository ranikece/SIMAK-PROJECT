USE simak_terpadu;
START TRANSACTION;
UPDATE layanan SET kapasitas_harian = kapasitas_harian WHERE id_layanan = 1;
DO SLEEP(10);
UPDATE layanan SET kapasitas_harian = kapasitas_harian WHERE id_layanan = 2;
COMMIT;
