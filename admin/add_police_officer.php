<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gl_number = trim($_POST['gl_number']); // Get the GL Number from the form
    $phone     = trim($_POST['phone']); // Get the phone number from the form
    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $rank      = trim($_POST['rank']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
    
    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM police_officers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        $error_msg = "❌ A police officer with this email already exists.";
    } else {
        // Include the phone number, GL number, and other details in the insert query

        $stmt = $conn->prepare("INSERT INTO police_officers (name, email, rank, password, gl_number, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $rank, $password, $gl_number, $phone);
    
        if ($stmt->execute()) {
            $success_msg = "✅ Police officer added successfully!";
        } else {
            $error_msg = "❌ Failed to add police officer.";
            echo $stmt->error;
        }
        $stmt->close();
    }
    
    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Police Officer</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-700 via-indigo-600 to-blue-500 min-h-screen flex items-center justify-center">

<?php include 'navbar.php'; // Include the navbar here ?>

  <div class="bg-white shadow-xl rounded-xl p-10 w-full max-w-xl mt-6">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Add Police Officer</h2>

    <?php if ($success_msg): ?>
      <p class="mb-4 text-green-600 text-center"><?php echo $success_msg; ?></p>
    <?php elseif ($error_msg): ?>
      <p class="mb-4 text-red-600 text-center"><?php echo $error_msg; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <input type="text" name="name" placeholder="Officer Name" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <input type="tel" name="phone" placeholder="Phone Number" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">

      <input type="text" name="rank" placeholder="Rank" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
      <input type="text" name="gl_number" placeholder="GL Number" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">

      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
     
      <button type="submit" class="w-full bg-indigo-700 text-white py-3 font-semibold rounded-lg hover:bg-indigo-800 transition">Add Officer</button>
    </form>
  </div>

</body>
</html>
