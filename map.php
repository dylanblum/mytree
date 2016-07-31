<?php $pageTitle = 'Tree Map'; include "includes/header.php";?>
	<h1>Tree Map</h1>
	<p class='smalltext'>Please be patient, the number of markers can cause long load times...</p>
	<iframe src="map_all.php" width="1000px" height="700px"></iframe>
	<h2 class='center'>Legend</h2>
	<p class='center'><img src='img/markers/marker_red.png' alt="Red Marker"> = Proposed Tree
    <img src='img/markers/marker_blue.png' alt="Blue Marker"> = Allocated Tree
    <img src='img/markers/marker_green.png' alt="Green Marker"> = Existing Tree
    <img src='img/markers/marker_gold.png' alt="Gold Marker"> = Named Tree</p>
<?php include "includes/footer.php";?>