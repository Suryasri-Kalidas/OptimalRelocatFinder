<?php 
session_start();

require_once "dbconfig.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
} else {
	header("location: login.php");
	exit;
}

include 'get_search_criteria.php';

if (isset($_POST['save_query_name']) && !empty($_POST['save_query_name'])) {
	$save_query_name = trim($_POST['save_query_name']);
} else {
	echo '<script>alert("Please enter a valid name for this search entry");</script>';
	exit;
}

$sql = "CALL SP_Check_Search_Name(?,?)";
if ($stmt = mysqli_prepare($mysqli, $sql)) {
	mysqli_stmt_bind_param($stmt, "ss", $username,$save_query_name);
	if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);

		if (mysqli_stmt_num_rows($stmt) > 0) {
			echo '<script>alert("Saved search already exists with this name");</script>';
			include 'blank.php';
			exit;
		}
		mysqli_stmt_close($stmt);

		$sql = "CALL SP_Store_Search(?,?,?,?,?,?,?,?,?,?,?)";
		if ($stmt = mysqli_prepare($mysqli, $sql)) {
			mysqli_stmt_bind_param($stmt, "ssdddddddds", $username,$save_query_name, 
				$type,$pop_density,$min_hh_income,$max_hh_income,$pop_density_pref,
				$poverty_pref,$unemployment_pref,$education_pref,$selected);

			if (!mysqli_stmt_execute($stmt)) {
				echo '<alert>Something went wrong</alert>';
			}
		}
		mysqli_stmt_close($stmt);
	}
}
?>
<html>
<head></head>
<body>
	
	<form id="formoid" action="index.php" method="post">
<?php
foreach($_POST as $k => $v) {
	if ($k != "save_query_name") {
		echo '<input type="hidden" name="'.htmlentities($k).'" value="'.htmlentities($v).'">';
	}	
}
?>
	</form>

</body>
</html>
<script>
	<?php echo 'document.getElementById("formoid").submit();'; ?>
</script>
