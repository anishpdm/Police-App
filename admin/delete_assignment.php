<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM assigned_duties WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: view_duties.php");
exit();
