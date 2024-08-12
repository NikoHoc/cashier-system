<?php
require_once("./services/config.php");
session_start();

// if($_SESSION['is_login'] == false) {
//   header("location: login.php");
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/link.php"; ?>  
</head>

<body>
  <!-- Sidebar and Navbar -->
  <?php include "components/navbar.php"; ?>

  <!-- Main Content -->
  <main class="flex-grow-1 p-3" style="margin-left: 250px;">
    <div>
      <h1>Welcome to the Dashboard</h1>
      <p>Your main content goes here.</p>
    </div>
  </main>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
