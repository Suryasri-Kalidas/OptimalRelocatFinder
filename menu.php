<h1 style="text-indent: 20">Relocate: Location Finder</h1>
<div style="position:absolute; top: 47; right: 5; width:100px, text-align:right;">
<?php 
if (!empty($username)) {
	echo 'Logged in as '.$username;
}
?>
</div>
<div class="topnav">
	<a class="home" href="/" id="home">Search</a>
	<a class="saves" href="/saved.php" id="saves">Saved Searches</a>
	<div class="topnav-right">
	<?php 
		if (empty($username)) {
			echo '<a href="/login.php">Log In</a><a href="/signup.php">Sign Up</a>';
		} else {
			echo '<a href="/signout.php">Sign Out</a>';
		}
	?>
	</div>
</div>

