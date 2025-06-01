<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db_connect.php';

// Handle approval or rejection of leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['leave_id'])) {
  $leave_id = intval($_POST['leave_id']);
  $action = $_POST['action']; // Approved or Rejected

  $checkStatusQuery = "SELECT status, start_date, end_date,officer_id FROM leave_requests WHERE id = ?";
  $stmt = $conn->prepare($checkStatusQuery);
  $stmt->bind_param("i", $leave_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $leaveData = $result->fetch_assoc();

  if ($leaveData && $leaveData['status'] === 'pending') {
      if (in_array($action, ['Approved', 'Rejected'])) {
          // Update leave request status
          $updateQuery = "UPDATE leave_requests SET status = ? WHERE id = ?";
          $updateStmt = $conn->prepare($updateQuery);
          $updateStmt->bind_param("si", $action, $leave_id);
          $updateStmt->execute();
          $updateStmt->close();

          

          // Delete duty schedule only if approved
          if ($action === 'Approved') {
              $startDate = $leaveData['start_date'];
              $endDate = $leaveData['end_date'];
              $officer_id_NEW = $leaveData['officer_id'];



            // Fetch the duties to be deleted (preview)
$previewQuery = "SELECT * FROM assigned_duties WHERE `officer_id` = ? AND duty_date BETWEEN ? AND ?";
$previewStmt = $conn->prepare($previewQuery);
$previewStmt->bind_param("iss", $officer_id_NEW, $startDate, $endDate);
$previewStmt->execute();
$previewResult = $previewStmt->get_result();

if ($previewResult->num_rows > 0) {
    echo "<br> <br> <br> <div style='background:#fef9c3; padding:10px; margin:10px; border:1px solid #facc15'>";
    echo "<strong>The following duty records will be deleted due to approved leave:</strong><br><ul>";
    while ($row = $previewResult->fetch_assoc()) {
        echo "<li>Duty ID: {$row['id']}, Officer ID: {$row['officer_id']}, Date: {$row['duty_date']}</li>";
    }
    echo "</ul></div>";
} else {
    echo "<div style='color:gray; padding:10px;'>No duty assignments found for the selected leave period.</div>";
}
$previewStmt->close();

// Then delete
$deleteQuery = "DELETE FROM `assigned_duties`
WHERE `officer_id` = ? 
  AND `duty_date` BETWEEN ? AND ?
 ";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("iss", $officer_id_NEW , $startDate, $endDate);
$deleteStmt->execute();
$deleteStmt->close();


          }
      }
  }

  $stmt->close();
}


// Fetch all leave requests
$query = "SELECT lr.id, po.name, po.`rank`, lr.start_date, lr.end_date, lr.reason, lr.status, lr.created_at 
          FROM leave_requests lr 
          JOIN police_officers po ON lr.officer_id = po.id 
          ORDER BY lr.created_at DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Leave Requests</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>

<br><br>

  <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-6">
    <h1 class="text-4xl font-bold text-blue-700 text-center mb-6">Leave Requests - Admin Panel</h1>

    <?php if ($result->num_rows > 0): ?>
      <table class="w-full table-auto border-collapse">
        <thead>
          <tr class="bg-blue-700 text-white">
            <th class="px-4 py-2">Officer</th>
            <th class="px-4 py-2">Rank</th>
            <th class="px-4 py-2">Start Date</th>
            <th class="px-4 py-2">End Date</th>
            <th class="px-4 py-2">Reason</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-b text-center">
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['rank']); ?></td>
              <td class="px-4 py-2"><?php echo $row['start_date']; ?></td>
              <td class="px-4 py-2"><?php echo $row['end_date']; ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['reason']); ?></td>
              <td class="px-4 py-2 font-semibold <?php echo $row['status'] == 'approved' ? 'text-green-600' : ($row['status'] == 'rejected' ? 'text-red-600' : 'text-yellow-600'); ?>">
                <?php echo $row['status']; ?>
              </td>
              <td class="px-4 py-2">
                <?php if ($row['status'] === 'pending'): ?>
                  <form method="POST" action="" class="inline-block">
                    <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="action" value="Approved" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mr-1">Approve</button>
                    <button type="submit" name="action" value="Rejected" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Reject</button>
                  </form>
                <?php else: ?>
                  <span class="text-gray-500 italic">No action</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-center text-gray-600">No leave requests found.</p>
    <?php endif; ?>

    
  </div>
</body>
</html>

<?php $conn->close(); ?>
