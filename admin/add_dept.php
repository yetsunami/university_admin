<?php
session_start();
require '../db.php';

$message = ''; // رسالة لإظهار نجاح أو خطأ

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        // استخدام prepared statement لتجنب SQL Injection
        $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $message = "✅ تم إضافة القسم بنجاح!";
        } else {
            $message = "❌ خطأ: " . $conn->error;
        }
    } else {
        $message = "⚠️ الرجاء إدخال اسم القسم.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة قسم جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">➕ إضافة قسم جديد</h3>
        </div>
        <div class="card-body">
            <?php if($message): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">اسم القسم</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="أدخل اسم القسم" required>
                </div>
                <button type="submit" class="btn btn-success">💾 إضافة</button>
                <a href="manage_depts.php" class="btn btn-secondary">🔙 رجوع</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
