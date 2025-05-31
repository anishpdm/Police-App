<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['police_id'])) {
    header("Location: ../police_login.php");
    exit();
}

$officer_id = $_SESSION['police_id'];

// Fetch assigned cases
$case_query = "
    SELECT c.id, c.case_title, c.case_description, c.status, c.created_at 
    FROM cases c
    JOIN case_officers co ON c.id = co.case_id
    WHERE co.officer_id = ?
    ORDER BY c.created_at DESC
";
$case_stmt = $conn->prepare($case_query);
$case_stmt->bind_param("i", $officer_id);
$case_stmt->execute();
$cases_result = $case_stmt->get_result();

// Fetch leave requests
$leave_query = "
    SELECT id, start_date, end_date, reason, status, created_at 
    FROM leave_requests 
    WHERE officer_id = ?
    ORDER BY created_at DESC
";
$leave_stmt = $conn->prepare($leave_query);
$leave_stmt->bind_param("i", $officer_id);
$leave_stmt->execute();
$leave_result = $leave_stmt->get_result();

// Fetch duty types
$duty_query = "
SELECT ad.`id`,ad.`officer_id`, d.DutyType, `duty_date`, `created_at` FROM `assigned_duties` ad join Duties d on d.Id=ad.duty_id where ad.officer_id=$officer_id and `duty_date`>=now() ORDER BY `duty_date` ASC";
$duty_result = $conn->query($duty_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Police Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <?php include 'navbar.php'; ?>
    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-bold text-indigo-700 text-center mb-6">Welcome, Officer</h1>

        <!-- Assigned Cases -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">📂 Assigned Cases</h2>
            <table class="w-full text-sm border">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cases_result->num_rows > 0): ?>
                        <?php while ($row = $cases_result->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($row['case_title']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['case_description']) ?></td>
                                <td class="px-4 py-2"><?= $row['status'] ?></td>
                                <td class="px-4 py-2"><?= $row['created_at'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No cases assigned.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Leave Requests -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">📝 Leave Requests</h2>
            <table class="w-full text-sm border">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Start Date</th>
                        <th class="px-4 py-2">End Date</th>
                        <th class="px-4 py-2">Reason</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Requested On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leave_result->num_rows > 0): ?>
                        <?php while ($row = $leave_result->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= $row['start_date'] ?></td>
                                <td class="px-4 py-2"><?= $row['end_date'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['reason']) ?></td>
                                <td class="px-4 py-2"><?= $row['status'] ?></td>
                                <td class="px-4 py-2"><?= $row['created_at'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No leave requests submitted.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Duty Types -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold text-indigo-600 mb-4">🚓 Upcoming Duties</h2>
            <table class="w-full text-sm border">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                    <th class="px-4 py-2">Date </th>

                        <th class="px-4 py-2">Duty Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($duty_result->num_rows > 0): ?>
                        <?php while ($row = $duty_result->fetch_assoc()): ?>
                            <tr class="border-t">
                            <td class="px-4 py-2"><?= $row['duty_date'] ?></td>

                                <td class="px-4 py-2"><?= htmlspecialchars($row['DutyType']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="px-4 py-2 text-center text-gray-500">No duty types available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
