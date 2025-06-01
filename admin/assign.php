<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$run_schedule = isset($_GET['schedule']) && $_GET['schedule'] == '1';

if ($run_schedule) {
    // Get officers excluding inspectors and SIs
    $officers = $conn->query("SELECT id, name FROM police_officers WHERE `rank` != 'inspector' AND `rank` != 'law/order si' AND `rank` != 'crime si'")->fetch_all(MYSQLI_ASSOC);

    // Get duty types that are not fixed
    $duties = $conn->query("SELECT Id, DutyType, Count FROM Duties WHERE IsFixed = 0")->fetch_all(MYSQLI_ASSOC);

    // Get all approved leaves
    $leave_result = $conn->query("SELECT officer_id, start_date, end_date FROM leave_requests WHERE status = 'approved'");
    $leave_map = [];
    while ($row = $leave_result->fetch_assoc()) {
        $start = strtotime($row['start_date']);
        $end = strtotime($row['end_date']);
        for ($i = $start; $i <= $end; $i += 86400) {
            $leave_map[$row['officer_id']][] = date('Y-m-d', $i);
        }
    }

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Delete previous future auto-assignments
        $conn->query("DELETE FROM assigned_duties WHERE duty_date >= CURDATE()");

        // Auto-assign duties for next 28 days
        $today = strtotime(date('Y-m-d'));
        $assignments = [];
        $assigned_map = []; // [$date][$officer_id] = true

        for ($d = 0; $d < 28; $d++) {
            $date = date('Y-m-d', $today + ($d * 86400));
            shuffle($duties); // Randomize duty order

            foreach ($duties as $duty) {
                $count = $duty['Count'];
                $duty_id = $duty['Id'];
                $assigned = 0;

                shuffle($officers); // Randomize officer order

                foreach ($officers as $officer) {
                    $officer_id = $officer['id'];

                    // Skip officer if on leave or already assigned that day
                    if (
                        (isset($leave_map[$officer_id]) && in_array($date, $leave_map[$officer_id])) ||
                        isset($assigned_map[$date][$officer_id])
                    ) {
                        continue;
                    }

                    // Assign
                    $assignments[] = [
                        "officer_id" => $officer_id,
                        "duty_id" => $duty_id,
                        "duty_date" => $date
                    ];
                    $assigned_map[$date][$officer_id] = true;

                    $assigned++;
                    if ($assigned >= $count) break;
                }
            }
        }

        // Insert into DB
        foreach ($assignments as $a) {
            $stmt = $conn->prepare("INSERT INTO assigned_duties (officer_id, duty_id, duty_date) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $a['officer_id'], $a['duty_id'], $a['duty_date']);
            $stmt->execute();
            $stmt->close();
        }

        // Commit the transaction
        $conn->commit();
        $status_message = "✅ Auto-duty scheduled for next 28 days!";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        $status_message = "❌ Error occurred while scheduling duties.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Assigned Duties</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br> <br>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">Assigned Duties (Next 28 Days)</h1>

    <?php if (!empty($status_message)) : ?>
      <div class="bg-green-200 text-green-800 px-4 py-3 mb-6 rounded shadow"><?= $status_message ?></div>
    <?php endif; ?>

    <div class="mb-4 text-center">
      <a href="?schedule=1" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">📅 Schedule Duties Now</a>
    </div>

    <div class="overflow-auto bg-white p-4 rounded shadow">
      <table class="w-full text-sm text-left border">
        <thead class="bg-indigo-600 text-white">
          <tr>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Officer Name</th>
            <th class="px-4 py-2">Duty</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT a.duty_date, p.name, d.DutyType FROM assigned_duties a JOIN police_officers p ON a.officer_id = p.id JOIN Duties d ON a.duty_id = d.Id ORDER BY a.duty_date, p.name");
          while ($row = $result->fetch_assoc()) {
              echo "<tr class='border-t'>
                      <td class='px-4 py-2'>" . $row['duty_date'] . "</td>
                      <td class='px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>
                      <td class='px-4 py-2'>" . htmlspecialchars($row['DutyType']) . "</td>
                    </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
