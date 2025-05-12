<?php
// Kết nối database từ file riêng biệt
require_once 'connection.php';

echo "<h2>Kiểm tra kết nối database</h2>";

// Kiểm tra kết nối
if ($conn) {
    echo "<p style='color: green;'>✓ Kết nối đến database 'sellphones' thành công!</p>";
} else {
    echo "<p style='color: red;'>✗ Kết nối thất bại: " . mysqli_connect_error() . "</p>";
    exit();
}

// Kiểm tra bảng users
$check_table = $conn->query("SHOW TABLES LIKE 'users'");
if ($check_table->num_rows > 0) {
    echo "<p style='color: green;'>✓ Bảng 'users' đã tồn tại!</p>";
    
    // Kiểm tra cấu trúc bảng
    echo "<h3>Cấu trúc bảng users:</h3>";
    $result = $conn->query("DESCRIBE users");
    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Tên cột</th><th>Kiểu dữ liệu</th><th>NULL</th><th>Khóa</th><th>Mặc định</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Kiểm tra các cột cần thiết
    $required_columns = ['ho', 'ten', 'email', 'matkhau', 'ngaysinh', 'gioitinh', 'thanhpho', 'sothich', 'mota'];
    $missing_columns = [];
    
    while ($row = $result->fetch_assoc()) {
        if (in_array($row['Field'], $required_columns)) {
            $key = array_search($row['Field'], $required_columns);
            unset($required_columns[$key]);
        }
    }
    
    if (empty($required_columns)) {
        echo "<p style='color: green;'>✓ Tất cả các cột cần thiết đều tồn tại!</p>";
    } else {
        echo "<p style='color: red;'>✗ Thiếu các cột sau: " . implode(", ", $required_columns) . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Bảng 'users' chưa được tạo!</p>";
    echo "<p>Bạn cần tạo bảng 'users' với các cột sau:</p>";
    echo "<ul>";
    echo "<li>id (INT, PRIMARY KEY, AUTO_INCREMENT)</li>";
    echo "<li>ho (VARCHAR(50))</li>";
    echo "<li>ten (VARCHAR(50))</li>";
    echo "<li>email (VARCHAR(100))</li>";
    echo "<li>matkhau (VARCHAR(255))</li>";
    echo "<li>ngaysinh (DATE)</li>";
    echo "<li>gioitinh (VARCHAR(10))</li>";
    echo "<li>thanhpho (VARCHAR(50))</li>";
    echo "<li>sothich (TEXT)</li>";
    echo "<li>mota (TEXT)</li>";
    echo "</ul>";
}

// Đóng kết nối
$conn->close();
?> 