<?php
// Check for submit to deny direct access to formhandler
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check for post request receipt
	$usersearch = $_POST["usersearch"];

	try {
		require_once "includes/dbh.inc.php"; // Link to file to gain access to file

		$query = "SELECT * FROM comments WHERE username = :usersearch;"; // Values inserted later

		$stmt = $pdo->prepare($query); // Prepare query for post-submission of data as statement obj

		$stmt->bindParam(":usersearch", $usersearch); // Bind parameter to variable

		$stmt->execute(); // Execute prepared statement obj with assigned args to params

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null; // Close connection to database via pdo
		$stmt = null; // Close connection to statement
	} catch (PDOException $e) {
		die("Query failed: " . $e->getMessage()); // Terminate script with error msg
	}
} else {
	header("Location: ../index.php"); // Route back to index.php on invalid request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP SELECT Demo</title>
</head>
<body>
	<h3>Search results:</h3>

	<section>
		<?php
			if (empty($results)) {
				echo "<div>";
				echo "<p>No results</p>";
				echo "</div>";
			} else {
				// var_dump($results); // Returns results as array
				foreach ($results as $row) {
					echo "<div>";
					echo "<h4>" . htmlspecialchars($row["username"]) . "</h4>";
					echo "<p>" . htmlspecialchars($row["comment_text"]) . "</p>";
					echo "<p>" . htmlspecialchars($row["created_at"]) . "</p>";
					echo "</div>";
				}
			}
		?>
	</section>
</body>
</html>