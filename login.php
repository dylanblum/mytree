<?php
	// set up the page title
	$pageTitle = 'Login';
	
	// include the header file
	include('includes/header.php');
	if(!$login){
		$conn = @mysqli_connect ($dbconfig[0],$dbconfig[1],$dbconfig[2],$dbconfig[3]);
		echo "<h1>Login</h1>";
		//set email to blank, so they write nothing if the form hasn't sent anything for their data
		$username = "";		
		
		if ($_POST!=NULL){//Check if the form has sent anything
			$errmsg = "";
			//Email Check section
			if (isset ($_POST["username"]) and $_POST["username"]!=NULL){
				$username = $_POST["username"];
				
				//Query users table to see if email is already in use
				
				$qry = "select * from users where username=\"$username\"";
				$result = mysqli_query($conn, $qry);
				$row = mysqli_fetch_assoc ($result);
				if ($row) { //If a match was found
					if (isset ($_POST["pass"]) and $_POST["pass"]!=NULL){
						$pass = $_POST["pass"];
						$pass_actual = $row["password"];
						if ($pass != $pass_actual){
							$errmsg .= "<p>Incorrect Password, please try again.</p>";
						}
					} else {
						$errmsg .= "<p>Please enter your password.</p>";
					}
				} else {
					$errmsg .= "<p>Username not found. Are you sure you have <a href='register.php'>registered</a>?</p>";
				}
				mysqli_free_result($result); //free up the result
				
			} else {
				$errmsg .= "<p>Please enter your email address.</p>";
			}
			
			if ($errmsg == ""){
				
				$qry = "select id, name from users where username=\"$username\"";//Find the user id of the user now they've signed up
				$result = mysqli_query($conn, $qry);
				$row = mysqli_fetch_assoc ($result);
				$userid = $row["id"];
				$_SESSION["login"] = $userid;//Set the user logged in to be the user's user id
				$name = $row["name"];
				echo "<p>Welcome back {$name}. You have successfully logged in.<p>";
				mysqli_free_result($result); //free up the result
				
				echo "<p><a href='index.php'>Back to Front</a></p>";
				
			} else {
				
				echo $errmsg;
				echo "<form method='post' action='login.php'><p>Username <input type='text' required name='username' maxlength='20' value='$username' /><p>Password <input type='password' required  name='pass' maxlength='20' /></p><p><input type='submit' value='Log In' /></p></form>";
				echo "<p>New User? <a href='register.php'>Sign Up Here!</a></p>";
			}		
			
		} else {//Page has most likely loaded from navbar
			
			echo "<form method='post' action='login.php'><p>Username <input type='text' required name='username' maxlength='20' value='$username' /><p>Password <input type='password' required  name='pass' maxlength='20' /></p><p><input type='submit' value='Log In' /></p></form>";
			echo "<p>New User? <a href='register.php'>Sign Up Here!</a></p>";
		}
		
		// include the header file
		mysqli_close ($conn);
	} else {
		echo "<p>You are already logged in...</p>";
	}
	include "includes/footer.php";
?>