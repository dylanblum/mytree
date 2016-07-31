<?php $pageTitle = 'Register'; 
	include "includes/header.php";

	if(!$login){
		
		$conn = @mysqli_connect ($dbconfig[0],$dbconfig[1],$dbconfig[2],$dbconfig[3]);
		echo "<h1>Registration Page</h1>";
		//set email and name to blank, so they write nothing if the form hasn't sent anything for their data
		$email = "";
		$username = "";
		$name = "";
		
		
		if ($_POST!=NULL){//Check if the form has sent anything
			$errmsg = "";
			//Email Check section
			if (isset ($_POST["email"]) and $_POST["email"]!=NULL){
				$emailregex = "/^.+@.+\..{2,3}$/";//regular expression for an email address
				$email = $_POST["email"]; // retrieve the form email data 
				if (preg_match($emailregex, $email)){//check if it's an email address
					//Query users table to see if email is already in use
					
					$qry = "select * from users where email=\"$email\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						$errmsg .= "<p>Email address already in use.</p>";
					}
					mysqli_free_result($result); //free up the result
				} else {
					$errmsg .= "<p>Please enter a valid email address.</p>";
				}
			} else {
				$errmsg .= "<p>Please enter a valid email address.</p>";
			}
			
			//Profile name Check Section
			if (isset ($_POST["name"]) and $_POST["name"]!=NULL){
				$name = $_POST["name"];//retrieve form profile name data
				$qry = "select * from users where name=\"$name\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						$errmsg .= "<p>Name already in use.</p>";
					}
					mysqli_free_result($result); //free up the result
			} else {
				$errmsg .= "<p>Please enter a profile name.</p>";
			}
			
			//Profile name Check Section
			if (isset ($_POST["username"]) and $_POST["username"]!=NULL){
				$username = $_POST["username"];//retrieve form profile name data
				$qry = "select * from users where username=\"$username\"";
					$result = mysqli_query($conn, $qry);
					$row = mysqli_fetch_assoc ($result);
					if ($row) { //If a match was found
						$errmsg .= "<p>Username already in use.</p>";
					}
					mysqli_free_result($result); //free up the result
			} else {
				$errmsg .= "<p>Please enter a profile name.</p>";
			}
			
			if (isset ($_POST["pass"]) and $_POST["pass"]!=NULL){
				if (isset ($_POST["pass_conf"]) and $_POST["pass_conf"]!=NULL){
					$pass = $_POST["pass"];
					$pass_conf = $_POST["pass_conf"];
					if ($pass == $pass_conf){
						$passregex = "/^[A-Za-z0-9]+$/";//regular expression for letters and numbers
						if (!preg_match($passregex, $pass)){//if it has anything other than letters and numbers
							$errmsg .= "<p>Password can only contain letters and numbers.</p>";
						}
					} else {
						$errmsg .= "<p>Passwords do not match.</p>";
					}
				} else {
					$errmsg .= "<p>Please confirm your password.</p>";
				}
			} else {
				$errmsg .= "<p>Please enter a password.</p>";
			}
			
			if ($errmsg == ""){
				
				// Insert into the table 
				$qry = "insert into users(username, password, email, name)
									values(\"$username\", \"$pass\", \"$email\", \"$name\")";
				$result = mysqli_query($conn, $qry);
				echo "<p>Registration of $name ($email) was successful.</p>";
				$qry = "select id from users where username=\"$username\"";//Find the user id of the user now they've signed up
				$result = mysqli_query($conn, $qry);
				$row = mysqli_fetch_assoc ($result);
				$userid = $row["id"];
				$_SESSION["login"] = $userid;//Set the user logged in to be the user's user id
				mysqli_free_result($result); //free up the result
				echo "<p><a href='index.php'>Back to Front</a></p>";
				
			} else {
				
				echo $errmsg;
				echo "<form method='post' action='register.php'><p>Username <input type='text' required name='username' maxlength='20' value='$username' /></p><p>Email <input type='email' required name='email' maxlength='100' value='$email' /></p><p>Public Name <input type='text' required  name='name' maxlength='30' value='$name' /> (The name that will be shown on your trees)</p><p>Password <input type='password' required  name='pass' maxlength='20' /></p><p>Confirm Password <input type='password' required  name='pass_conf' maxlength='20' /></p><p><input type='submit' value='Register' /></p></form>";
			}		
			
		} else {//Page has most likely loaded from navbar
			
			echo "<form method='post' action='register.php'><p>Username <input type='text' required name='username' maxlength='20' value='$username' /></p><p>Email <input type='email' required name='email' maxlength='100' value='$email' /></p><p>Public Name <input type='text' required  name='name' maxlength='30' value='$name' /> (The name that will be shown on your trees)</p><p>Password <input type='password' required  name='pass' maxlength='20' /></p><p>Confirm Password <input type='password' required  name='pass_conf' maxlength='20' /></p><p><input type='submit' value='Register' /></p></form>";
		}
		// include the header file
		mysqli_close ($conn);
	} else {
		echo "<p>You are already logged in...</p>";
	}
	
	include "includes/footer.php";?>