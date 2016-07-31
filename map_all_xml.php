<?php //include "includes/header.php";

require_once ("includes/settings.php");
	
function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$conn = @mysqli_connect ($dbconfig[0],$dbconfig[1],$dbconfig[2],$dbconfig[3]);
if (!$conn) {
  die('Not connected : ' . mysqli_error());
}

// Select all the rows in the trees table
$query = "SELECT * FROM trees WHERE 1";
$result = mysqli_query($conn, $query);
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['tree_id']) . '" ';
  echo 'address="' . parseToXML($row['near_address']) . '" ';
  echo 'lat="' . $row['latitude'] . '" ';
  echo 'lng="' . $row['longitude'] . '" ';
  echo 'comname="' . $row['tree_common'] . '" ';
  echo 'botname="' . $row['tree_botanical'] . '" ';
  echo 'origin="' . $row['tree_origin'] . '" ';
  if ($row['owner']!=NULL){
	$owner = intval($row['owner']);
	$query2 = "SELECT name FROM users WHERE id = $owner";
	$result2 = mysqli_query($conn, $query2);
	$row2 = @mysqli_fetch_row($result);
	echo 'owner="' . $row2[0] . '" ';
	mysqli_free_result($result2);
  } else {
	echo 'owner="" '; 
  }
  echo 'treename="' . $row['name'] . '" ';
  if ($row['owner']!=NULL){
	echo 'type="named" ';
  } else {
	echo 'type="planted" ';
  }
  echo '/>';
}
mysqli_free_result($result);

// Select all the rows in the proposed table
$query = "SELECT * FROM proposed WHERE 1";
$result = mysqli_query($conn, $query);
if ($result) {

	// Iterate through the rows, printing XML nodes for each
	while ($row = @mysqli_fetch_assoc($result)){
	  // ADD TO XML DOCUMENT NODE
	  echo '<marker ';
	  echo 'name="Proposed #' . parseToXML($row['planting_id']) . '" ';
	  echo 'address="' . parseToXML($row['near_address']) . '" ';
	  echo 'lat="' . $row['latitude'] . '" ';
	  echo 'lng="' . $row['longitude'] . '" ';
	  echo 'comname="' . $row['tree_common'] . '" ';
	  echo 'botname="' . $row['tree_botanical'] . '" ';
	  echo 'origin="' . $row['tree_origin'] . '" ';
	  if ($row['owner']!=NULL){
		$owner = $row['owner'];
		$query = "SELECT * FROM users WHERE id = $owner";
		$result2 = mysqli_query($conn, $query);
		$row2 = @mysqli_fetch_assoc($result);
		echo 'owner="' . $row2['name'] . '" ';
		mysqli_free_result($result2);
	  } else {
		echo 'owner="" '; 
	  }
	  ;
	  echo 'treename="' . $row['name'] . '" ';
	  if ($row['owner']!=NULL){
		echo 'type="allocated" ';
	  } else {
		echo 'type="proposed" ';
	  }
	  echo '/>';
	}
}
mysqli_free_result($result);

// End XML file
echo '</markers>';
	
//include "includes/footer.php";?>