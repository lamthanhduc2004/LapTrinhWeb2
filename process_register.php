<?php
// Kết nối database từ file riêng biệt
require_once 'connection.php';

// Biến lưu trữ thông báo
$message = '';
$messageType = '';

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ho = isset($_POST['ho']) ? $conn->real_escape_string(trim($_POST['ho'])) : '';
    $ten = isset($_POST['ten']) ? $conn->real_escape_string(trim($_POST['ten'])) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : '';
    $matkhau = isset($_POST['matkhau']) ? $_POST['matkhau'] : '';
    $ngaysinh = isset($_POST['ngaysinh']) && !empty($_POST['ngaysinh']) ? $conn->real_escape_string($_POST['ngaysinh']) : NULL;
    $gioitinh = isset($_POST['gioitinh']) ? $conn->real_escape_string($_POST['gioitinh']) : NULL;
    $thanhpho = isset($_POST['thanhpho']) ? $conn->real_escape_string($_POST['thanhpho']) : '';
    
    // Xử lý sở thích (checkboxes)
    $sothich = [];
    if (isset($_POST['choi-game'])) $sothich[] = 'Chơi game';
    if (isset($_POST['chup-anh'])) $sothich[] = 'Chụp ảnh';
    if (isset($_POST['xem-youtube'])) $sothich[] = 'Xem YouTube';
    if (isset($_POST['luot-web'])) $sothich[] = 'Lướt web';
    if (isset($_POST['mua-sam'])) $sothich[] = 'Mua sắm';
    $sothich_str = !empty($sothich) ? implode(', ', $sothich) : '';
    
    $mota = isset($_POST['mota']) ? $conn->real_escape_string(trim($_POST['mota'])) : '';
    
    // Kiểm tra dữ liệu
    $errors = [];
    
    // Kiểm tra họ tên
    if (empty($ho)) {
        $errors[] = "Họ không được để trống";
    }
    
    if (empty($ten)) {
        $errors[] = "Tên không được để trống";
    }
    
    // Kiểm tra email
    if (empty($email)) {
        $errors[] = "Email không được để trống";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check_email = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            $errors[] = "Email này đã được đăng ký. Vui lòng sử dụng email khác.";
        }
    }
    
    // Kiểm tra mật khẩu
    if (empty($matkhau)) {
        $errors[] = "Mật khẩu không được để trống";
    } elseif (strlen($matkhau) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
    }
    
    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($matkhau, PASSWORD_DEFAULT);
        
        // Chuẩn bị câu lệnh SQL
        $sql = "INSERT INTO users (ho, ten, email, matkhau, ngaysinh, gioitinh, thanhpho, sothich, mota) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Sử dụng prepared statement để tăng bảo mật
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $ho, $ten, $email, $hashed_password, $ngaysinh, $gioitinh, $thanhpho, $sothich_str, $mota);
        
        if ($stmt->execute()) {
            // Đăng ký thành công
            $message = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
            $messageType = "success";
            
            // Chuyển hướng đến trang đăng nhập sau 3 giây
            header("refresh:3;url=login.html");
        } else {
            // Lỗi khi thêm vào database
            $message = "Lỗi: " . $stmt->error;
            $messageType = "danger";
        }
        
        // Đóng statement
        $stmt->close();
    } else {
        // Có lỗi, hiển thị thông báo
        $message = "<ul><li>" . implode("</li><li>", $errors) . "</li></ul>";
        $messageType = "danger";
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết Quả Đăng Ký</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .result-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .message {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="result-container">
            <div class="logo-container mb-4">
                <a href="index.html">
                    <img src="imgs/logo.jpg" alt="Thank.vn Logo" style="max-width: 150px;">
                </a>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> message">
                    <?php if ($messageType == "success"): ?>
                        <i class="fas fa-check-circle me-2"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-circle me-2"></i>
                    <?php endif; ?>
                    <?php echo $message; ?>
                </div>
                
                <?php if ($messageType == "success"): ?>
                    <p>Bạn sẽ được chuyển hướng đến trang đăng nhập sau 3 giây...</p>
                <?php else: ?>
                    <a href="register.html" class="btn btn-primary">Quay lại đăng ký</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 