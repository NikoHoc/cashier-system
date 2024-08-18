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
  <div class="wrapper">
    <aside id="sidebar">
      <!-- Sidebar -->
      <?php include "components/navbar.php"; ?>
    </aside>

    <div class="main">
      <nav class="navbar navbar-expand px-4 py-3">
        <h3 class="fw-bold fs-4 mt-2">Admin Dashboard</h3>
        <div class="navbar-collapse collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                <img src="./assets/images/logo_sapi.png" class="avatar img-fluid" alt="">
              </a>
              <div class="dropdown-menu dropdown-menu-end rounded">

              </div>
            </li>
          </ul>
        </div>
      </nav>

      <main class="content px-3 py-4">
        <div class="container-fluid">
          <div class="mb-3">
            

          </div>
        </div>
      </main>

      </main>

    </div>
  </div>


  <!-- Main Content -->
  <main>

  </main>


  <script src="../assets/js/script.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>