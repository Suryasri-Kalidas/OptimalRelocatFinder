<?php 
session_start();

require_once "dbconfig.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
} else {
	$username = "";
}

//Grab search criteria input 
include 'get_search_criteria.php';

$result = $mysqli -> query("CALL SP_Rate($type,$pop_density,$min_hh_income,
	$max_hh_income,$pop_density_pref,$poverty_pref,$unemployment_pref,$education_pref)");
$data = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$data[] = $row;
}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="scripts/raphael.js"></script>
	<script src="scripts/rankColor.js"></script>
	<script src="scripts/jquery-3.5.0.min.js"></script>
</head>
<body>

	<?php include 'menu.php'; ?>
	<div class="search" align="center">
    		<h2>Search for states or counties</h2>
		<form id="formoid" class="form-horizontal" action="index.php" method="post">
			<?php //function to help us change default selected value for every field
				function selected($a,$b) {
					if ($a == $b) { 
						echo "selected=\"selected\""; 
					}
				}
			?>
			<script>
				function typeChange() {
					var v = document.getElementById('type_select').value;
					if (v == 1) {
						document.getElementById('density_select').style.display = 'none';
					} else {
						document.getElementById('density_select').style.display = 'block';
	
					}
				}
			</script>
			<div class="form-group">
				<label for="type">Choose a search type:</label>
				<select name="type" id="type_select" onchange="typeChange()">
				<?php $sel = $type; ?>
				<option <?php selected($sel, 2); ?> value=2>County Search</option>
				<option <?php selected($sel, 1); ?> value=1>State Search</option>
				</select>
			</div>
			<div class="form-group" id="density_select">
				<label for="pop_density">Population density:</label>
				<input type="range" name="pop_density" value=<?php echo "\"$pop_density\""; ?>min="1" max="9">
				</select>
				<label for="pop_density_pref">Priority:</label>
				<select name="pop_density_pref">
					<?php $sel = $pop_density_pref; ?>
					<option <?php selected($sel, 3); ?> value=3>High</option>
					<option <?php selected($sel, 2); ?> value=2>Medium</option>
					<option <?php selected($sel, 1); ?> value=1>Low</option>
					<option <?php selected($sel, 0); ?> value=0>None</option>
				</select>
			</div>
			<div class="form-group">
				<label for="poverty_pref">Low Poverty Priority:</label>
				<select name="poverty_pref">
					<?php $sel = $poverty_pref; ?>
					<option <?php selected($sel, 3); ?> value=3>High</option>
					<option <?php selected($sel, 2); ?> value=2>Medium</option>
					<option <?php selected($sel, 1); ?> value=1>Low</option>
					<option <?php selected($sel, 0); ?> value=0>None</option>
				</select>
			</div>
			<div class="form-group">
				<label for="education_pref">Quality Education Priority:</label>
				<select name="education_pref">
					<?php $sel = $education_pref; ?>
					<option <?php selected($sel, 3); ?> value=3>High</option>
					<option <?php selected($sel, 2); ?> value=2>Medium</option>
					<option <?php selected($sel, 1); ?> value=1>Low</option>
					<option <?php selected($sel, 0); ?> value=0>None</option>
				</select>
			</div>
			<div class="form-group">
				<label for="unemployment_pref">Low Unemployment Priority:</label>
				<select name="unemployment_pref">
					<?php $sel = $unemployment_pref; ?>
					<option <?php selected($sel, 3); ?> value=3>High</option>
					<option <?php selected($sel, 2); ?> value=2>Medium</option>
					<option <?php selected($sel, 1); ?> value=1>Low</option>
					<option <?php selected($sel, 0); ?> value=0>None</option>
				</select>
			</div>
			<div class="form-group">
				<?php $sel = $min_hh_income; ?>
				<input type="text" id="min_hh_income" name="min_hh_income" value=<?php echo '"'.$sel.'"'; ?>>
				<label for="max_hh_income"> &lt; Median Household Income &lt; </label>
				<?php $sel = $max_hh_income; ?>
				<input type="text" id="max_hh_income" name="max_hh_income" value=<?php echo '"'.$sel.'"'; ?>>
			</div>
			</br>
			<button type="submit" d="submitButton" name="submitButton" value="Submit" style="width:400">Search</button>
		</form>
		<hr> 
		<form id="saveQuery" class="form-vertical" action="saveQuery.php" method="post">
			<div class="form-group">
				<label for="save_query_name">Save search results as:</label>
				<input type="text" id="save_query_name" name="save_query_name" value="">
			</div>
			<button type="submit" d="submitButton" name="submitButton" value="Submit">Save</button>
			<hr>
		</form>
		<script> 
			if (<?php echo $type; ?> == -1) {
				document.getElementById('saveQuery').style.display = 'none';
			}

			$("#formoid").submit(function(event) {
				var params = [{name: "selected", value: JSON.stringify(selected)}];
				$(this).append($.map(params, function(param) {
					return $('<input>', {
						type: 'hidden',
						name: param.name,
						value: param.value
					})
				}))
			});

			$("#saveQuery").submit(function(event) {
				var params = [{name: "selected", value: JSON.stringify(<?php echo $selected; ?>)},
					{name: "type", value: <?php echo $type; ?>},
					{name: "pop_density", value: <?php echo $pop_density; ?>},
					{name: "min_hh_income", value: <?php echo $min_hh_income; ?>},
					{name: "max_hh_income", value: <?php echo $max_hh_income; ?>},
					{name: "pop_density_pref", value: <?php echo $pop_density_pref; ?>},
					{name: "poverty_pref", value: <?php echo $poverty_pref; ?>},
					{name: "unemployment_pref", value: <?php echo $unemployment_pref; ?>},
					{name: "education_pref", value: <?php echo $education_pref; ?>}];
				$(this).append($.map(params, function(param) {
					return $('<input>', {
						type: 'hidden',
						name: param.name,
						value: param.value
					})
				}))
			});
			typeChange();

		</script>
	</div>
	<div class="map" align="center" id="map"></div>
