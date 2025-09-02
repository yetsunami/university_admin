<?php
session_start();
require "../db.php";

$message = "";

// جلب جميع الأقسام الموجودة مسبقًا
$departments = [];
$res = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
while ($row = $res->fetch_assoc()) {
    $departments[] = $row;
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $name = trim($_POST['name']);
    $dept = intval($_POST['dept']); // id القسم المختار

    if(!empty($name) && $dept){
        $stmt = $conn->prepare("INSERT INTO subjects(name, department_id) VALUES(?, ?)");
        $stmt->bind_param("si", $name, $dept);

        if ($stmt->execute()) {
            $message = "✅ تم إضافة المادة بنجاح!";
        } else {
            $message = "❌ خطأ: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "⚠️ الرجاء ملء جميع الحقول.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مادة جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">➕ إضافة مادة جديدة</h3>
        </div>
        <div class="card-body">
            <?php if($message): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المادة</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="أدخل اسم المادة" required>
                </div>

                <div class="mb-3">
                    <label for="dept" class="form-label">اختر القسم</label>
                    <select name="dept" id="dept" class="form-select" required>
                        <option value="">-- اختر القسم --</option>
                        <?php foreach($departments as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">💾 إضافة</button>
                <a href="manage_courses.php" class="btn btn-secondary">🔙 رجوع</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
