<?php
// File kết nối CSDL riêng biệt

// Thông tin kết nối database
$servername = "localhost";
$username = "root";  
$password = "";     
$dbname = "sellphones";  

// Tạo kết nối
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt charset là utf8mb4 để hỗ trợ tiếng Việt
$conn->set_charset("utf8mb4");
?>