<?php


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../components/link.php"; ?>
</head>

<body>
  <main>
    <section class="" style="background-color: #ffbc51;">
      <div class="container py-5 " style="width: 70%; height: 60%">
        <div class="row d-flex justify-content-center align-items-center  ">
          <div class="col col-xl-10 ">
            <div class="card" style="border-radius: 1rem; border: solid red 3px">
              <div class="row g-0">
                <div class="col-md-6 col-lg-5 d-none d-md-block">
                  <img src="foto-signin.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                </div>
                <div class="col-md-6 col-lg-7 d-flex align-items-center ">
                  <div class="card-body p-4 p-lg-5 text-black" >

                    <form >

                      <div class="d-flex align-items-center mb-3 pb-1">
                        <img src="logo sapi.png" class="img-fluid rounded-circle border border-warning" style="width: 70px; height: 50px;" alt="Logo">
                        <span class="ms-1 h1 fw-bold mb-0">Depot Bakso Asli</span>
                      </div>

                      <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                      <div data-mdb-input-init class="form-outline mb-4 border border-danger" style="border-radius: 0.3rem;">
                        <input type="email" id="form2Example17" class="form-control form-control-lg" />
                        <label class="form-label" for="form2Example17">Email address</label>
                      </div>

                      <div data-mdb-input-init class="form-outline mb-4 border border-danger" style="border-radius: 0.3rem;"">
                        <input type="password" id="form2Example27" class="form-control form-control-lg" />
                        <label class="form-label" for="form2Example27">Password</label>
                      </div>

                      <div class="pt-1 mb-4">
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="button">Login</button>
                      </div>

                      <a class="small text-muted" href="#!">Forgot password?</a>
                      <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!" style="color: #393f81;">Register here</a></p>

                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

</body>

</html>