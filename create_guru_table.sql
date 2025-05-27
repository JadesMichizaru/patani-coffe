-- Buat tabel guru jika belum ada
CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(50) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    avatar VARCHAR(255),
    no VARCHAR(50),
    password VARCHAR(255) NOT NULL
);

-- Tambahkan index untuk kolom kode
ALTER TABLE guru ADD INDEX idx_kode (kode); 