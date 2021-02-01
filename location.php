<?php 
session_start();

require_once 'dbconfig.php';
//Grab search criteria input 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$fips_id = (int) $_GET['id'];
} else {
	$fips_id = -1; //County search
}

$loc_sql = "CALL SP_Read_Location($fips_id);";

$nat_sql = "CALL SP_National()";

#Assuming only 1 result.
$loc_result = $mysqli -> query($loc_sql);
$loc_data = mysqli_fetch_array($loc_result, MYSQLI_ASSOC);

$nat_result = $mysqli -> query($nat_sql);
$nat_data = mysqli_fetch_array($nat_result, MYSQLI_ASSOC);
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="scripts/raphael.js"></script>
</head>
<body>
	<?php include 'menu.php'; ?>

	<h2 align="center">
		<?php 
			if ($loc_data['type'] < 2) { 
				echo $loc_data['name'];
			} else {
				echo $loc_data['name'].", ".$loc_data['state']; 
			}
		?>
	</h2>
	<div>
		<p>Population (2018): <?php echo $loc_data['population']; ?></p>
		<p>Migration (2018): <?php echo $loc_data['net_migration'].'%'; ?> </p>
		<p>Urban score: <?php echo (10-$loc_data['rural_rating'])." \"".$loc_data['description'].".\""; ?></p>
		<p>Median Household Income: <?php echo '$'.number_format($loc_data['med_household_income'])." (".round($loc_data['med_household_income']*100/$nat_data['med_household_income'], 2)."% of the national average)"; ?></p>
		<p>Unemployment rate: <?php echo $loc_data['unemployment']."% (".round($loc_data['unemployment']*100/$nat_data['unemployment'], 2)."% of the national average)"; ?></p>
		<p>Poverty rate: <?php echo $loc_data['poverty']."% (".round($loc_data['poverty']*100/$nat_data['poverty'], 2)."% of the national average)"; ?></p>
		<p>Percent of adults with:
			<p style="text-indent: 20">
				Less than a highschool diploma: <?php echo $loc_data['lt_highschool']."% (".round($loc_data['lt_highschool']*100/$nat_data['lt_highschool'], 2)."% of the national average)"; ?>
			</p>
			<p style="text-indent: 20">
				Only a highschool diploma: <?php echo $loc_data['only_highschool']."% (".round($loc_data['only_highschool']*100/$nat_data['only_highschool'], 2)."% of the national average)"; ?>
			</p>
			<p style="text-indent: 20">
				Some college or an Associates degree: <?php echo $loc_data['some_college']."% (".round($loc_data['some_college']*100/$nat_data['some_college'], 2)."% of the national average)"; ?>
			</p>
			<p style="text-indent: 20">
				A Bachelors degree or better: <?php echo $loc_data['bachelors']."% (".round($loc_data['bachelors']*100/$nat_data['bachelors'], 2)."% of the national average)"; ?>
			</p>
		</p>
	</div>
	<div class="center" id="map"></div>
</body>
</html>
<script>
	var file = "";
	if (<?php echo $loc_data['type']; ?> == 1) {
		file = "json/states.json";
	} else if (<?php echo $loc_data['type'] ?> == 2) {
		file = "json/counties.json";
	}

	var map = Raphael(document.getElementById("map", 500, 300));

	var request = new XMLHttpRequest();
	request.open("GET", file, false);
	request.send(null);

	var data = JSON.parse(request.responseText);
	var sql_data = <?php echo json_encode($loc_data); ?>;

	for (var i = 0; i < data.length; i++) {
		var current_path = map.path(data[i]['edges']);
		current_path.id = data[i]['fips_id'];
		current_path.name = data[i]['name'];
	
		//Color by ranking
		var color = "#777777";
		if (sql_data['fips_id'] == data[i]['fips_id']) {
			color = "#0000FF"
		}

		current_path.attr({opacity:".5", stroke:"#000000", fill:color, "stroke-width":"0.2"});

		current_path.hover(function() {this.attr({"opacity":"1"})},
			function() {this.attr({"opacity":".5"})});

		current_path.click(function() {
			var url = window.location.origin.concat("/location.php?id=".concat(this.id));
			window.location.replace(url);
		});
	}

	//SCALE
	//map.forEach(function(obj) {
	//	obj.transform("S0.5,0.5 0,0");
	//});

</script>
<?php
	$mysqli -> close();
?>
