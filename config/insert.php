<?php
$sql = $_REQUEST['sql'];
include("SYD_Class.php");
$coder = new sydClass();
$coder->operation($sql);
if ($insert_success) {
    echo "successfully inserted";
} else {
    echo "Error: could not insert record.";
}
?>