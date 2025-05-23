CREATE DATABASE apotek_manager2;
USE apotek_manager2;

CREATE TABLE kategori_obat (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE pemasok (
    id_pemasok INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telepon VARCHAR(20),
    alamat TEXT
);

ALTER TABLE pemasok CHANGE nama nama_pemasok VARCHAR(100) NOT NULL;

CREATE TABLE obat (
    id_obat INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    id_kategori INT,
    harga_beli DECIMAL(10,2) NOT NULL,
    harga_jual DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL,
    tanggal_kadaluarsa DATE,
    id_pemasok INT,
    gambar VARCHAR(100),
    FOREIGN KEY (id_kategori) REFERENCES kategori_obat(id_kategori) ON DELETE CASCADE,
    FOREIGN KEY (id_pemasok) REFERENCES pemasok(id_pemasok) ON DELETE CASCADE
);




CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telepon VARCHAR(12) NOT NULL UNIQUE,
    poin INT DEFAULT 0
);

CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE CASCADE
);

CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    id_obat INT,
    jumlah INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat) ON DELETE CASCADE
);

CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabel VARCHAR(50),
    id_record INT,
    aksi VARCHAR(50),
    keterangan TEXT,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS temp_keranjang;
CREATE TABLE temp_keranjang (
    id_obat INT,
    jumlah INT,
    harga_jual DECIMAL(10,2)
);

INSERT INTO kategori_obat (nama_kategori) VALUES
('Antibiotik'),
('Analgesik'),
('Vitamin'),
('Antiseptik');

INSERT INTO pemasok (nama_pemasok, telepon, alamat) VALUES
('PT Sehat Selalu', '081234567890', 'Jl. Kesehatan No.1'),
('CV Obat Mandiri', '082233445566', 'Jl. Farma No.21');


INSERT INTO obat (nama, id_kategori, harga_beli, harga_jual, stok, tanggal_kadaluarsa, id_pemasok) VALUES
('Amoxicillin', 1, 5000, 8000, 100, '2025-12-31', 1),
('Paracetamol', 2, 3000, 5000, 200, '2026-01-30', 2),
('Vitamin C', 3, 1500, 3000, 150, '2025-09-01', 1),
('Betadine', 4, 7000, 10000, 50, '2025-08-15', 2);

INSERT INTO pelanggan (nama, telepon) VALUES
('Andi', '081122334455'),
('Budi', '082233445566'),
('Citra', '083344556677');

INSERT INTO transaksi (id_pelanggan, total) VALUES (1, 16000);
SET @id_transaksi := LAST_INSERT_ID();

INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah, harga, subtotal) VALUES
(@id_transaksi, 1, 1, 8000, 8000),
(@id_transaksi, 2, 2, 4000, 8000);

INSERT INTO obat (nama, id_kategori, harga_beli, harga_jual, stok, tanggal_kadaluarsa, id_pemasok, gambar) VALUES
('obat_habis1', 1, 1000.00, 1500.00, 0, '2025-12-31', 1, 'obat_habis1.jpg'),
('obat_habis2', 1, 1100.00, 1600.00, 0, '2025-11-30', 1, 'obat_habis2.jpg'),
('obat_habis3', 2, 1200.00, 1700.00, 0, '2025-10-31', 2, 'obat_habis3.jpg'),
('obat_habis4', 2, 1300.00, 1800.00, 0, '2025-09-30', 2, 'obat_habis4.jpg'),
('obat_habis5', 3, 1400.00, 1900.00, 0, '2025-08-31', 2, 'obat_habis5.jpg');

-- VIEW
CREATE VIEW view_obat_stok AS
SELECT 
    o.id_obat,
    o.nama AS nama_obat,
    k.nama_kategori,
    o.gambar,
    o.stok
FROM 
    obat o
JOIN 
    kategori_obat k ON o.id_kategori = k.id_kategori
WHERE
    o.stok = 0;

CREATE VIEW view_penjualan_per_obat AS
SELECT 
    o.id_obat,
    o.nama AS nama_obat,
    SUM(d.jumlah) AS total_terjual,
    SUM(d.subtotal) AS total_pendapatan
FROM detail_transaksi d
JOIN obat o ON d.id_obat = o.id_obat
GROUP BY o.id_obat, o.nama;


CREATE VIEW view_penjualan_bulanan AS
SELECT 
    DATE_FORMAT(tanggal, '%Y-%m') AS bulan,
    COUNT(*) AS jumlah_transaksi,
    SUM(total) AS total_penjualan
FROM transaksi
GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
ORDER BY bulan DESC;

CREATE VIEW view_obat_terlaris AS
SELECT 
    o.id_obat,
    o.nama,
    SUM(dt.jumlah) AS total_terjual
FROM obat o
JOIN detail_transaksi dt ON o.id_obat = dt.id_obat
GROUP BY o.id_obat, o.nama
ORDER BY total_terjual DESC;

CREATE VIEW view_pelanggan_poin_dan_transaksi AS
SELECT 
    p.id_pelanggan,
    p.nama AS nama_pelanggan,
    p.telepon,
    p.poin,
    COUNT(t.id_transaksi) AS total_transaksi
