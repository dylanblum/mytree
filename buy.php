<?php $pageTitle = 'Home'; 
include "includes/header.php";

if($login){
		$conn = @mysqli_connect ($dbconfig[0],$dbconfig[1],$dbconfig[2],$dbconfig[3]);
		echo "<h1>Buy Tree</h1>";
		//set treeid to blank, so they write nothing if the form hasn't sent anything for their data
		$treeid = "";
		$category = "";
		$warning = "";
		$name = "";
		
		if ($_GET!=NULL){//Check if the form has sent anything
			
			//Email Check section
			if (isset ($_GET["tree"]) and $_GET["tree"]!=NULL){
				$treetemp = $_GET['tree'];
				$check = substr($treetemp, 0, 1);
				if ($check == 'p'){
					$treetemp = substr($treetemp, 1);
					$qry = "select * from proposed where planting_id=\"$treetemp\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						if ($row['owner']!=NULL){
							$warning .= "<p>The propsed tree with the ID \"$treetemp\" has already been allocated.</p>";
						} else{
							$treeid = $treetemp;
							$category = "proposed";
						}
					} else{
						$warning .= "<p>The propsed tree with the ID \"$treetemp\" could not be found.</p>";
					}
				} else {
					$qry = "select * from trees where tree_id=\"$treetemp\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						if ($row['owner']!=NULL){
							$warning .= "<p>The tree with the ID \"$treetemp\" has already been named.</p>";
						} else{
							$treeid = $treetemp;
							$category = "existing";
						}
					} else{
						$warning .= "<p>The tree with the ID \"$treetemp\" could not be found.</p>";
					}	
				}
				
				mysqli_free_result($result); //free up the result
			
			}
		}
		if ($_POST!=NULL){
			$errmsg="";
			//Profile name Check Section
			if (isset ($_POST["category"]) and $_POST["category"]!=NULL){
				$category = $_POST["category"];
			} else {
				$errmsg .= "<p>Please specify if it's an existing or proposed tree.</p>";
			}
			if (isset ($_POST["treeid"]) and $_POST["treeid"]!=NULL){
				$treetemp = $_POST["treeid"];
				
				if ($category == 'proposed'){
					$qry = "select * from proposed where planting_id=\"$treetemp\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						if ($row['owner']!=NULL){
							$errmsg .= "<p>The propsed tree with the ID \"$treetemp\" has already been allocated.</p>";
						} else{
							$treeid = $treetemp;
						}
					} else{
						$errmsg .= "<p>The propsed tree with the ID \"$treetemp\" could not be found.</p>";
					}
				} else if ($category == 'existing') {
					$qry = "select * from trees where tree_id=\"$treetemp\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						if ($row['owner']!=NULL){
							$errmsg .= "<p>The tree with the ID \"$treetemp\" has already been named.</p>";
						} else{
							$treeid = $treetemp;
						}
					} else{
						$errmsg .= "<p>The tree with the ID \"$treetemp\" could not be found.</p>";
					}	
				}
			} else {
				$errmsg .= "<p>Please specify a Tree ID.</p>";
			}
			
			//Profile name Check Section
			if (isset ($_POST["name"]) and $_POST["name"]!=NULL){
				$name = $_POST["name"];//retrieve form profile name data
			} else {
				$errmsg .= "<p>Please name the tree.</p>";
			}
			
			if ($errmsg == ""){
				if ($category == 'proposed'){
					$qry = "UPDATE proposed SET owner = $login, name = '$name' WHERE planting_id = $treeid";
					$result = mysqli_query($conn, $qry);
					echo "<p>You have now purchased the Carbon Offest of Proposed Tree #$treeid. That tree is now to be named <b>$name</b>. Thank you for your contribution.</p>";
					echo "<p><a href='index.php'>Back to Front</a></p>";
				} else if ($category == 'existing') {
					// Update Tree 
					$qry = "UPDATE trees SET owner = $login, name = '$name' WHERE tree_id = $treeid";
					$result = mysqli_query($conn, $qry);
					echo "<p>You have now adopted Tree #$treeid, and it is now named <b>$name</b>. Thank you for your contribution.</p>";
					echo "<p><a href='index.php'>Back to Front</a></p>";
				}
			} else {
				
				echo $errmsg;
				$form = "<form method='post' action='buy.php'><p>Tree ID <input type='text' required name='treeid' maxlength='10' value='$treeid' /></p><p>Category: Proposed<input type='radio' name='category' value='proposed' "; 
				if ($category == 'proposed'){ $form .= " checked ";}
				$form .="> Existing<input type='radio' name='category' value='existing' ";
				if ($category == 'existing'){ $form .= " checked ";}
				$form .="><p>Tree Name <input type='text' required  name='name' maxlength='100' value='$name' /> (Name your tree)</p><p><input type='submit' value='Buy' /></p></form>";
				echo $form;
			}		
			
		} else {//Page has most likely loaded from navbar
			
			$form = "<form method='post' action='buy.php'><p>Tree ID <input type='text' required name='treeid' maxlength='10' value='$treeid' /></p><p>Category: Proposed<input type='radio' name='category' value='proposed' "; 
			if ($category == 'proposed'){ $form .= " checked ";}
			$form .="> Existing<input type='radio' name='category' value='existing' ";
			if ($category == 'existing'){ $form .= " checked ";}
			$form .="><p>Tree Name <input type='text' required  name='name' maxlength='100' value='$name' /> (Name your tree)</p><p><input type='submit' value='Buy' /></p></form>";
			echo $form;
		}
		// include the header file
		mysqli_close ($conn);
	} else {
		echo "<p>You are need to be <a href='login.php'>logged in</a>.</p>";
	}

 include "includes/footer.php";?>