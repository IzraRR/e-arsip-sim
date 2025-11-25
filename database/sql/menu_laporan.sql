-- =====================================================
-- SQL UNTUK MENAMBAHKAN MENU LAPORAN (Opsional)
-- =====================================================
-- Jika aplikasi Anda menggunakan dynamic menu dari database,
-- jalankan query ini. Jika tidak, abaikan file ini.
-- =====================================================

-- Contoh struktur tabel menus (sesuaikan dengan struktur Anda)
-- CREATE TABLE IF NOT EXISTS menus (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     icon VARCHAR(255),
--     route VARCHAR(255),
--     order_index INT DEFAULT 0,
--     parent_id BIGINT UNSIGNED NULL,
--     created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- );

-- Tambahkan menu Laporan
INSERT INTO menus (name, icon, route, order_index, parent_id) 
VALUES ('Laporan', '📊', 'laporan.index', 90, NULL);

-- Atau jika menu menggunakan permissions/roles
-- INSERT INTO menu_role (menu_id, role_id) 
-- SELECT m.id, r.id 
-- FROM menus m, roles r 
-- WHERE m.route = 'laporan.index' 
-- AND r.name IN ('admin', 'pimpinan', 'staff');

-- =====================================================
-- QUERY UNTUK CEK DATA LAPORAN
-- =====================================================

-- Cek total surat masuk
SELECT COUNT(*) as total_surat_masuk FROM surat_masuk;

-- Cek total surat keluar
SELECT COUNT(*) as total_surat_keluar FROM surat_keluar;

-- Cek total disposisi
SELECT COUNT(*) as total_disposisi FROM disposisi;

-- Cek total arsip
SELECT COUNT(*) as total_arsip FROM arsip;

-- Surat masuk per bulan (tahun berjalan)
SELECT 
    MONTH(tanggal_terima) as bulan,
    COUNT(*) as total
FROM surat_masuk
WHERE YEAR(tanggal_terima) = YEAR(CURDATE())
GROUP BY MONTH(tanggal_terima)
ORDER BY bulan;

-- Surat masuk berdasarkan prioritas
SELECT 
    prioritas,
    COUNT(*) as total
FROM surat_masuk
GROUP BY prioritas;

-- Disposisi berdasarkan status
SELECT 
    status,
    COUNT(*) as total
FROM disposisi
GROUP BY status;
