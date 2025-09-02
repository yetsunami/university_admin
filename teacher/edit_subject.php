<?php
session_start();
require '../db.php';

$id = intval($_GET['id']);

// جلب بيانات المادة مع اسم القسم
$stmt = $conn->prepare("SELECT subjects.id, subjects.name AS subject_name, departments.id AS dept_id, departments.name AS dept_name
                        FROM subjects 
                        JOIN departments ON departments.id = subjects.department_id
                        WHERE subjects.id = ?");
if (!$stmt) {
    die("خطأ في الاستعلام: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $dept = intval($_POST['dept']);

    $stmt = $conn->prepare("UPDATE subjects SET name=?, department_id=? WHERE id=?");
    if (!$stmt) {
        die("خطأ في الاستعلام: " . $conn->error);
    }
    $stmt->bind_param("sii", $name, $dept, $id);

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
  <title>تعديل المادة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-warning text-dark">
      <h3 class="mb-0">✏ تعديل بيانات المادة</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">اسم المادة</label>
          <input type="text" name="name" value="<?= htmlspecialchars($result['subject_name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">القسم</label>
          <select name="dept" class="form-control" required>
            <?php
            $departments = $conn->query("SELECT * FROM departments");
            while ($d = $departments->fetch_assoc()) {
                $selected = ($d['id'] == $result['dept_id']) ? "selected" : "";
                echo "<option value='{$d['id']}' $selected>{$d['name']}</option>";
            }
            ?>
          </select>
        </div>
        <button type="submit" class="btn btn-warning">💾 تحديث</button>
        <a href="manage_courses.php" class="btn btn-secondary">🔙 رجوع</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
