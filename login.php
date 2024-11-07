<?php
require_once("./config/database.php");
session_start();

$login_notification = "";

if (isset($_SESSION['is_login']) && $_SESSION['is_login']) {
  header("location: index.php");
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $select_admin_query = "SELECT * FROM admin_depot WHERE username='$username' AND password = '$password'";

  $select_admin = $db->query($select_admin_query);

  if ($select_admin->num_rows > 0) {
    $admin = $select_admin->fetch_assoc();

    $_SESSION['is_login'] = true;
    $_SESSION['username'] = $admin['username'];
    $_SESSION['id_admin'] = $admin['id_admin'];

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Login successful.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    setTimeout(function() {
                        window.location = 'index.php';
                    }, 100);
                });
            });
        </script>";

    // header("location: index.php");
  } else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: 'Invalid username or password.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
  </script>";
    $login_notification = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "includes/head.php"; ?>
</head>

<body class="bg-light d-flex align-items-center vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h3 class="text-center mb-4">Login</h3>

            <?php if ($login_notification != ""): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $login_notification; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <div class="mb-3">
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-user"></i>
                  </span>
                  <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
              </div>
              <div class="mb-3">
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-lock"></i>
                  </span>
                  <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
              </div>
              <div class="d-grid mb-3">
                <button type="submit" name="login" class="btn btn-primary">Log in</button>
              </div>
              <div class="text-center">
                <a href="#" class="text-decoration-none">Forgot Password?</a> | <a href="#" class="text-decoration-none">Sign Up</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include "includes/script.php"; ?>
</body>

</html>