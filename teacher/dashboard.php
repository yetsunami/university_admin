<?php
session_start();
include "../db.php"; // ملف الاتصال بقاعدة البيانات

// التحقق من الصلاحية
if ($_SESSION['role'] != 'teacher') {
    echo "ليس لديك صلاحية الوصول لهذه الصفحة.";
    exit;
}

// معالجة الفورم لإضافة مادة جديدة
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_name'])) {
    $subject_name = trim($_POST['subject_name']);
    $department_id = intval($_POST['department_id']);

    if ($subject_name && $department_id) {
        $stmt = $conn->prepare("INSERT INTO subjects (name, department_id) VALUES (?, ?)");
        $stmt->bind_param("si", $subject_name, $department_id);
        if ($stmt->execute()) {
            $message = "✅ تم إضافة المادة بنجاح!";
        } else {
            $message = "❌ حدث خطأ أثناء الإضافة: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "⚠️ الرجاء ملء جميع الحقول.";
    }
}

// جلب جميع الأقسام
$departments = [];
$dept_res = $conn->query("SELECT * FROM departments");
while ($d = $dept_res->fetch_assoc()) {
    $departments[] = ['id' => $d['id'], 'name' => $d['name']];
}

// الحصول على القسم المحدد من GET
$selected_dept = isset($_GET['dept']) ? intval($_GET['dept']) : 0;

// جلب المواد حسب القسم المحدد أو كل المواد
$subjects_by_dept = [];
foreach ($departments as $dept) {
    if ($selected_dept && $dept['id'] != $selected_dept) continue;

    $dept_id = $dept['id'];
    $subjects_res = $conn->query("SELECT id, name FROM subjects WHERE department_id = $dept_id");
    $subjects = [];
    while ($s = $subjects_res->fetch_assoc()) {
        $subjects[] = ['id' => $s['id'], 'name' => $s['name']];
    }
    $subjects_by_dept[] = ['id' => $dept_id, 'name' => $dept['name'], 'subjects' => $subjects];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجهة الأستاذ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        .pointer { cursor: pointer; }
        .toggle-icon { transition: transform 0.3s ease; }
        .rotate { transform: rotate(45deg); }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <!-- بطاقة إضافة المادة الجديدة (Collapse) -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white pointer d-flex justify-content-between align-items-center" 
             data-bs-toggle="collapse" data-bs-target="#addSubjectForm" aria-expanded="false">
            <span>إضافة مادة جديدة</span>
            <span id="toggleIcon" class="toggle-icon">➕</span>
        </div>
        <div class="collapse" id="addSubjectForm">
            <div class="card-body">
                <?php if($message): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">اسم المادة:</label>
                        <input type="text" name="subject_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القسم:</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">اختر القسم</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">💾 إضافة المادة</button>
                </form>
            </div>
        </div>
    </div>

    <!-- اختيار القسم لعرض المواد -->
    <div class="mb-4">
        <form method="get" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0">عرض مواد قسم:</label>
            <select name="dept" class="form-select w-auto">
                <option value="0">كل الأقسام</option>
                <?php foreach($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= ($selected_dept == $dept['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary">عرض</button>
        </form>
    </div>

    <!-- عرض المواد حسب القسم -->
    <h3 class="mb-3">📚 المواد</h3>
    <div class="row">
        <?php foreach ($subjects_by_dept as $dept): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                        <?= htmlspecialchars($dept['name']) ?>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($dept['subjects'])): ?>
                            <?php foreach($dept['subjects'] as $sub): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($sub['name']) ?>
                                    <span>
                                        <a href="edit_subject.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-warning me-1">تعديل</a>
                                        <a href="delete_subject.php?id=<?= $sub['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟');">حذف</a>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">لا توجد مواد</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // تغيير أيقونة البطاقة عند الفتح/الإغلاق
    const addSubjectForm = document.getElementById('addSubjectForm');
    const toggleIcon = document.getElementById('toggleIcon');

    addSubjectForm.addEventListener('shown.bs.collapse', () => { toggleIcon.textContent = '➖'; });
    addSubjectForm.addEventListener('hidden.bs.collapse', () => { toggleIcon.textContent = '➕'; });
</script>

</body>
</html>
