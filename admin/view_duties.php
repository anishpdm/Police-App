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

$sql .= " ORDER BY ad.duty_date ASC";  // Updated to use 'ad.duty_date'

$result = $conn->query($sql);

$officers = $conn->query("SELECT id, name FROM police_officers");
$types = $conn->query("SELECT id, name FROM duty_types");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assigned Duties</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>
<br><br>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Assigned Duties</h1>

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
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Officer</th>
            <th class="px-4 py-2">Duty</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?= htmlspecialchars($row['date']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['officer_name']) ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['duty_name']) ?></td>
            <td class="px-4 py-2">
              <a href="edit_assignment.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
              <a href="delete_assignment.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

   
  </div>
</body>
</html>
