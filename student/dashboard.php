<?php
session_start();
include "../db.php";

// التحقق من أن المستخدم طالب
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['id'];

// بيانات الطالب من جدول users
$stmt = $conn->prepare("SELECT name, level FROM users WHERE id = ?");
if (!$stmt) die("خطأ: " . $conn->error);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// المواد المسجلة للطالب من جدول subjects
$sql = "SELECT 
    st.name AS student_name,
    s.name AS subject_name,
    d.name AS department_name
FROM users st
JOIN departments d ON st.department_id = d.id
JOIN subjects s ON s.department_id = d.id
WHERE st.id = ?;  -- ضع هنا معرف الطالب";
$stmt2 = $conn->prepare($sql);
if (!$stmt2) die("خطأ: " . $conn->error);
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$subjects = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>ملفي الدراسي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <!-- بيانات الطالب -->
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">👤 بيانات الطالب</h4>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> <?= htmlspecialchars($student['name']) ?></p>
                <p><strong>المستوى:</strong> <?= htmlspecialchars($student['level']) ?></p>
                <p><strong>القسم:</strong> 
                    <span class="badge bg-info text-dark">
                        <?= htmlspecialchars($subjects->num_rows > 0 ? $subjects->fetch_assoc()['department_name'] : '-') ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- المواد المسجلة -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">📚 المواد المسجّلة</h4>
            </div>
            <div class="card-body">
                <?php if ($subjects->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>اسم المادة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                // إعادة المؤشر لأننا سحبنا صف القسم أعلاه
                                $subjects->data_seek(0); 
                                while ($row = $subjects->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">لا توجد مواد مسجّلة حتى الآن.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>

</html>
