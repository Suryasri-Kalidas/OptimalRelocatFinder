<?php 
session_start();

require_once "dbconfig.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
} else {
	header("location: login.php");
	exit;
}

if (isset($_POST['delete_id']) && !empty($_POST['delete_id'])) {
	$delete_id = $_POST['delete_id'];
} else {
	header("location: saved.php");
	exit;
}

$sql = "CALL SP_Delete_Search(?,?)";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "sd", $username, $delete_id);

if (!mysqli_stmt_execute($stmt)) {
	echo '<alert>Something went wrong</alert>';
} else {
	header("location: saved.php");
}

mysqli_stmt_close($stmt);
?>
