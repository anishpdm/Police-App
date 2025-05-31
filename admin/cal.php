<?php
session_start();
include '../db_connect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$filter_date = $_GET['date'] ?? '';
$filter_officer = $_GET['officer'] ?? '';
$filter_type = $_GET['type'] ?? '';

// SQL to fetch duties and join with police officers and duty types
$sql = "SELECT ad.id, ad.duty_date AS date, po.name AS officer_name, dt.name AS duty_name
        FROM assigned_duties ad
        JOIN police_officers po ON ad.officer_id = po.id
        JOIN duty_types dt ON ad.duty_id = dt.id
        WHERE 1=1";

if ($filter_date) {
    $sql .= " AND ad.duty_date = '" . $conn->real_escape_string($filter_date) . "'";
}
if ($filter_officer) {
    $sql .= " AND po.id = '" . $conn->real_escape_string($filter_officer) . "'";
}
if ($filter_type) {
    $sql .= " AND dt.id = '" . $conn->real_escape_string($filter_type) . "'";
}

$sql .= " ORDER BY ad.duty_date ASC";

$result = $conn->query($sql);

$officers = $conn->query("SELECT id, name FROM police_officers");
$types = $conn->query("SELECT id, name FROM duty_types");

// Group duties by date
$duties_by_date = [];
while ($row = $result->fetch_assoc()) {
    $duties_by_date[$row['date']][] = $row;
}

// Set current month and year for the calendar view
$year = date('Y');
$month = date('m');

// If a specific date is passed in, use that instead of the current date
if ($filter_date) {
    $year = date('Y', strtotime($filter_date));
    $month = date('m', strtotime($filter_date));
}

// Get the number of days in the current month
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Get the first day of the month
$first_day_of_month = date('w', strtotime("$year-$month-01"));

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assigned Duties - Calendar View</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Function to display the modal with duty details for the clicked date
    function showDutyDetails(date) {
        const dutyDetails = document.getElementById('duty-details');
        dutyDetails.style.display = 'block'; // Show the duty details modal
        dutyDetails.innerHTML = `<h2 class="text-xl font-bold mb-4">Duties for ${date}</h2><ul class="mt-2" id="duty-list"></ul>`;

        fetch(`get_duties.php?date=${date}`)
            .then(response => response.json())
            .then(data => {
                const dutyList = document.getElementById('duty-list');
                if (data.length > 0) {
                    data.forEach(duty => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${duty.duty_name} - ${duty.officer_name}`;
                        dutyList.appendChild(listItem);
                    });
                } else {
                    dutyList.innerHTML = '<li>No duties assigned for this date.</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching duty details:', error);
                const dutyList = document.getElementById('duty-list');
                dutyList.innerHTML = '<li>Error fetching duties.</li>';
            });
    }
  </script>
</head>
<body class="p-6 bg-gray-100">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br><br><br>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Assigned Duties - Calendar View</h1>

    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
      <input type="date" name="date" value="<?= htmlspecialchars($filter_date) ?>" class="p-2 border rounded">
      <select name="officer" class="p-2 border rounded">
        <option value="">All Officers</option>
        <?php while ($row = $officers->fetch_assoc()) { ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $filter_officer ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
      </select>
      <select name="type" class="p-2 border rounded">
        <option value="">All Duty Types</option>
        <?php while ($row = $types->fetch_assoc()) { ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $filter_type ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
      </select>
      <button class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <div class="overflow-x-auto bg-white p-4 rounded shadow">
      <div class="grid grid-cols-7 gap-4 text-center">
        <?php
        // Calendar headers (days of the week)
        $days_of_week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days_of_week as $day) {
            echo "<div class='font-bold'>$day</div>";
        }

        // Add empty cells before the first day of the month
        for ($i = 0; $i < $first_day_of_month; $i++) {
            echo "<div></div>";
        }

        // Display days of the month with duties
        for ($day = 1; $day <= $days_in_month; $day++) {
            $current_date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            echo "<div class='border p-2 cursor-pointer' onclick='showDutyDetails(\"$current_date\")'>";
            echo "<div class='font-bold'>$day</div>";

            if (isset($duties_by_date[$current_date])) {
                echo "<div class='text-sm'>Duties Assigned</div>";
            }

            echo "</div>";
        }
        ?>
      </div>
    </div>

    <!-- Modal for displaying duty details -->
    <div id="duty-details" class="mt-8 bg-white p-6 rounded shadow hidden">
      <!-- Duty details will be injected here -->
    </div>
  </div>
</body>
</html>
