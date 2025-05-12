-- Tạo cơ sở dữ liệu sellphones
CREATE DATABASE IF NOT EXISTS sellphones;
USE sellphones;

-- Tạo bảng users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ho VARCHAR(50) NOT NULL,
  ten VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  matkhau VARCHAR(255) NOT NULL,
  ngaysinh DATE,
  gioitinh VARCHAR(10),
  thanhpho VARCHAR(50),
  sothich TEXT,
  mota TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu (tùy chọn)
-- INSERT INTO users (ho, ten, email, matkhau, ngaysinh, gioitinh, thanhpho, sothich, mota)
-- VALUES ('Nguyễn', 'Văn A', 'nguyenvana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1990-01-01', 'Nam', 'Hà Nội', 'Chơi game, Chụp ảnh', 'Người dùng thích công nghệ');

-- Tạo bảng products (nếu cần)
-- CREATE TABLE IF NOT EXISTS products (
--   id INT AUTO_INCREMENT PRIMARY KEY,
--   name VARCHAR(100) NOT NULL,
--   brand VARCHAR(50) NOT NULL,
--   price DECIMAL(10,2) NOT NULL,
--   discount_price DECIMAL(10,2),
--   description TEXT,
--   image VARCHAR(255),
--   category VARCHAR(50),
--   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 