
<?php
$host = "localhost"; 
$user = "root";     // اسم المستخدم الافتراضي في XAMPP
$pass = "";         // كلمة المرور (غالباً فارغة في XAMPP)
$db   = "itcs3";    // اسم قاعدة البيانات

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $pass, $db);

// فحص الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
} 