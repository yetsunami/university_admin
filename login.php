<?php
session_start();
require 'db.php';
$error='';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // البحث بالاسم (يمكن تغييره للهاتف لو أردت)
    $stmt = $conn->prepare("SELECT id, name, role, phone, password FROM users WHERE name = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if ($password === $user['password']) { // كلمات مرور غير مشفّرة (نص عادي)
            // حفظ بيانات الجلسة
            $_SESSION['id']   = (int)$user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // التوجيه حسب الدور
            if ($user['role'] === 'admin') {
                header("Location: index.php");
            }
             elseif($user['role'] === 'student') {
                header("Location: student/dashboard.php");
            }
            
            elseif($user['role'] === 'teacher') {
                header("Location: teacher/dashboard.php");
            }
            elseif($user['role'] === 'user') {
                header("Location:guest.php");
            }
        } else {
            $error = "❌ كلمة المرور غير صحيحة";
        }
    } else {
        $error = "❌ اسم المستخدم غير موجود";
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-center mb-3">تسجيل الدخول</h3>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">دخول</button>
    </form>
</div>

</body>
</html>
