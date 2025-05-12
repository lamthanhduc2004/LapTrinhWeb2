<?php
// Kết nối database
require_once 'connection.php';

// Bắt đầu session để lấy thông tin user đã đăng nhập
session_start();

// Kiểm tra nếu user đã đăng nhập, giả định email được lưu trong session
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

// Lấy email từ session
$email = $_SESSION['email'];

// Truy vấn thông tin user từ database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Nếu không tìm thấy user, chuyển hướng về trang đăng nhập
    header("Location: login.html");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SELLPHONESSS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column p-3">
                    <h4 class="text-center mb-4">Main Navigation</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#userInfo" data-bs-toggle="tab">
                                <i class="fas fa-user me-2"></i> Thông tin User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#appleProducts" data-bs-toggle="tab">
                                <i class="fab fa-apple me-2"></i> Sản phẩm Apple
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#samsungProducts" data-bs-toggle="tab">
                                <i class="fas fa-mobile-alt me-2"></i> Sản phẩm Samsung
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#orderHistory" data-bs-toggle="tab">
                                <i class="fas fa-history me-2"></i> Lịch sử đơn hàng
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <ul class="nav flex-column mt-auto">
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="index.html">
                                <i class="fas fa-home me-2"></i> Quay lại Trang Chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="login.html">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">Dashboard</h1>
                    <div>
                        <button class="btn btn-primary me-2"><i class="fas fa-share me-1"></i> Share</button>
                        <button class="btn btn-outline-secondary"><i class="fas fa-download me-1"></i> Export</button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card-stat bg-primary">
                            150
                            <small>Đơn hàng mới</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-stat bg-success">
                            53%
                            <small>Tỷ lệ hoàn thành</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-stat bg-warning">
                            44
                            <small>Người dùng mới</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-stat bg-danger">
                            65
                            <small>Sản phẩm đã bán</small>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- User Info Tab -->
                    <div class="tab-pane fade show active" id="userInfo">
                        <h3>Thông tin User</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['ho']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['ten']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" value="<?php echo htmlspecialchars($user['ngaysinh']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['gioitinh'] ?? 'Chưa xác định'); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thành phố</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['thanhpho']); ?>" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Sở thích</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['sothich']); ?>" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mô tả bản thân</label>
                                <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($user['mota']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Apple Products Tab -->
                    <div class="tab-pane fade" id="appleProducts">
                        <h3>Sản phẩm Apple</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Giảm Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>IPhone 14</td>
                                    <td>19.990.000đ</td>
                                    <td>22.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>IPhone 13</td>
                                    <td>16.990.000đ</td>
                                    <td>18.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>IPhone 12</td>
                                    <td>13.990.000đ</td>
                                    <td>15.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>IPhone 11</td>
                                    <td>10.990.000đ</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Samsung Products Tab -->
                    <div class="tab-pane fade" id="samsungProducts">
                        <h3>Sản phẩm Samsung</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Giảm Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Galaxy S23</td>
                                    <td>21.990.000đ</td>
                                    <td>24.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Galaxy S22</td>
                                    <td>17.990.000đ</td>
                                    <td>19.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Galaxy S21</td>
                                    <td>14.990.000đ</td>
                                    <td>16.990.000đ</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Galaxy A53</td>
                                    <td>9.490.000đ</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order History Tab -->
                    <div class="tab-pane fade" id="orderHistory">
                        <h3>Lịch sử đơn hàng</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ngày</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2025-04-25</td>
                                    <td>IPhone 14</td>
                                    <td>Đã đặt</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>2025-05-01</td>
                                    <td>Galaxy S23</td>
                                    <td>Chưa xác nhận</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>2025-05-10</td>
                                    <td>Galaxy A53</td>
                                    <td>Hoàn tất</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>