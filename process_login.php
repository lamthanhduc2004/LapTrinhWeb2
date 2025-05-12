<?php
// Kết nối database
require_once 'connection.php';

// Bắt đầu session
session_start();

// Biến lưu trữ thông báo
$message = '';
$messageType = '';

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : '';
    $matkhau = isset($_POST['matkhau']) ? $_POST['matkhau'] : '';

    // Kiểm tra dữ liệu
    $errors = [];

    // Kiểm tra email
    if (empty($email)) {
        $errors[] = "Email không được để trống";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ";
    }

    // Kiểm tra mật khẩu
    if (empty($matkhau)) {
        $errors[] = "Mật khẩu không được để trống";
    }

    // Nếu không có lỗi, tiến hành kiểm tra đăng nhập
    if (empty($errors)) {
        // Truy vấn user từ database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if (password_verify($matkhau, $user['matkhau'])) {
                // Đăng nhập thành công, lưu email vào session
                $_SESSION['email'] = $email;
                $message = "Đăng nhập thành công! Bạn sẽ được chuyển hướng đến dashboard sau 3 giây...";
                $messageType = "success";

                // Chuyển hướng đến dashboard sau 3 giây
                header("refresh:3;url=dashboard.php");
            } else {
                $message = "Mật khẩu không đúng.";
                $messageType = "danger";
            }
        } else {
            $message = "Email không tồn tại.";
            $messageType = "danger";
        }

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
    <title>Kết Quả Đăng Nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/stylesRegLog.css">
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
                    <p>Bạn sẽ được chuyển hướng đến dashboard sau 3 giây...</p>
                <?php else: ?>
                    <a href="login.html" class="btn btn-primary">Quay lại đăng nhập</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>