FROM pelanggan p
LEFT JOIN transaksi t ON p.id_pelanggan = t.id_pelanggan
GROUP BY p.id_pelanggan, p.nama, p.telepon, p.poin;


CREATE VIEW view_transaksi_detail AS
SELECT 
    t.id_transaksi,
    t.tanggal,
    pl.nama AS pelanggan,
    ob.nama AS nama_obat,
    dt.jumlah,
    dt.harga,
    dt.subtotal
FROM transaksi t
JOIN pelanggan pl ON t.id_pelanggan = pl.id_pelanggan
JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
JOIN obat ob ON dt.id_obat = ob.id_obat;

-- PROCEDURE
DELIMITER //
CREATE PROCEDURE tampil_obat_stok_rendah(IN batas_stok INT)
BEGIN
DECLARE selesai INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE id_obatx INT;
DECLARE namax VARCHAR(100);
DECLARE stokx INT;

DROP TEMPORARY TABLE IF EXISTS temp_stok_rendah;
CREATE TEMPORARY TABLE temp_stok_rendah (id INT, nama VARCHAR(100));

SELECT MAX(id_obat) INTO selesai FROM obat;

WHILE i <= selesai DO
    SELECT id_obat, nama, stok INTO id_obatx, namax, stokx
    FROM obat WHERE id_obat = i;

    IF stokx IS NOT NULL AND stokx < batas_stok THEN
        INSERT INTO temp_stok_rendah(id, nama) VALUES (id_obatx, namax);
    END IF;

    SET i = i + 1;
END WHILE;

SELECT * FROM temp_stok_rendah;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE tampil_obat_by_kategori(IN p_id_kategori INT)
BEGIN
DECLARE i INT DEFAULT 1;
DECLARE max_id INT;
DECLARE nama_obat VARCHAR(100);
DECLARE kategori_id INT;
DECLARE id_obatx INT;
DROP TEMPORARY TABLE IF EXISTS temp_obat_kategori;
CREATE TEMPORARY TABLE temp_obat_kategori (id INT, nama VARCHAR(100));

SELECT MAX(id_obat) INTO max_id FROM obat;

WHILE i <= max_id DO
    SELECT id_obat, nama, id_kategori INTO id_obatx, nama_obat, kategori_id
    FROM obat WHERE id_obat = i;

    IF kategori_id = p_id_kategori THEN
        INSERT INTO temp_obat_kategori(id, nama) VALUES (id_obatx, nama_obat);
    END IF;

    SET i = i + 1;
END WHILE;

SELECT * FROM temp_obat_kategori;
END //
DELIMITER ;
CALL tampil_obat_by_kategori(2);

DELIMITER //
CREATE PROCEDURE tampil_obat_mau_kadaluarsa(IN p_hari INT)
BEGIN
DECLARE i INT DEFAULT 1;
DECLARE max_id INT;
DECLARE nama_obat VARCHAR(100);
DECLARE tgl_exp DATE;
DECLARE id_obatx INT;
DROP TEMPORARY TABLE IF EXISTS temp_kadaluarsa;
CREATE TEMPORARY TABLE temp_kadaluarsa (id INT, nama VARCHAR(100), tanggal_kadaluarsa DATE);

SELECT MAX(id_obat) INTO max_id FROM obat;

WHILE i <= max_id DO
    SELECT id_obat, nama, tanggal_kadaluarsa INTO id_obatx, nama_obat, tgl_exp
    FROM obat WHERE id_obat = i;

    IF tgl_exp IS NOT NULL AND tgl_exp <= DATE_ADD(CURDATE(), INTERVAL p_hari DAY) THEN
        INSERT INTO temp_kadaluarsa(id, nama, tanggal_kadaluarsa) VALUES (id_obatx, nama_obat, tgl_exp);
    END IF;

    SET i = i + 1;
END WHILE;

