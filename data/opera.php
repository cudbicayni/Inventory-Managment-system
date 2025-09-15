<?php 
include "Codes.php";
$ob=new Codes();
$ob->setConnect();
//$sql="call levels_proc('$lev','$price','insert',null)";
$tiro= count($_REQUEST);
$i=1;
$sql="call ";

foreach ($_REQUEST as $key => $value) {
	if($i==1)
		$sql.=" $value(";
	else if($i==$tiro)
		$sql.="'$value')";
	else
		$sql.="'$value',";
	$i++;
}
// echo $sql;
$ob->setSQL($sql);
?>
