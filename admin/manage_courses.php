<?php
session_start();
include "../db.php";

// التحقق من صلاحية المدير
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// جلب المواد + القسم التابع لها
$sql = "SELECT subjects.id, subjects.name AS subject_name, departments.name AS dept_name
        FROM subjects
        INNER JOIN departments ON subjects.department_id = departments.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المواد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📖 إدارة المواد</h4>
            <a href="add_subject.php" class="btn btn-success btn-sm">➕ إضافة مادة جديدة</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">اسم المادة</th>
                            <th scope="col">القسم التابع له</th>
                            <th scope="col" class="text-center">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['dept_name']) ?></span></td>
                                    <td class="text-center">
                                        <a href="edit_subject.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">✏️ تعديل</a>
                                        <a href="delete_subject.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟');">🗑 حذف</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">لا توجد مواد مضافة بعد</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
