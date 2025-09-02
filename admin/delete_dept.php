<?php
require '../db.php';

$id = intval($_GET['id']); // حماية ضد SQL Injection

$stmt = $conn->prepare("DELETE FROM departments WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_depts.php");
    exit;
} else {
    echo "خطأ: " . $conn->error;
}
