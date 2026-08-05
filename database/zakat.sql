-- =============================================
-- DATABASE ZAKAT MAAL
-- =============================================

CREATE DATABASE IF NOT EXISTS zakat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zakat;

-- ---------------------------------------------
-- Tabel users (admin)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Tabel muzakki (pembayar zakat)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS muzakki (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL DEFAULT 'Laki-laki',
    alamat TEXT,
    no_hp VARCHAR(20),
    jenis_harta VARCHAR(100) NOT NULL DEFAULT 'Campuran',
    total_harta DECIMAL(15,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Tabel transaksi_zakat (pembayaran zakat)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS transaksi_zakat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    muzakki_id INT NOT NULL,
    jumlah_zakat DECIMAL(15,2) NOT NULL DEFAULT 0,
    metode ENUM('Tunai','Transfer','Lainnya') NOT NULL DEFAULT 'Tunai',
    tanggal_pembayaran DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (muzakki_id) REFERENCES muzakki(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Data awal: admin default (admin / admin123)
-- ---------------------------------------------
INSERT INTO users (nama, username, password) VALUES
('Administrator', 'admin', '$2y$12$kfEekM64VocuK4cG8n1NmuBm3YR3CmGAzh3t.1eLBAQtcE0FC9zZW');