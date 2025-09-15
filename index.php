

<?php
session_start();
if($_SESSION['user']){
?>
<!DOCTYPE html>
<html lang="en">

<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<!-- header -->
<?php include "include/headers.php"; ?>
<!-- end header -->
<?php include "config/SYD_Class.php";
$co=new sydClass();
?>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <!-- nav -->
      <?php include "include/nav.php"; ?>
      <!-- end nav -->

      <!-- side bar-->
      <?php include "include/sidebar.php"; ?>
      
      <!-- end sidebar -->

      <!-- Main Content -->
      <div class="main-content">
       
          <!-- home -->
          <div id="main" class="tab-content">
            <?php include "data/home.php"; ?>
          </div>
          
          <!-- end home -->
        
        <!-- star setting -->
        <?php include "data/sett.php"; ?>
        <!-- end settings -->
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          <a href="#">Inventory Management System</a></a>
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <!-- footer -->
  <?php include "data/footer.php"; ?>
  <!-- end footer -->
  <?php include "data/model.php"; ?>
  
</body>
<!-- index.html  21 Nov 2019 03:47:04 GMT -->



</html>


<!-- events -->
<?php include "data/events.php"; ?>
<!-- end event -->
<script>
  $("#main").click(function(){
    $(".nav-link").removeClass("active");
    // alert()
  })
</script>



 
<?php
}
else{
    header("Location: login.php");
}
?>