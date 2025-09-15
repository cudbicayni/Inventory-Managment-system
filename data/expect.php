<?php
// ====================
// Finance: Total, Monthly, Daily Sales and Expenses
// ====================


function TotalSales() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL Total_view()";
	try {
		$r = $db->query($sql);
		if ($rw = $r->fetch_array(MYSQLI_NUM))
			echo $rw[0];
		else
			echo "0";
		$r->free();
		$db->next_result();
		$db->close();
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}

function expences() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL TotalExp_view()";
	try {
		$r = $db->query($sql);
		if ($rw = $r->fetch_array(MYSQLI_NUM))
			echo $rw[0];
		else
			echo "0";
		$r->free();
		$db->next_result();
		$db->close();
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}

function customer() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL countCustomer()";
	try {
		$r = $db->query($sql);
		if ($rw = $r->fetch_array(MYSQLI_NUM))
			echo $rw[0];
		else
			echo "0";
		$r->free();
		$db->next_result();
		$db->close();
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}



function purdash() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL purdash_view()";
	try {
		$r = $db->query($sql);
		if ($rw = $r->fetch_array(MYSQLI_NUM))
			echo $rw[0];
		else
			echo "0";
		$r->free();
		$db->next_result();
		$db->close();
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}

function receivable() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL AccountsReceivable()";
	$value = 0;
	try {
		if ($result = $db->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				$value += $row["total_accounts_receivable"];
			}
			$result->free();
			$db->next_result();
		}
		$db->close();
		echo $value;
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}

function payable() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL AccountsPayable()";
	$value = 0;
	try {
		if ($result = $db->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				$value += $row["total_accounts_payable"];
			}
			$result->free();
			$db->next_result();
		}
		$db->close();
		echo $value;
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}

function dailyAuditToday() {
	$db = new mysqli("localhost", "root", "", "invent");
	$sql = "CALL dailyaudit()";
	try {
		$r = $db->query($sql);
		if ($rw = $r->fetch_array(MYSQLI_NUM))
			echo $rw[0];
		else
			echo "0";
		$r->free();
		$db->next_result();
		$db->close();
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
}






?>
