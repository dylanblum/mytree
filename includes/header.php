<?php
require_once ("includes/settings.php");

	session_start(); // start the session 
	if (isset ($_SESSION["login"])) { // check if session variable exists - If user is logged in
		$login = $_SESSION["login"]; //set $login to be their user id
	} else {
	$login = false;//used for showing/hiding things based on whether they're logged in or not.
	}
	if (!isset($pageTitle)) {
		$pageTitle = "";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META name="keywords" content="" /> 
	<META name="description" content=""> 
	<link type="text/css" rel="stylesheet" href="styles/reset.css">
	<link type="text/css" rel="stylesheet" href="styles/style.css">
	<!--<script src="includes/scripts.js"></script>-->


	<title>MyTree<?php if ($pageTitle != ""){echo " - $pageTitle"; }?></title>
		
</head>
<body>
	<div id="wrapper">
		<header id="top">
		<img id="logo" src="img/logo.png">
		<h1 id="mainTitle">MyTree</h1>
		</header>
<?php
		$nav = "<nav>\n<ul>\n<li><a href='index.php' >Home</a></li>\n<li><a href='map.php' >Tree Map</a></li>\n<li>";
		if (!$login){
			$nav .= "<a href='login.php' >Log in</a></li>";
		} else{
			$nav .= "<a href='buy.php' >Adopt Tree</a></li>";
		}
		$nav .= "\n</ul>\n</nav>";
		
			echo $nav;
		
?>
		<div id="main">
			<div id="content">
			