</body>
</html>
<script>
	var file = "";
	if (<?php echo $type; ?> == 1) {
		file = "json/states.json";
	} else if (<?php echo $type; ?> == 2) {
		file = "json/counties.json";
	}

	var map = Raphael(document.getElementById("map", 500, 300));

	var request = new XMLHttpRequest();
	request.open("GET", file, false);
	request.send(null);

	var data = JSON.parse(request.responseText);
	var sql_data = <?php echo json_encode($data); ?>;


	var selected = <?php echo $selected; ?>;

	//Convert sql data into a dictionary!
	var locations = {}
	for (var i = 0; i < sql_data.length; i++) {
		locations[sql_data[i]['fips_id']] = sql_data[i];
	}

	for (var i = 0; i < data.length; i++) {
		var current_path = map.path(data[i]['edges']);
		current_path.id = data[i]['fips_id'];
		current_path.name = data[i]['name'];
		current_path.selected = selected.includes(current_path.id);

		var rating = -1;
		if (data[i]['fips_id'] && locations[ data[i]['fips_id'] ]) {
			raw_rating = locations[data[i]['fips_id'] ]['rating'];
			current_path.rating = Math.max(0,Math.min(1,raw_rating));
			locations[data[i]['fips_id']]['path'] = current_path;
		}
	
		//Color by ranking
		current_path.color = "#000000";
		if (current_path.rating >= 0) {
			current_path.color = getRankColor(current_path.rating);
		}

		if (current_path.selected == 0) {
			current_path.attr({opacity:".5", stroke:"#000000", fill:current_path.color, "stroke-width":"0.2"});
		} else {
			current_path.attr({opacity:"1", stroke:"#257AFD", fill:"#257AFD", "stroke-width":"0.2"});
		}

		current_path.hover(function() {this.attr({"opacity":"1"})},
			function() {
				if (this.selected == 0) {
					this.attr({"opacity":".5"});
				}
			});

		current_path.dblclick(function() {
			var url = window.location.origin.concat("/location.php?id=".concat(this.id));
			window.open(url);
		});

		current_path.click(function() {
			if (this.selected == 0) {
				this.attr({opacity:"1", stroke:"#257AFD", fill:"#257AFD"});
				this.selected = 1;

				selected.push(this.id);
			} else {
				this.attr({opacity:".5", stroke:"#000000", fill:this.color});
				this.selected = 0;

				selected.splice(selected.lastIndexOf(this.id), 1);
			}
		});
	}

	if (selected.length > 0) {
		for (var i = 0; i < data.length; i++) {
			if (data[i]['fips_id'] && locations[data[i]['fips_id'] ]) {
				var current_path = locations[data[i]['fips_id']]['path'];
				if (current_path.selected == 0) {
					var c_bbox = current_path.getBBox();
					var proximity = 100;

					for (var j = 0; j < selected.length; j++) {
						var s_bbox = locations[selected[j]]['path'].getBBox();
						var dist = Math.max(Math.abs(c_bbox.x - s_bbox.x), Math.abs(c_bbox.y - s_bbox.y));
						proximity = Math.min(proximity, dist);
					}
					if (proximity > 10) {
						current_path.rating = -1;
						current_path.color = "#000000";
					} else {
						current_path.rating *= Math.max(current_path.rating, 1.2 - .05*proximity);
						current_path.color = getRankColor(current_path.rating);
					}
					current_path.attr({fill:current_path.color});
	
				}
			}
		}
	}
</script>
<?php
	$mysqli -> close();
?>
