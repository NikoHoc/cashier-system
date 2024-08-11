<?php
require_once("./services/config.php");
session_start();

$login_notification = "";

if (isset($_SESSION['is_login']) && $_SESSION['is_login']) {
    header("location: index.php");
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $select_admin_query = "SELECT * FROM admin WHERE username='$username' AND password = '$password'";

    $select_admin = $db->query($select_admin_query);

    if ($select_admin->num_rows > 0) {
        $admin = $select_admin->fetch_assoc();

        $_SESSION['is_login'] = true;
        $_SESSION['username'] = $admin['username'];

        header("location: index.php");
    } else {
        $login_notification = "Akun admin tidak ditemukan";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "components/link.php"; ?>
</head>

<body>
  <main>
    <section class="vh-100">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6 text-black">

            <div class="px-5 ms-xl-4">
              <img src="/assets/images/logo_sapi.png" class="img-fluid rounded-circle border border-warning " style="width: 70px; height: 50px;" alt="Logo">
              <i class="fas fa-crow fa-2x pt-5 mt-xl-4" style="color: white;"></i>
              <span class="h1 fw-bold mb-0">Depot Bakso Asli</span>
            </div>

            <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
            <i><?= $login_notification ?></i>
            
              <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" style="width: 23rem;">

                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" name="email" class="form-control form-control-lg" />
                  <label class="form-label" for="form2Example18">Email address</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password"  name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="form2Example28">Password</label>
                </div>

                <div class="pt-1 mb-4">
                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block" type="submit" name="login">Login</button>
                </div>

                <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Forgot password?</a></p>
                <p>Don't have an account? <a href="#!" class="link-info">Register here</a></p>

              </form>

            </div>

          </div>
          
          <div class="col-sm-6 px-0 d-none d-sm-block">
            <img src="/assets/images/gmbr_depot.jpg"
              alt="Login image" class="w-90 vh-100" style="object-position: left;">
          </div>
        </div>
      </div>
    </section>

  </main>

</body>

</html>