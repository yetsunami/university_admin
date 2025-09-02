<?php
session_start();
include "../db.php";

// التحقق من صلاحية المدير
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// جلب الأقسام
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأقسام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📚 إدارة الأقسام</h4>
            <a href="add_dept.php" class="btn btn-success btn-sm">➕ إضافة قسم جديد</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">اسم القسم</th>
                            <th scope="col" class="text-center">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="text-center">
                                        <a href="add_course.php?dept_id=<?= $row['id'] ?>" 
                                           class="btn btn-primary btn-sm me-1">➕ إضافة كورس</a>
                                        <a href="delete_dept.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('هل أنت متأكد من الحذف؟');">🗑 حذف</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">لا توجد أقسام مضافة بعد</td>
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
