<?php

	if(isset($_POST['startdate'])) {
		$startdate = $_POST['startdate'];
	}
	else {
		$startdate = date("Y-m-d",strtotime("today"));
	}
	
	if(isset($_POST['enddate']) && $_POST['enddate'] != "") {
		$enddate = $_POST['enddate'];
	}
	else {
		$enddate = date("Y-m-d",strtotime("tomorrow"));
	}


echo "Search Invoice<br />";


echo <<<EOD
<div style="text-align:left;">
<b>Search by biller and customer name</b><br />
<form action="index.php?module=invoices&view=search" method="post">
Biller:<input type="text" name="biller"><br />
Customer: <input type="text" name="customer"><br />
<input type="submit" value="Search">
</form>
<br />
<br />


<b>Search by date</b>
<form action="index.php?module=invoices&view=search" method="post">
<input type="text" class="date-picker" name="startdate" id="date1" value="$startdate" /><br /><br />
<input type="text" class="date-picker" name="enddate" id="date1" value="$enddate" /><br /><br />
<input type="submit" value="Search">
</form>
<br />
EOD;

$biller = $_POST['biller'];
$customer = $_POST['customer'];
$query = null;

if(isset($_POST['biller']) || isset($_POST['customer'])) {
	//search biller & customer
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date, t.inv_ty_description as type
	FROM si_biller b, si_invoices i, si_customers c, si_invoice_type t
	WHERE b.name LIKE  '%$biller%'
	AND c.name LIKE  '%$customer%' 
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	$query = mysqlQuery($sql);
}

$startdate = $_POST['startdate'];
$enddate = $_POST['enddate'];

if(isset($_POST['startdate']) && isset($_POST['enddate'])) {
	$sql = "SELECT b.name as biller, c.name as customer, i.id as invoice, i.date as date, t.inv_ty_description as type
	FROM si_biller b, si_invoices i, si_customers c, si_invoice_type t
	WHERE i.date >= '$startdate' 
	AND i.date <= '$enddate'
	AND i.biller_id = b.id 
	AND i.customer_id = c.id
	AND i.type_id = t.inv_ty_id";
	$query = mysqlQuery($sql);
}


if($query != null) {
	echo "<b>Result</b>";
	echo "<table border=1 cellpadding=2>";
	while($res = mysql_fetch_array($query)) {
		echo "<tr>";
		echo "<td><a href='index.php?module=invoices&view=quick_view&submit=$res[invoice]&style=$res[type]'>$res[invoice]</a></td>
		<td>$res[date]</td>
		<td>$res[biller]</td>
		<td>$res[customer]</td>
		<td>$res[type]</td>";
		echo "</tr>";
	}
	echo "</table>";
}

echo "</div>";
/*
"Enhancements to Invoice Manage page

?>