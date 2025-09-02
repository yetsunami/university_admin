<?php
session_start();
require 'db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// التحقق من أن المستخدم أدمن فقط
if ($_SESSION['role'] == 'admin') {
   header('location:admin/dashboard.php');
   exit;
}
else{
   echo "<div style='padding:20px; color:red; font-weight:bold;'>❌ غير مصرح لك بالدخول إلى لوحة التحكم.</div>";
    echo "<a href='logout.php'>تسجيل الخروج</a>";
    exit;
}

