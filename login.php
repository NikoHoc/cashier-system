<?php
require_once("./services/config.php");
session_start();

$login_notification = "";

if (isset($_SESSION['is_login']) && $_SESSION['is_login']) {
  header("location: index.php");
}

if (isset($_POST['login'])) {
  $username = $_POST['email'];
  $password = $_POST['password'];

  $select_admin_query = "SELECT * FROM admin_depot WHERE email_admin='$username' AND password = '$password'";

  $select_admin = $db->query($select_admin_query);

  if ($select_admin->num_rows > 0) {
    $admin = $select_admin->fetch_assoc();

    $_SESSION['is_login'] = true;
    $_SESSION['username'] = $admin['username'];

    header("location: index.php");
  } else {
    $login_notification = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "components/link.php"; ?>
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

            <form method="POST" action="">
              <div class="mb-3">
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-user"></i>
                  </span>
                  <input type="email" class="form-control" name="email" placeholder="Email" required>
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

  <!-- FontAwesome for Icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>

</html>
