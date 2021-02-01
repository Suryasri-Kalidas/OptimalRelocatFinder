<?php 
//Connect to MySQL
$mysqli = new mysqli('127.0.0.1', 'cs411project','54321','relocate');
if ($mysqli->connect_errno) {
	echo "<script>alert(\"Database not found: " . $mysqli->connect_errno ."\")</script>";
	exit;
}
?>
