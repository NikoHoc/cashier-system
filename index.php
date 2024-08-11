<?php
require_once("./services/config.php");
session_start();

if($_SESSION['is_login'] == false) {
  header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/link.php"; ?>  
</head>

<body>
  <!-- Navbar -->
  <?php include "components/navbar.php"; ?>
  <!-- Navbar -->

  <main>
    <div>
    
    </div>
    

  </main>

</body>

</html>