<?php

// Check for submit to deny direct access to formhandler
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check for post request receipt
	$username = $_POST["username"];
	$pwd = $_POST["pwd"];
	$email = $_POST["email"];

	try {
		require_once "dbh.inc.php"; // Link to file to gain access to file

		$query = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email);"; // Values inserted later

		$stmt = $pdo->prepare($query); // Prepare query for post-submission of data as statement obj

		$stmt->bindParam(":username", $username); // Bind parameter to variable
		$stmt->bindParam(":pwd", $pwd); // Bind parameter to variable
		$stmt->bindParam(":email", $email); // Bind parameter to variable

		$stmt->execute(); // Execute prepared statement obj with assigned args to params

		$pdo = null; // Close connection to database via pdo
		$stmt = null; // Close connection to statement

		header("Location: ../index.php"); // Route user back to main page

		die(); // Use `exit()` when no connection running, else `die()`
	} catch (PDOException $e) {
		die("Query failed: " . $e->getMessage()); // Terminate script with error msg
	}
} else {
	header("Location: ../index.php"); // Route back to index.php on invalid request
}

// Best practice:
// Data sanitization: `htmlspecialchars(output to browser)`