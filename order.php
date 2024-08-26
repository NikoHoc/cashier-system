<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
  header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "includes/head.php"; ?>
</head>

<body>
  <div class="wrapper">
    <aside id="sidebar">
      <!-- Sidebar -->
      <?php include "includes/sidebar.php"; ?>
      <!-- Sidebar -->
    </aside>

    <div class="main">
      <!-- Navbar -->
      <?php include "includes/navbar.php"; ?>
      <!-- Navbar -->

      <!-- Main Content -->
      <main class="content px-3 py-4">
        <div class="container-fluid">
          <div class="mb-3">


          </div>
        </div>
      </main>
      <!-- Main Content -->


    </div>
  </div>




  <?php include "includes/script.php"; ?>
</body>

</html>