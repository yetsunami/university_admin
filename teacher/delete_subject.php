<?php
session_start();
require "../db.php";

$id     = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM subjects where id =?");
$stmt->bind_param("i",$id);

if ($stmt->execute()){
    header("location:dashboard.php");
    exit;
}
else{
        echo "خطأ: " . $conn->error;
}

?>