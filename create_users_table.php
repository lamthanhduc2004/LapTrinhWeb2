<?php
// Kết nối database từ file riêng biệt
require_once 'connection.php';

// SQL để tạo bảng users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    ho VARCHAR(50) NOT NULL,
    ten VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    matkhau VARCHAR(255) NOT NULL,
    ngaysinh DATE NULL,
    gioitinh VARCHAR(10) NULL,
    thanhpho VARCHAR(50) NULL,
    sothich TEXT NULL,
    mota TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Thực thi câu lệnh SQL
if ($conn->query($sql) === TRUE) {
    echo "<h2>Trạng thái bảng users</h2>";
    echo "<p style='color: green;'>✓ Bảng users đã được tạo hoặc đã tồn tại!</p>";
    
    // Kiểm tra bảng rỗng
    $check_empty = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $check_empty->fetch_assoc();
    if ($row['count'] == 0) {
        echo "<p>Bảng hiện đang trống. Bạn có thể đăng ký người dùng mới từ trang <a href='register.html'>đăng ký</a>.</p>";
    } else {
        echo "<p>Bảng hiện có " . $row['count'] . " người dùng đã đăng ký.</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Lỗi khi tạo bảng: " . $conn->error . "</p>";
}

// Đóng kết nối
$conn->close();

echo "<p><a href='test_connection.php'>Kiểm tra cấu trúc bảng</a></p>";
echo "<p><a href='register.html'>Quay lại trang đăng ký</a></p>";
?> 