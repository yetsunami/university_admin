<?php
require '../db.php';

$id = intval($_GET['id']); // حماية ضد SQL Injection

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_users.php");
    exit;
} else {
    echo "خطأ: " . $conn->error;
}
