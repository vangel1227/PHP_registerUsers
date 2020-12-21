<?php

// Sets database access info as constants, enter your own information if different
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'sitename');

// Connects to Database:
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect to MySQL: ' . mysqli_connect_error() );

// Sets encoding
mysqli_set_charset($dbc, 'utf8');