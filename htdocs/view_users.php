<?php // Retrieves all records from users table
$page_title = 'View the Current Users';
include('includes/header.html');
echo '<h1>Registered Users</h1>';

require('../mysqli_connect.php'); // Connects to DB

// Records per page:
$display = 10;

// Determine number of pages
if (isset($_GET['p']) && is_numeric($_GET['p'])) { 
	$pages = $_GET['p'];
} else { 
 	// Count records:
	$q = "SELECT COUNT(user_id) FROM users";
	$r = @mysqli_query($dbc, $q);
	$row = @mysqli_fetch_array($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate number of pages...
	if ($records > $display) { 
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} 

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine sort (Default registration date).
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine order:
switch ($sort) {
	case 'ln':
		$order_by = 'last_name ASC';
		break;
	case 'fn':
		$order_by = 'first_name ASC';
		break;
	case 'rd':
		$order_by = 'registration_date ASC';
		break;
	default:
		$order_by = 'registration_date ASC';
		$sort = 'rd';
		break;
}

// Define query:
$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY $order_by LIMIT $start, $display";
$r = @mysqli_query($dbc, $q); 

// Table header HTML
echo '<table class="table table-borderless table-hover" width="60%" align="center">
<thead class="table-dark">
<tr>
	<th align="left"><strong>Edit</strong></th>
	<th align="left"><strong>Delete</strong></th>
	<th align="left"><strong><a href="view_users.php?sort=ln">Last Name</a></strong></th>
	<th align="left"><strong><a href="view_users.php?sort=fn">First Name</a></strong></th>
	<th align="left"><strong><a href="view_users.php?sort=rd">Date Registered</a></strong></th>
</tr>
</thead>
<tbody>
';

// Fetch & print records
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
		<td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
		<td align="left">' . $row['last_name'] . '</td>
		<td align="left">' . $row['first_name'] . '</td>
		<td align="left">' . $row['dr'] . '</td>
	</tr>
	';
}

echo '</tbody></table>';
mysqli_free_result($r);
mysqli_close($dbc);

// Generates links to other pages if needed
if ($pages > 1) {

	echo '<br><p>';
	$current_page = ($start/$display) + 1;

	// Generates previous button when not on first page
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}

	// Generates numbered pages
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End pages loop

	// Generates next button if not on last page
	if ($current_page != $pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	echo '</p>';
} 

include('includes/footer.html');
?>
