<?php
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $password = $_POST['password'];
    $phone    = $_POST['phone'];
    $level    = $_POST['level'];
    $role     = $_POST['role'];

    // مبدئياً نخزن كلمة المرور كما هي (نص عادي مثل جدولك الحالي)
    // لاحقاً تقدر تستخدم password_hash + password_verify
    $stmt = $conn->prepare("INSERT INTO users (name, password, phone, role,level) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $password, $phone, $role,$level);

    if ($stmt->execute()) {
        header("Location:dashboard.php");
        exit;
    } else {
        echo "خطأ: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة مستخدم</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-success text-white">
      <h3 class="mb-0">➕ إضافة مستخدم جديد</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">الاسم</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">كلمة المرور</label>
          <input type="text" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">الهاتف</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">المستوى</label>
          <input type="num" name="level" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">الدور</label>
          <select name="role" class="form-select" required>
            <option value="admin">ادمن</option>
            <option value="teacher">مدرس</option>
            <option value="student">طالب</option>
            <option value="guest">ضيف مستخدم عاديا</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success">💾 حفظ</button>
        <a href="dashboard.php" class="btn btn-secondary">🔙 رجوع</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
