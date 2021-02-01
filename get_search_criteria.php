<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"])) {
	$username = $_SESSION["username"];
} else {
	$username = "";
}

//Grab search criteria input 
if (isset($_POST['type']) && is_numeric($_POST['type'])) {
	$type = (int) $_POST['type'];
} else {
	$type = -1; //County search
}

if (isset($_POST['pop_density']) && is_numeric($_POST['pop_density']) && isset($_POST['pop_density_pref']) && is_numeric($_POST['pop_density_pref'])) {
	$pop_density = (int) $_POST['pop_density'];
	$pop_density_pref = (int) $_POST['pop_density_pref'];
} else {
	$pop_density_pref = 0;
	$pop_density = 0;
}

if (isset($_POST['poverty_pref']) && is_numeric($_POST['poverty_pref'])) {
	$poverty_pref = (int) $_POST['poverty_pref'];
} else {
	$poverty_pref = 0;
}

if (isset($_POST['health_pref']) && is_numeric($_POST['health_pref'])) {
	$health_pref = (int) $_POST['health_pref'];
} else {
	$health_pref = 0;
}

if (isset($_POST['education_pref']) && is_numeric($_POST['education_pref'])) {
	$education_pref = (int) $_POST['education_pref'];
} else {
	$education_pref = 0;
}

if (isset($_POST['unemployment_pref']) && is_numeric($_POST['unemployment_pref'])) {
	$unemployment_pref = (int) $_POST['unemployment_pref'];
} else {
	$unemployment_pref = 0;
}

if (isset($_POST['min_hh_income']) && is_numeric($_POST['min_hh_income'])) {
	$min_hh_income = (int) $_POST['min_hh_income'];
} else {
	$min_hh_income = 0;
}

if (isset($_POST['max_hh_income']) && is_numeric($_POST['max_hh_income'])) {
	$max_hh_income = (int) $_POST['max_hh_income'];
} else {
	$max_hh_income = 10000000; //there shouldnt be any counties with an income higher than this
}

//Advanced function: keep track of selected areas for commuting
if (isset($_POST['selected']) && !empty($_POST['selected'])) {
	$selected = $_POST['selected'];
} else {
	$selected = json_encode([]);
}
?>
