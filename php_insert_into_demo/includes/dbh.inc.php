<?php

$dsn = "mysql:host=localhost;dbname=imt_test";
$dbusername = "root";
$dbpassword = "";

// PDO = "Php-Data-Object"
try {
	$pdo = new PDO($dsn, $dbusername, $dbpassword); // Create PDO to use to connect to database
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Change error handling of $pdo
} catch (PDOException $e) { // Use thrown exception
	echo "Connection failed: " . $e->getMessage(); // Echo message of thrown exception
}