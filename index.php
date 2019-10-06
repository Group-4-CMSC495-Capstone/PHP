<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
	
	session_start();

	$error = "";

	if (array_key_exists("logout", $_GET)) {
		unset($_SESSION);
		//setcookie("id", "", time()-60*60);
		//$_COOKIE["id"] = "";
	} else if (array_key_exists("id", $_SESSION)) {
		header("Location: events.php");
	}

	if (array_key_exists("submit", $_POST)) {
		
		$link = mysqli_connect("localhost", "admin", "xxxxxxxx", "Emailer");

		if (mysqli_connect_error()) {
			die ("Database Connection Error<br>");
		}

		if (!$_POST['username']) {
			$error .= "A username is required.<br>";
		}

		if (!$_POST['password']) {
			$error .= "A password is required.<br>";
		}

		if ($error != "") {
			$error = "<p>There were error(s) on the page:</p>".$error;
		} else {
			if ($_POST['register'] == '1') {

				if (!$_POST['email']) {
					$error .= "An email address is required.<br>";
				} else {
					$query = "SELECT Account_Id FROM Accounts WHERE username='".mysqli_real_escape_string($link, $_POST['username'])."' LIMIT 1;";

					$result = mysqli_query($link, $query);

					if (mysqli_num_rows($result) > 0) {
						$error = "That username is taken.";
					} else {
						$query = "INSERT INTO Accounts (username, password, email) VALUES ('".mysqli_real_escape_string($link, $_POST['username'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '".mysqli_real_escape_string($link, $_POST['email'])."');";


						if (!mysqli_query($link, $query)) {
							echo "<p>Could not create an account.</p>";
						} else {
							//$query = "UPDATE Accounts SET password='".md5(md5(mysqli_insert_id()).$_POST['password'])."' WHERE Account_Id=".mysqli_insert_id()." LIMIT 1;"

							//mysqli_query($link, $query);

							$_SESSION['id']=mysqli_insert_id($link);

							//setcookie("id", mysql_insert_id($link);
			
							header("Location: events.php");
						}
					}
				}
				
			} else {
				$query="SELECT * FROM Accounts WHERE username='".mysqli_real_escape_string($link, $_POST['username'])."';";

				$result = mysqli_query($link, $query);

				$row = mysqli_fetch_array($result);

				if (array_key_exists("account_id", $row)) {
					//$hashedPassword = md5(md5($row['Account_Id']).$_POST['password']);

					//if ($hashedPassword == $row['password']) {
					//	$_SESSION['id'] = $row['id'];
					//	
					//	if ($_POST['stayLoggedIn'] == '1') {
					//		setcookie('id', $row['id']);
					//	}
			
					//	header("Location: events.php");
					//}
				}

				if ($_POST['password'] == $row['password']) {
					$_SESSION['id'] = $row['account_id'];

					header("Location: events.php");
				} else {
					$error .= "Username and/or password is incorrect.";
				}
			}
		}
	}
?>

<div id="error"><?php echo $error; ?></div>

<form method="post">
	<input type="username" name="username" placeholder="Username">

	<input type="password" name="password" placeholder="Password">

	<input type="email" name="email" placeholder="E-mail Address">

	<input type="hidden" name="register" value="1">

	<input type="submit" name="submit" value="Create an Account">
</form>

<form method="post">
	<input type="username" name="username" placeholder="Username">

	<input type="password" name="password" placeholder="Password">

	<input type="hidden" name="register" value="0">

	<input type="submit" name="submit" value="Login">
</form>
