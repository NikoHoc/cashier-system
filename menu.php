<?php
require_once("./services/config.php");
session_start();

if ($_SESSION['is_login'] == false) {
  header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "components/link.php"; ?>
</head>

<body>
  <div class="wrapper">
    <aside id="sidebar">
      <!-- Sidebar -->
      <?php include "components/sidebar.php"; ?>
      <!-- Sidebar -->
    </aside>

    <div class="main">
      <!-- Navbar -->
      <?php include "components/navbar.php"; ?>
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




  <?php include "components/script.php"; ?>
</body>

</html>