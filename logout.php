<?php 
session_start();
session_destroy();
session_unset('$user');
session_unset('$id');
header("location: log.php")
 ?>