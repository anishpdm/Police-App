<?php
session_start();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<br>";

    $username = $_POST['username'];
    $Inppassword = $_POST['password'];

    require 'db_connect.php'; // ✅ This includes your DB connection

    $stmt = $conn->prepare("SELECT password, id FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword, $id);  // Added $id to fetch user ID
        $stmt->fetch();
     
        // Now, compare the entered password with the hashed password
        if (password_verify($Inppassword, $hashedPassword)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $id;  // Assign the fetched admin ID to the session
            header("Location: admin/add_police_officer.php");
            exit;
        } else {
            $login_error = '<p class="text-red-600">❌ Invalid password.</p>';
        }
    } else {
        $login_error = '<p class="text-red-600">❌ User not found.</p>';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-indigo-600 via-purple-500 to-pink-500 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Admin Login</h2>

    <?php if ($login_error) echo '<div class="mb-4 text-center">' . $login_error . '</div>'; ?>

    <form method="POST" class="space-y-5">
      <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <button type="submit" class="w-full py-3 bg-indigo-700 text-white font-semibold rounded-lg hover:bg-indigo-800 transition">Login</button>
      <br> <br>
    </form>

      <!-- Improved Back to Home Button -->
  <div class="mt-6 text-center">
    <a href="http://13.126.207.54/"
      class="inline-block w-full py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
      ⬅ Back to Home
    </a>
  </div>


  </div>

</body>
</html>
