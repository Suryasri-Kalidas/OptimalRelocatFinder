<?php 

session_start();

require_once "dbconfig.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
	header("location: index.php");
	exit;
}

$username = $password = $err = "";
if (empty(trim($_POST["username"]))) {
	$err = "";
} else {
	$sql = "CALL SP_Check_Username(?)";
	if ($stmt = mysqli_prepare($mysqli, $sql)) {
		$username = trim($_POST["username"]);
		mysqli_stmt_bind_param($stmt, "s", $param_username);
		
		$param_username = $username;
		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);

			if (mysqli_stmt_num_rows($stmt) == 1) { #already exists
				$err = "Username already taken";
			}
			mysqli_stmt_close($stmt);

			
			if (empty($err)) {

				if (empty(trim($_POST["password"]))) {
					$err = "Please enter a password";
				} elseif (strlen($_POST["password"]) < 6) {
					$err = "Please choose a password of length 6 or greater";
				} else {
					$password = $_POST["password"];

					$sql = "CALL SP_Create_User(?,?)";
					
					if ($stmt = mysqli_prepare($mysqli, $sql)) {
						mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
						
						$param_username = $username;
						$param_password = password_hash($password, PASSWORD_DEFAULT);
						
						if (mysqli_stmt_execute($stmt)) {
							header("location: login.php");
							
						}
						
						mysqli_stmt_close($stmt);
					}
				}
			}
		}
	
	}
}
?>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="log_in">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="">
            </div>
            <p><font size="4" color="red"><?php echo $err; ?></font></p>
            <button type="submit" d="submitButton" name="submitButton" value="Sign up">Sign up</button>
            <p><font size="5">Already have an account? <a href="login.php">Log in</a></font></p>
        </form>
    </div>    
</body>
</html>
