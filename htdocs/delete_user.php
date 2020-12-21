<?php // For deleting user record only accessible through view_users.php

$page_title = 'Delete a User';
include('includes/header.html');
echo '<h1>Delete a User</h1>';

// Check user ID through GET/POST
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission
	$id = $_POST['id'];
} else { // No valid ID, kill script
	echo '<p class="error">This page has been accessed in error.</p>';
	include('includes/footer.html');
	exit();
}

require('../mysqli_connect.php'); // Connbect DB

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete record

		// DELETE query
		$q = "DELETE FROM users WHERE user_id=$id LIMIT 1";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If passes

			// User deleted message
			echo '<p>The user has been deleted.</p>';

		} else { // Fail message
			echo '<p class="error">The user could not be deleted due to a system error.</p>'; // Public message
			echo '<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>'; // Debugging message
		}

	} else { // No confirmation of deletion
		echo '<p>The user has NOT been deleted.</p>';
	}

} else { // Show form

	// Retrieve the user's info
	$q = "SELECT CONCAT(last_name, ', ', first_name) FROM users WHERE user_id=$id";
	$r = @mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form

		// Get user's info
		$row = mysqli_fetch_array($r, MYSQLI_NUM);

		// Display record being deleted
		echo "<h3>Name: $row[0]</h3>
		Are you sure you want to delete this user?";

		// Create form
		echo '<form action="delete_user.php" method="post">
	<input type="radio" name="sure" value="Yes"> Yes
	<input type="radio" name="sure" value="No" checked="checked"> No
	<input type="submit" name="submit" value="Submit">
	<input type="hidden" name="id" value="' . $id . '">
	</form>';

	} else { // Invalid user ID
		echo '<p class="error">This page has been accessed in error.</p>';
	}
} 

mysqli_close($dbc);

include('includes/footer.html');
?>