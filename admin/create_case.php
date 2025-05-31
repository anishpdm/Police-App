<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$status_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['case_title'], $_POST['case_description'])) {
    $case_title = $_POST['case_title'];
    $case_description = $_POST['case_description'];
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO cases (case_title, case_description, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $case_title, $case_description, $status);
    $stmt->execute();
    $stmt->close();

    $status_message = "✅ New case added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Case</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <?php include 'navbar.php'; ?>
    <br><br><br><br>
    
    <div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-indigo-700">Create New Case</h2>

        <?php if (!empty($status_message)): ?>
            <div class="bg-green-200 text-green-800 px-4 py-3 mb-4 rounded"><?= $status_message ?></div>
        <?php endif; ?>

        <form action="create_case.php" method="POST">
            <label class="block mb-2 font-semibold">Case Title</label>
            <input type="text" name="case_title" required class="w-full p-2 border rounded mb-4">

            <label class="block mb-2 font-semibold">Description</label>
            <textarea name="case_description" required class="w-full p-2 border rounded mb-4" rows="4"></textarea>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</body>
</html>
