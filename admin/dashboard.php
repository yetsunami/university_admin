<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id'])) {
    header("Location:../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>لوحة تحكم المدير</title>
  <style>
      body {
          background-color: #f5f6fa;
      }
      .card {
          border-radius: 15px;
          transition: transform 0.3s, box-shadow 0.3s;
      }
      .card:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      }
      .card i {
          color: #0d6efd;
      }
      .navbar .btn-danger {
          border-radius: 50px;
      }
      .card-title {
          margin-top: 10px;
          font-weight: bold;
      }
      .card-text {
          color: #6c757d;
      }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">لوحة تحكم المدير</a>
    <div class="ms-auto d-flex align-items-center text-white">
        <span class="me-3">مرحبا، <?= htmlspecialchars($_SESSION['name']) ?></span>
        <a href="../logout.php" class="btn btn-danger btn-sm">تسجيل الخروج</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-4">

        <!-- إدارة المستخدمين -->
        <div class="col-md-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-users fa-4x mb-3"></i>
                    <h5 class="card-title">إدارة المستخدمين</h5>
                    <p class="card-text">إضافة وحذف وتعديل المستخدمين</p>
                    <a href="manage_users.php" class="btn btn-primary mt-auto">فتح</a>
                </div>
            </div>
        </div>

        <!-- إدارة الأقسام -->
        <div class="col-md-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-building fa-4x mb-3"></i>
                    <h5 class="card-title">إدارة الأقسام</h5>
                    <p class="card-text">إضافة وحذف وتعديل الأقسام</p>
                    <a href="manage_depts.php" class="btn btn-success mt-auto">فتح</a>
                </div>
            </div>
        </div>

        <!-- إدارة الكورسات -->
        <div class="col-md-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-book-open fa-4x mb-3"></i>
                    <h5 class="card-title">إدارة الكورسات</h5>
                    <p class="card-text">عرض وإدارة المناهج</p>
                    <a href="manage_courses.php" class="btn btn-warning mt-auto text-white">فتح</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
