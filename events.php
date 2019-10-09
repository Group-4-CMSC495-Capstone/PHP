<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
	// Session use
	session_start();

	//if (array_key_exists("id", $_COOKIE)) {
	//	$_SESSION['id'] = $_COOKIE['id'];
	//}	

	// If there is an id session, display login. Else send back to mainpage.
	if (array_key_exists("id", $_SESSION)) {
		echo "<p>Logged in. <a href='index.php?logout=1'>Log Out</a></p>";
	} else {
		header("Location: index.php");
	}

	// Error message variable
	$error = "";

	// If there are submit contents in the POST
	if (array_key_exists("submit", $_POST)) {

		$link = mysqli_connect("localhost", "admin", "xxxxxx", "Emailer");

		if (mysqli_connect_error()) {
			die ("Database Connection Error<br>");
		}

		// If there are deleteevent in the POST, it will delete that event from the DB
		if ($_POST['deleteevent']) {
			$query = "DELETE FROM Events WHERE event_id='".$_POST['deleteevent']."';";

			if (mysqli_query($link, $query)) {
				echo "Event ".$_POST['deleteevent']." successfully deleted.";
			} else {
				echo "There was an error deleting the event.";
			}

		} else {

			// Since there was no deleteevent, we are creating event.
			// Error message if there is no event category
			if (!$_POST['event_category']) {
				$error .= "A event name is required.<br>";
			}

			// add more security

			// Display error message if there is one, if not create the event into the DB
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

		mysqli_close($link);
	}
	

?>

<!-- INPUT FORM FOR CREATING THE EVENTS -->
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
	/*
	Post event creation input, displays all events that are owned by the session logged in.
	Displays a button for each event that allows for deletion of that specific event.
	*/
	$link = mysqli_connect("localhost", "admin", "xxxxxxx", "Emailer");

	if (mysqli_connect_error()) {
		die ("Database Connection Error<br>");
	}

	$query="SELECT * FROM Events WHERE event_owner='".$_SESSION['id']."';";

	$result = mysqli_query($link, $query);
	
	// Loop results of all events with samee account ID and display all events.
	if ($result) {
		while ($row=mysqli_fetch_assoc($result)) {
			echo "Event Name:".$row['event_category']."<br>";
			echo "Event Date:".$row['event_date']."<br>";
			echo "Event Time:".$row['event_time']."<br>";
			echo "Event Description:".$row['event_desc']."<br>";
			echo "Event Notification Date:".$row['event_notif_date']."<br>";
			echo "Event Notification Time:".$row['event_notif_time']."<br>";
			echo "<form method='post'>";
			echo "<input type='hidden' name='deleteevent' value='".$row['event_id']."'>";
			echo "<input type='submit' name='submit' value='Delete Event'>";
			echo "</form><br>";
		}
	} else {
		echo "NO RESULT";
	}

	mysqli_close($link);

?>
