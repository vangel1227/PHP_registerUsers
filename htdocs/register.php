<?php // Adds record via INSERT

$page_title = 'Register';
include('includes/header.html');

// Check form submission (first name, last name, email, password, confirmed password)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = []; // Initialize error array

	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = trim($_POST['first_name']);
	}

	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = trim($_POST['last_name']);
	}

	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = trim($_POST['email']);
	}

	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = trim($_POST['pass1']);
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}

	if (empty($errors)) { // If passes, register the user in DB

		require('../mysqli_connect.php'); // Connect DB

		// INSERT query
		$q = "INSERT INTO users (first_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', SHA2('$p', 512), NOW() )";
		$r = @mysqli_query($dbc, $q); // Run query
		if ($r) { // If query passes, print message

			echo '<h1>Thank you!</h1>
		<p>You are now registered.</p><p><br></p>';

		} else { // If fails, print public & debugging message

			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';

		} // End ($r) IF

		mysqli_close($dbc); // Close DB

		// Include footer & quit script
		include('includes/footer.html');
		exit();

	} else { // Report errors

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br>';
		foreach ($errors as $msg) { // Prints each error
			echo " - $msg<br>\n";
		}
		echo '</p><p>Please try again.</p><p><br></p>';

	} // End (empty($errors)) IF

} // End main Submit conditional
?>

<!-- Submission Form -->
<h1>Register</h1>
<form class="row g-3" action="register.php" method="post">
	<div class="col-md-6">
		<label class="form-label">First Name: </label> <input class="form-control" type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>">
	</div>
	<div class="col-md-6">
		<label class="form-label">Last Name: </label> <input class="form-control" type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>">
	</div>
	<div class="col-12">
		<label class="form-label">Email Address: </label> <input class="form-control" type="email" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" >
	</div>
	<div class="col-md-6">
		<label class="form-label">Password: </label> <input  class="form-control"type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" >
	</div>
	<div class="col-md-6">
		<label class="form-label">Confirm Password: </label> <input  class="form-control"type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" >
	</div>
	<div class="col-12">
		<p><input class="btn btn-secondary" type="submit" name="submit" value="Register"></p>
	</div>
</form>
<?php include('includes/footer.html'); ?>
