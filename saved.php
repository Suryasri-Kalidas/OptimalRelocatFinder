<?php 
session_start();

require_once "dbconfig.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
} else {
	header("location: login.php");
	exit;
}

$sql = "CALL SP_Retrieve_History(?)";
if ($stmt = mysqli_prepare($mysqli, $sql)) {
	mysqli_stmt_bind_param($stmt, "s", $param_username);
	$param_username = $username;

	if (mysqli_stmt_execute($stmt)) {
		$result = mysqli_stmt_get_result($stmt);
		$data = array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[] = $row;
		}
	}
}
?>
 

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<form id="formoid" action="index.php" method="post"></form>
	<form id="deleteformoid" action="deleteQuery.php" method="post"></form>
<?php include 'menu.php'; ?>

	<div id="results">
<script>
var data = <?php echo json_encode($data); ?>;

function set_post(i) {
	for (var key in data[i]) {
		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", key);
		input.setAttribute("value", data[i][key]);
		document.getElementById("formoid").appendChild(input);
	}
	document.getElementById("formoid").submit();

}

function delete_id(i) {
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "delete_id");
	input.setAttribute("value", data[i]["id"]);
	document.getElementById("deleteformoid").appendChild(input);

	document.getElementById("deleteformoid").submit();
}

</script>
<script>
	document.write('<h2>Saved Searches:</h2><font size="4">');
	for (var i = data.length - 1; i >= 0 ; i--) {
		document.write('<p style="text-indent: 20">');
		document.write(data[i]['name']);

		document.write('  <button onclick="set_post(');
		document.write(i);
		document.write(')" href="#">');
		document.write("Load");
		document.write("</button>");

		document.write('  <button onclick="delete_id(');
		document.write(i);
		document.write(')" href="#">');
		document.write("Delete");
		document.write("</button></p>");
	}
	document.write("</font>");
</script>	
	</div>    
</body>
</html>
