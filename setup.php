<?php
$host = "localhost";
$user = "root";   // المستخدم الافتراضي في XAMPP
$pass = "";       // كلمة المرور (غالباً فارغة)
$db   = "ITCS3";

// الاتصال بالسيرفر بدون تحديد قاعدة بيانات
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// إنشاء قاعدة البيانات إذا لم تكن موجودة
$sql = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "✅ قاعدة البيانات $db تم إنشاؤها بنجاح<br>";
} else {
    die("❌ خطأ في إنشاء قاعدة البيانات: " . $conn->error);
}

// استخدام قاعدة البيانات
$conn->select_db($db);

// إنشاء جدول users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    level INT NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ جدول users تم إنشاؤه بنجاح<br>";
} else {
    die("❌ خطأ في إنشاء الجدول: " . $conn->error);
}

// إدخال بيانات أولية إذا كان الجدول فارغ
$sql = "SELECT COUNT(*) AS count FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO users (name, level, phone) VALUES
        ('أحمد محمد', 1, '777111222'),
        ('خالد علي', 2, '777333444'),
        ('سارة حسين', 3, '777555666')";
    if ($conn->query($sql) === TRUE) {
        echo "✅ تم إدخال بيانات أولية بنجاح<br>";
    } else {
        echo "❌ خطأ في إدخال البيانات: " . $conn->error;
    }
} else {
    echo "ℹ️ توجد بيانات مسبقًا في الجدول، لم تتم إضافة بيانات جديدة<br>";
}

$conn->close();
?>