SELECT * FROM temp_kadaluarsa;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE total_poin_pelanggan()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE total_poin INT DEFAULT 0;
    DECLARE max_id INT;
    DECLARE poin_val INT;

    SELECT MAX(id_pelanggan) INTO max_id FROM pelanggan;

    WHILE i <= max_id DO
        SELECT poin INTO poin_val FROM pelanggan WHERE id_pelanggan = i;
        SET total_poin = total_poin + IFNULL(poin_val, 0);
        SET i = i + 1;
    END WHILE;

    SELECT total_poin AS total_poin_pelanggan;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE proses_login_user(
    IN p_username VARCHAR(100),
    IN p_input VARCHAR(100)
)
BEGIN
    DECLARE v_id_pelanggan INT;
    DECLARE v_nama_db VARCHAR(100);
    DECLARE v_telepon_db VARCHAR(100);
	
    IF p_username = 'adminme' AND p_input = 'adminpassword' THEN
        SELECT 'admin' AS ROLE, NULL AS id_pelanggan;
    ELSE
        SELECT id_pelanggan, nama, telepon INTO v_id_pelanggan, v_nama_db, v_telepon_db
        FROM pelanggan
        WHERE telepon = p_input
        LIMIT 1;

        IF v_id_pelanggan IS NULL THEN
            INSERT INTO pelanggan (nama, telepon, poin)
            VALUES (p_username, p_input, 0);
            
            SELECT 'pelanggan_baru' AS ROLE, LAST_INSERT_ID() AS id_pelanggan;

        ELSE
            IF v_nama_db = p_username THEN
                SELECT 'pelanggan' AS ROLE, v_id_pelanggan AS id_pelanggan;
            ELSE
                SELECT 'gagal' AS ROLE, NULL AS id_pelanggan;
            END IF;
        END IF;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE lakukan_pembelian_keranjang(
    IN p_telepon VARCHAR(20)
)
BEGIN
    DECLARE v_id_pelanggan INT;
    DECLARE v_id_transaksi INT;
    DECLARE v_total DECIMAL(10,2) DEFAULT 0;
    DECLARE v_poin INT;

    SELECT id_pelanggan INTO v_id_pelanggan
    FROM pelanggan
    WHERE telepon = p_telepon;

    SELECT SUM(o.harga_jual * k.jumlah) INTO v_total
    FROM temp_keranjang k
    JOIN obat o ON k.id_obat = o.id_obat;

    INSERT INTO transaksi (id_pelanggan, total)
    VALUES (v_id_pelanggan, v_total);

    SET v_id_transaksi = LAST_INSERT_ID();

    INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah, harga, subtotal)
    SELECT 
        v_id_transaksi,
        k.id_obat,
        k.jumlah,
        o.harga_jual,
        o.harga_jual * k.jumlah
    FROM temp_keranjang k
    JOIN obat o ON k.id_obat = o.id_obat;

    SET v_poin = FLOOR(v_total / 5000) * 10;

    UPDATE pelanggan
    SET poin = poin + v_poin
    WHERE id_pelanggan = v_id_pelanggan;

    SELECT 'Pembelian berhasil' AS pesan, v_id_transaksi AS nomor_transaksi, v_total AS total, v_poin AS poin_didapat;
END //
DELIMITER ;
DELIMITER //

CREATE PROCEDURE lakukan_pembelian_keranjang(
    IN p_telepon VARCHAR(20)
)
BEGIN
    DECLARE v_id_pelanggan INT;
    DECLARE v_id_transaksi INT;
    DECLARE v_total DECIMAL(10,2) DEFAULT 0;
    DECLARE v_poin INT;

    -- Ambil id_pelanggan
    SELECT id_pelanggan INTO v_id_pelanggan
    FROM pelanggan
    WHERE telepon = p_telepon;

    -- Hitung total dari temp_keranjang
    SELECT SUM(harga * jumlah) INTO v_total
    FROM temp_keranjang;

    -- Buat transaksi utama
    INSERT INTO transaksi (id_pelanggan, total)
    VALUES (v_id_pelanggan, v_total);

    SET v_id_transaksi = LAST_INSERT_ID();

    -- Masukkan detail transaksi dari temp_keranjang
    INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah, harga, subtotal)
    SELECT 
        v_id_transaksi,
        id_obat,
        jumlah,
        harga,
        jumlah * harga
    FROM temp_keranjang;

    -- Hitung poin
    SET v_poin = FLOOR(v_total / 5000) * 10;

    -- Update poin pelanggan
    UPDATE pelanggan
    SET poin = poin + v_poin
    WHERE id_pelanggan = v_id_pelanggan;

    -- Return hasil
    SELECT 'Pembelian berhasil' AS pesan, v_id_transaksi AS nomor_transaksi, v_total AS total, v_poin AS poin_didapat;
END //

DELIMITER ;

-- TRIGGERS
DELIMITER //
CREATE TRIGGER after_insert_transaksi_audit
AFTER INSERT ON transaksi
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (tabel, id_record, aksi, keterangan)
    VALUES (
        'transaksi',
        NEW.id_transaksi,
        'INSERT',
        CONCAT('Transaksi baru sebesar Rp ', NEW.total)
    );
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_update_pelanggan_poin
AFTER UPDATE ON pelanggan
FOR EACH ROW
BEGIN
    IF NEW.poin != OLD.poin THEN
        INSERT INTO audit_log (tabel, id_record, aksi, keterangan)
        VALUES (
            'pelanggan', 
            NEW.id_pelanggan, 
            'UPDATE', 
            CONCAT('Poin pelanggan berubah dari ', OLD.poin, ' menjadi ', NEW.poin)
        );
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER before_delete_obat
BEFORE DELETE ON obat
FOR EACH ROW
BEGIN
    IF OLD.stok > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Tidak bisa menghapus obat yang masih ada stoknya';
    END IF;
END //
DELIMITER ;

DROP TRIGGER after_insert_detail_transaksi_update_stok;

DELIMITER //
CREATE TRIGGER after_insert_detail_transaksi_update_stok
AFTER INSERT ON detail_transaksi
FOR EACH ROW
BEGIN
    UPDATE obat
    SET stok = stok - NEW.jumlah
    WHERE id_obat = NEW.id_obat;
END //
DELIMITER ;
