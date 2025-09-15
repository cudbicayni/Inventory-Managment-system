<!DOCTYPE html>
<html lang="en">


<!-- auth-reset-password.html  21 Nov 2019 04:05:02 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>sos Management System</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
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
                <h4>Reset Password</h4>
              </div>
              <div class="card-body">
                <p class="text-muted">Enter Your New Password</p>
                <?php if (isset($_GET['email'])) : ?>
                  <label>A CODE HAS BEEN SENT TO <b><?php echo $_GET['email']; ?></b> ENTER BELOW CODE 👇</label>
                <?php endif; ?>
                <?php if (isset($_GET['error'])) : ?>
                  <div style="color: red;"><?php echo $_GET['error']; ?></div>
                <?php endif; ?>
                <form method="post" action="process_verification.php">
                  <div class="form-group">
                    <label for="reset_code"><strong>Reset Code:</strong></label> <br>
                    <input type="text" id="reset_code" class="form-control" name="reset_code" required>
                    <br><br>
                  </div>
                  <label for="new_password"><strong>New Password:</strong>:</label>
                  <input type="password" id="new_password" class="form-control" name="new_password" required>
                  <br><br>
                  <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
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


<!-- auth-reset-password.html  21 Nov 2019 04:05:02 GMT -->
</html>