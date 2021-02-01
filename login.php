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
	$username = trim($_POST["username"]);
	if (empty(trim($_POST["password"]))) {
		$err = "Please enter a password";
	} else {
		$password = $_POST["password"];

		if (empty($err)) {
			$sql = "CALL SP_Attempt_Login(?)";
			
			if ($stmt = mysqli_prepare($mysqli, $sql)) {
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				
				$param_username = $username;
				
				if (mysqli_stmt_execute($stmt)) {
					mysqli_stmt_bind_result($stmt, $id, $hashed_password);
					if (mysqli_stmt_fetch($stmt)) {

						if (password_verify($password, $hashed_password)) {

							session_start();
							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;
							header("location: index.php");

						} else {
							$err = "Incorrect password!";
						}
					} else {
						$err = "Incorrect username!";
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
}
?>
 
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="log_in">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <p><font size="4" color="red"><?php echo $err; ?></font></p>
            <button type="submit" d="submitButton" name="submitButton" value="Login">Log in</button>
            <p><font size="5">Don't have an account? <a href="signup.php">Sign up</a></font></p>
        </form>
    </div>    
</body>
</html>
