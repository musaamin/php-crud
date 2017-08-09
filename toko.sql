--
-- Database: `toko`
--

CREATE DATABASE IF NOT EXISTS toko;
USE toko;
-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE barang (
  kd_barang varchar(4) NOT NULL,
  nama_barang varchar(50) NOT NULL,
  harga int(11) NOT NULL,
  stok int(11) NOT NULL,
  PRIMARY KEY (kd_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO barang (kd_barang, nama_barang, harga, stok) VALUES
('A001', 'Flashdisk 32GB', 150000, 20),
('A002', 'SD Card 32GB', 250000, 50),
('B001', 'Laptop Intel Core i7', 10000000, 5),
('B002', 'Laptop Intel Core i5', 7500000, 10),
('C001', 'Baterai Laptop', 750000, 10),
('C002', 'Adaptor Laptop', 200000, 20),
('C003', 'RAM 4GB Corsair', 355000, 30);
