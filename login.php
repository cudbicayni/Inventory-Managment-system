<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Inventory Management System.</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-social/bootstrap-social.css">
  <!-- Template CSS -->
  <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
  <link rel="stylesheet" href="log.css">

  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>
              <div class="card-body">
                <form method="POST" id="fm" >
                  <div class="form-group">
                    <label for="email">Username</label>
                    <input id="email" type="text" class="form-control" name="txtusername" id="username" tabindex="1" required autofocus>
             
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                      <div class="float-right">
                        <a href="forget.php" class="text-small">
                         Forgot Password?
                        </a>
                      </div>
                    </div>
                    <input id="password" type="password" class="form-control" name="txtpassword" tabindex="2"  required>
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" name="submit" id="b1" class="btn btn-primary btn-lg btn-block" id="submit" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
                <div class="text-center mt-4 mb-3">
                 
                </div>
                <div class="row sm-gutters">
                  
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
</body>

<script>
       var b1 = document.getElementById("b1");

b1.addEventListener("click", (e) => {
    e.preventDefault(); // Prevent form submission
    $.ajax({
    url: "session.php",
    type: "POST",
    data: $("#fm").serialize(),
    success: function(data) {
        alert(data); // Temporary for debugging
        if (data == 2) {
            window.location.href = "verify.php";
        } else if (data == 1) {
            window.location.href = "index.php";
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Login',
                text: 'Incorrect username or password. Please try again.',
                timer: 1500,
                timerProgressBar: true
            });
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred: ' + textStatus,
            timer: 3500,
            timerProgressBar: true
        });
    }
});
  //  alert($("#fm").serialize());
});

</script>
<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>