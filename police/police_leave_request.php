<?php
session_start();

// Check if officer is logged in
if (!isset($_SESSION['police_id'])) {
    header("Location: police_login.php");
    exit();
}

include '../db_connect.php';

$police_id = $_SESSION['police_id'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = trim($_POST['reason']);

    if (empty($start_date) || empty($end_date) || empty($reason)) {
        $message = "❌ All fields are required.";
    } else {
        $status = "Pending";
        $query = "INSERT INTO leave_requests (officer_id, start_date, end_date, reason, status, created_at) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issss", $police_id, $start_date, $end_date, $reason, $status);

        if ($stmt->execute()) {
            $message = "✅ Leave request submitted successfully.";
        } else {
            $message = "❌ Failed to submit leave request.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave Request</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>
  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Leave Request</h2>

    <?php if ($message): ?>
      <p class="text-center mb-4 font-semibold text-red-600"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-gray-700">Start Date:</label>
        <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-gray-700">End Date:</label>
        <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-gray-700">Reason:</label>
        <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg p-2" required></textarea>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700">
        Submit Request
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="police_dashboard.php" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
