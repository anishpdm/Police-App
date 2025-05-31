<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$status_message = "";

// Handle form submission for assigning officers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['case_id'], $_POST['assigned_to'])) {
    $case_id = $_POST['case_id'];
    $assigned_officers = $_POST['assigned_to']; // Array of officer IDs
    $status = $_POST['status'];

    // Delete old assignments
    $conn->query("DELETE FROM case_officers WHERE case_id = $case_id");

    // Insert new officer assignments
    $stmt = $conn->prepare("INSERT INTO case_officers (case_id, officer_id) VALUES (?, ?)");
    foreach ($assigned_officers as $officer_id) {
        $stmt->bind_param("ii", $case_id, $officer_id);
        $stmt->execute();
    }
    $stmt->close();

    // Update status
    $stmt = $conn->prepare("UPDATE cases SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $case_id);
    $stmt->execute();
    $stmt->close();

    $status_message = "✅ Officers assigned successfully!";
}

// Fetch all officers
$officers_result = $conn->query("SELECT id, name FROM police_officers");
$officers = [];
while ($row = $officers_result->fetch_assoc()) {
    $officers[] = $row;
}

// Fetch all cases
$cases_result = $conn->query("SELECT id, case_title, case_description, status, created_at FROM cases");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Cases</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <?php include 'navbar.php'; ?>
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Assign Cases to Police Officers</h1>

        <?php if (!empty($status_message)): ?>
            <div class="bg-green-200 text-green-800 px-4 py-3 mb-6 rounded shadow"><?= $status_message ?></div>
        <?php endif; ?>

        <div class="bg-white rounded shadow p-4 overflow-auto">
            <table class="w-full border text-sm">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Case Title</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Assigned Officers</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Assign</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $cases_result->fetch_assoc()): ?>
                        <?php
                            $case_id = $row['id'];
                            $assigned_names = [];
                            $assigned_ids = [];

                            $res = $conn->query("SELECT o.id, o.name FROM case_officers co JOIN police_officers o ON co.officer_id = o.id WHERE co.case_id = $case_id");
                            while ($a = $res->fetch_assoc()) {
                                $assigned_names[] = $a['name'];
                                $assigned_ids[] = $a['id'];
                            }
                        ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['case_title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['case_description']) ?></td>
                            <td class="px-4 py-2"><?= implode(", ", $assigned_names) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="px-4 py-2">
                                <form action="assign_case.php" method="POST">
                                    <input type="hidden" name="case_id" value="<?= $case_id ?>">
                                    <select name="assigned_to[]" multiple size="4" class="p-2 border rounded w-full">
                                        <?php foreach ($officers as $officer): ?>
                                            <option value="<?= $officer['id'] ?>" <?= in_array($officer['id'], $assigned_ids) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($officer['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="status" class="p-2 border rounded mt-2 w-full">
                                        <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Closed" <?= $row['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                    </select>
                                    <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
