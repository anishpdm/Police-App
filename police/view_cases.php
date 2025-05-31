<?php
session_start();
include '../db_connect.php';

// Check if the officer is logged in
if (!isset($_SESSION['police_id'])) {
    header("Location: police_login.php"); // Redirect to login if not logged in
    exit();
}

$officer_id = $_SESSION['police_id'];

$query = "
    SELECT c.id, c.case_title, c.case_description, c.status, c.created_at 
    FROM cases c
    JOIN case_officers co ON c.id = co.case_id
    WHERE co.officer_id = ?
    ORDER BY c.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Assigned Cases</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <?php include 'navbar.php'; ?>
    <br><br>
    <div class="max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-indigo-700 text-center">My Assigned Cases</h2>
        <div class="bg-white p-4 rounded shadow overflow-x-auto">
            <table class="w-full border text-sm">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['case_title']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['case_description']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="px-4 py-2"><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows === 0): ?>
                        <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No cases assigned yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
