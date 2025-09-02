<?php
require '../db.php';

$id = intval($_GET['id']); // حماية ضد SQL Injection

// جلب بيانات المستخدم الحالي
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $password = $_POST['password'];
    $phone    = $_POST['phone'];
    $role     = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, password=?, phone=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $password, $phone, $role, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
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
  <title>تعديل مستخدم</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-warning text-dark">
      <h3 class="mb-0">✏ تعديل بيانات المستخدم</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">الاسم</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">كلمة المرور</label>
          <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">الهاتف</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">الدور</label>
          <select name="role" class="form-select" required>
            <option value="admin"   <?= $user['role']=='admin' ? 'selected' : '' ?>>ادمن</option>
            <option value="teacher" <?= $user['role']=='teacher' ? 'selected' : '' ?>>مدرس</option>
            <option value="student" <?= $user['role']=='student' ? 'selected' : '' ?>>طالب</option>
            <option value="user" <?= $user['role']=='user' ? 'selected' : '' ?>>مستخدم عادي</option>
          </select>
        </div>
        <button type="submit" class="btn btn-warning">💾 تحديث</button>
        <a href="index.php" class="btn btn-secondary">🔙 رجوع</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
