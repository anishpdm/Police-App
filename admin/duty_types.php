<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$duty_type = $_POST['duty_type'] ?? '';
$count = $_POST['count'] ?? '';
$is_fixed = isset($_POST['is_fixed']) ? (int)$_POST['is_fixed'] : 0;
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['edit_id'])) {
        $stmt = $conn->prepare("UPDATE Duties SET DutyType = ?, Count = ?, IsFixed = ? WHERE Id = ?");
        $stmt->bind_param("siii", $duty_type, $count, $is_fixed, $_POST['edit_id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO Duties (DutyType, Count, IsFixed) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $duty_type, $count, $is_fixed);
    }
    $stmt->execute();
    header("Location: duty_types.php");
    exit();
}

if ($action === 'delete' && !empty($id)) {
    $conn->query("DELETE FROM Duties WHERE Id = $id");
    header("Location: duty_types.php");
    exit();
}

$edit_data = [];
if ($action === 'edit' && !empty($id)) {
    $edit_result = $conn->query("SELECT * FROM Duties WHERE Id = $id");
    $edit_data = $edit_result->fetch_assoc();
}

$duties = $conn->query("SELECT * FROM Duties");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Duties</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-10">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>
    <div class="max-w-xl mx-auto bg-white p-6 shadow-lg rounded-lg">
        <h2 class="text-xl font-bold mb-4"><?= $edit_data ? 'Edit' : 'Add' ?> Duty</h2>
        <form method="POST" class="space-y-4">
            <input type="text" name="duty_type" value="<?= $edit_data['DutyType'] ?? '' ?>" placeholder="Duty Name" required class="w-full border p-2 rounded">
            <input type="number" name="count" value="<?= $edit_data['Count'] ?? '' ?>" placeholder="Required Count" required class="w-full border p-2 rounded">

            <select name="is_fixed" required class="w-full border p-2 rounded">
                <option value="1" <?= isset($edit_data['IsFixed']) && $edit_data['IsFixed'] == 1 ? 'selected' : '' ?>>Yes</option>
                <option value="0" <?= isset($edit_data['IsFixed']) && $edit_data['IsFixed'] == 0 ? 'selected' : '' ?>>No</option>
            </select>

            <?php if ($edit_data): ?>
                <input type="hidden" name="edit_id" value="<?= $edit_data['Id'] ?>">
            <?php endif; ?>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded"><?= $edit_data ? 'Update' : 'Add' ?></button>
        </form>
    </div>

    <div class="max-w-xl mx-auto mt-6 bg-white p-6 shadow-lg rounded-lg">
        <h2 class="text-xl font-bold mb-4">Duties List</h2>
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Required Count</th>
                    <th class="p-2 border">Is Fixed</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($duty = $duties->fetch_assoc()): ?>
                <tr>
                    <td class="p-2 border"><?= htmlspecialchars($duty['DutyType']) ?></td>
                    <td class="p-2 border"><?= htmlspecialchars($duty['Count']) ?></td>
                    <td class="p-2 border"><?= $duty['IsFixed'] ? 'Yes' : 'No' ?></td>
                    <td class="p-2 border">
                        <a href="?action=edit&id=<?= $duty['Id'] ?>" class="text-blue-600">Edit</a> |
                        <a href="?action=delete&id=<?= $duty['Id'] ?>" class="text-red-600" onclick="return confirm('Delete this duty?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
