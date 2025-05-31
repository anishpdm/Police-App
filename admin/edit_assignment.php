<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: assigned_duties.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $duty_date = $_POST['duty_date'];
    $officer_id = $_POST['officer_id'];
    $duty_id = $_POST['duty_id'];

    $stmt = $conn->prepare("UPDATE assigned_duties SET duty_date=?, officer_id=?, duty_id=? WHERE id=?");
    $stmt->bind_param("siii", $duty_date, $officer_id, $duty_id, $id);
    $stmt->execute();
    header("Location: view_duties.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM assigned_duties WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

$officers = $conn->query("SELECT id, name FROM police_officers");
$duties = $conn->query("SELECT id, name FROM duty_types");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Assignment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Assignment</h2>
    <form method="POST">
        <label class="block mb-2">Duty Date</label>
        <input type="date" name="duty_date" value="<?= $assignment['duty_date'] ?>" class="p-2 border w-full mb-4 rounded" required>

        <label class="block mb-2">Officer</label>
        <select name="officer_id" class="p-2 border w-full mb-4 rounded">
            <?php while ($row = $officers->fetch_assoc()) { ?>
                <option value="<?= $row['id'] ?>" <?= $assignment['officer_id'] == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
            <?php } ?>
        </select>

        <label class="block mb-2">Duty Type</label>
        <select name="duty_id" class="p-2 border w-full mb-4 rounded">
            <?php while ($row = $duties->fetch_assoc()) { ?>
                <option value="<?= $row['id'] ?>" <?= $assignment['duty_id'] == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
            <?php } ?>
        </select>

        <div class="flex justify-between">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="assigned_duties.php" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
