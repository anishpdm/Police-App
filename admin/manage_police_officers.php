<?php
session_start();
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$msg = '';

// Handle delete
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Use prepare instead of query for parameterized queries
    $delStmt = $conn->prepare("DELETE FROM police_officers WHERE id = ?");
    if ($delStmt) {
        $delStmt->bind_param("i", $id);

        if ($delStmt->execute()) {
            $msg = "✅ Officer deleted successfully.";
        } else {
            $msg = "❌ Execution failed: " . $delStmt->error;
        }

        $delStmt->close();
    } else {
        $msg = "❌ Prepare failed: " . $conn->error;
    }
}


// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $rank = $_POST['rank'];

    $updateStmt = $conn->prepare("UPDATE police_officers SET name = ?, email = ?, `rank` = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $name, $email, $rank, $id);
    if ($updateStmt->execute()) {
        $msg = "✅ Officer updated successfully.";
    }
}

// Handle password reset
if (isset($_POST['reset_pass_id'])) {
    $id = intval($_POST['reset_pass_id']);
    $newPassword = password_hash("officer123", PASSWORD_DEFAULT);
    $conn->query("UPDATE police_officers SET password = '$newPassword' WHERE id = $id");
    $msg = "✅ Password reset to 'officer123'.";
}

$result = $conn->query("SELECT * FROM police_officers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Police Officers</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-10">
<?php include 'navbar.php'; // Include the navbar here ?>
<br><br>

  <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-3xl font-bold text-indigo-700 mb-6">Manage Police Officers</h2>

    <?php if ($msg): ?>
      <p class="mb-4 text-green-600 font-medium"><?php echo $msg; ?></p>
    <?php endif; ?>

    <div class="overflow-x-auto">
      <table class="w-full border border-gray-300 rounded-lg text-sm">
        <thead class="bg-indigo-700 text-white">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Rank</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-50">
              <form method="POST">
                <td class="px-4 py-2"><?php echo $row['id']; ?></td>
                <td class="px-4 py-2"><input name="name" value="<?php echo $row['name']; ?>" class="border px-2 py-1 rounded w-full"></td>
                <td class="px-4 py-2"><input name="email" value="<?php echo $row['email']; ?>" class="border px-2 py-1 rounded w-full"></td>
                <td class="px-4 py-2"><input name="rank" value="<?php echo $row['rank']; ?>" class="border px-2 py-1 rounded w-full"></td>
                <td class="px-4 py-2 flex gap-2">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Update</button>
                  <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Delete</a>
                </td>
              </form>
              <td class="px-4 py-2">
                <form method="POST">
                  <input type="hidden" name="reset_pass_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Reset Password</button>
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
