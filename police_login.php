<?php
session_start();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $Inppassword = $_POST['password'];

    require 'db_connect.php';

    // Get id and password in the query
    $stmt = $conn->prepare("SELECT id, password FROM police_officers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($officer_id, $hashedPassword);
        $stmt->fetch();

        if (password_verify($Inppassword, $hashedPassword)) {
            // Store ID in session
            $_SESSION['officer_logged_in'] = true;
            $_SESSION['police_id'] = $officer_id;

            header("Location: police/police_dashboard.php");
            exit(); // 🚨 Don't forget to exit after redirection
        } else {
            $login_error = '<p class="text-red-600">❌ Invalid password.</p>';
        }
    } else {
        $login_error = '<p class="text-red-600">❌ Email not found.</p>';
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Police Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 via-teal-500 to-green-400 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md">
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Police Officer Login</h2>

    <?php if ($login_error) echo '<div class="mb-4 text-center">' . $login_error . '</div>'; ?>

    <form method="POST" class="space-y-5">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
      <button type="submit" class="w-full py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition">Login</button>


    </form>

          <!-- Improved Back to Home Button -->
  <div class="mt-6 text-center">
    <a href="http://localhost/police-duty-tool/"
      class="inline-block w-full py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
      ⬅ Back to Home
    </a>
  </div>
  </div>

</body>
</html>
