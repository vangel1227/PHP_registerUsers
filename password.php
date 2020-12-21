<?php // Page lets user change password

$page_title = 'Change Password';
include('includes/header.html');

// Check form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require('../mysqli_connect.php'); // Connect DB
		$errors = []; // Initialize error array

	// Check email
	if (empty($_POST['email'])) {
		$errors[] = 'Email address missing.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	// Check current password
	if (empty($_POST['pass'])) {
		$errors[] = 'Current password missing.';
	} else {
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}

	// Check new password entry and match against confirmed password
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'New password and confirmed password did not match.';
		} else {
			$np = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'Forgot to enter new password.';
	}

	if (empty($errors)) { // If passes

		// Check email address/password
		$q = "SELECT user_id FROM users WHERE (email='$e' AND pass=SHA2('$p', 512) )";
		$r = @mysqli_query($dbc, $q);
		$num = @mysqli_num_rows($r);
		if ($num == 1) { // Match made

			// Get user_id
			$row = mysqli_fetch_array($r, MYSQLI_NUM);

			// UPDATE query
			$q = "UPDATE users SET pass=SHA2('$np', 512) WHERE user_id=$row[0]";
			$r = @mysqli_query($dbc, $q);

			if (mysqli_affected_rows($dbc) == 1) { // If passes

				// Success message
				echo '<h1>Thank you!</h1>
				<p>Your password has been updated. In Chapter 12 you will actually be able to log in!</p><p><br></p>';

			} else { // If UPDATE doesn't work

				// Failed message
				echo '<h1>System Error</h1>
				<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>';

				// Debugging message
				echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';

			}

			mysqli_close($dbc); // Close DB

			// Page footer & quit script to not show form again
			include('includes/footer.html');
			exit();

		} else { // Invalid email address/password
			echo '<h1>Error!</h1>
			<p class="error">The email address and password do not match those on file.</p>';
		}

	} else { // Report errors

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br>';
		foreach ($errors as $msg) { // Print each error
			echo " - $msg<br>\n";
		}
		echo '</p><p>Please try again.</p><p><br></p>';

	} 

	mysqli_close($dbc); // Close DB

} // End main Submit conditional
?>
<!-- Change password form -->
<h1>Change Password</h1>
<form class="row g-3" action="password.php" method="post">
<div class="col-md-6">
	<label class="form-label">Email Address: </label><input class="form-control" type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" > 
</div>
<div class="col-md-6">
	<label class="form-label">Current Password: </label><input class="form-control" type="password" name="pass" size="10" maxlength="20" value="<?php if (isset($_POST['pass'])) echo $_POST['pass']; ?>" >
</div>
<div class="col-md-6">
	<label class="form-label">New Password: </label><input class="form-control" type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" >
</div>
<div class="col-md-6">
	<label class="form-label">Confirm New Password: </label><input class="form-control" type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" >
</div>
	<p><input class="btn btn-secondary" type="submit" name="submit" value="Change Password"></p>
</form>
<?php include('includes/footer.html'); ?>