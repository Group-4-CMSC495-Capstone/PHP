<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
	
	session_start();
	//if (array_key_exists("id", $_COOKIE)) {
	//	$_SESSION['id'] = $_COOKIE['id'];
	//}	
	if (array_key_exists("id", $_SESSION)) {
		echo "Session:".$_SESSION['id'];
		echo "<p>Logged in. <a href='index.php?logout=1'>Log Out</a></p>";
	} else {
		header("Location: index.php");
	}
	$error = "";
	if (array_key_exists("submit", $_POST)) {
		$link = mysqli_connect("localhost", "admin", "xxxxxx", "Emailer");
		if (mysqli_connect_error()) {
			die ("Database Connection Error<br>");
		}
		if (!$_POST['event_category']) {
			$error .= "A event name is required.<br>";
		}
		// add more security
		if ($error != "") {
			$error = "<p>There were error(s) on the page:</p>".$error;
		} else {
			$query = "INSERT INTO Events (event_category, event_date, event_time, event_notif_date, event_notif_time, event_desc, event_owner) VALUES ('".mysqli_real_escape_string($link, $_POST['event_category'])."', '".mysqli_real_escape_string($link, $_POST['event_date'])."', '".mysqli_real_escape_string($link, $_POST['event_time'])."', '".mysqli_real_escape_string($link, $_POST['event_not_date'])."', '".mysqli_real_escape_string($link, $_POST['event_not_time'])."', '".mysqli_real_escape_string($link, $_POST['event_desc'])."', '".$_SESSION['id']."');";
			if (!mysqli_query($link, $query)) {
				echo "<p>Could not create an account.</p>";
			} else {
				echo "<p>Event successfully created.</p>";
			}
		}
	}
	
?>

<form method="post">
	<input type="text" name="event_category" placeholder="Event Name">

	<input type="date" name="event_date">

	<input type="time" name="event_time">

	<input type="date" name="event_not_date">

	<input type="time" name="event_not_time">

	<textarea name="event_desc" cols="60" rows="20" maxlength="255">Event Description</textarea>

	<input type="submit" name="submit" value="Create Event">
</form>

<?php
	echo "START";
	
	$link = mysqli_connect("localhost", "admin", "xxxxxxx", "Emailer");
	if (mysqli_connect_error()) {
		die ("Database Connection Error<br>");
	}
	$query="SELECT * FROM Events WHERE event_owner='".$_SESSION['id']."';";
	echo "test:".$query;
	$result = (mysqli_query($link, $query)) or die (mysqli_error($link));
	echo "results:".$result;
	if ($result) {
		while ($row=mysqli_fetch_assoc($result)) {
			echo "Event Name:".$result['event_category'];
			echo "Event Date:".$result['event_date'];
			echo "Event Time:".$result['event_time'];
			echo "Event Description:".$result['event_desc'];
			echo "Event Notification Date:".$result['event_notif_date'];
			echo "Event Notification Time:".$result['event_notif_time'];
		}
	} else {
		echo "NO RESULT";
	}
	echo "END";
?>