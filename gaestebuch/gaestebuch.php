<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8" />
	<meta name="author" content="..." />
	<meta name="description" content="... als Beispiel für eine db-gestützte Webanwendung mit PHP und MySQL/MariaDB" />
	<title>Gästebuch</title>
</head>

<body>
	<header>
		<?php $DatumUhrzeit = date("d.m.Y, H:i:s", time()); ?>
		<p>Datum: <?php echo $DatumUhrzeit; ?></p>
	</header>

	<h1>Elektronisches Gästebuch</h1>
	<p>(db-gestützte Webanwendung mit PHP und MySQL/MariaDB)</p>

	<section id="formular">
		<h2>Formular für einen Eintrag ins Gästebuch</h2>
		<?php
		$action = $_POST['action'] ?? null;
		$EintragID = $_POST['id'] ?? null;
		$Ersteller = $_POST['ersteller'] ?? '';
		$Eintrag = $_POST['eintrag'] ?? '';

		$verbindung = mysqli_connect("localhost", "ci3o", "ci3o", "aito");
		if (!$verbindung) {
			echo "<p>Keine Verbindung möglich!</p>";
			exit;
		}

		if ($action === 'insert' && $Ersteller && $Eintrag) {
			$eingabe = "INSERT INTO gaestebuch (Ersteller, Eintrag) VALUES ('$Ersteller','$Eintrag')";
			mysqli_query($verbindung, $eingabe);
			echo "<p>Eintrag gespeichert.</p>";
		} elseif ($action === 'edit' && $EintragID && $Eintrag) {
			$eingabe = "UPDATE gaestebuch SET Eintrag = '$Eintrag' WHERE EintragID = $EintragID";
			mysqli_query($verbindung, $eingabe);
			echo "<p>Eintrag aktualisiert.</p>";
		} elseif ($action === 'delete' && $EintragID) {
			$eingabe = "DELETE FROM gaestebuch WHERE EintragID = $EintragID";
			mysqli_query($verbindung, $eingabe);
			echo "<p>Eintrag gelöscht.</p>";
		}
		mysqli_close($verbindung);
		?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="action" value="insert">
			Name (Ersteller): <input type="text" name="ersteller" value="<?php echo htmlspecialchars($Ersteller); ?>" size="15">
			Eintrag: <input type="text" name="eintrag" value="">
			<input type="submit" value="Abschicken">
		</form>
	</section>

	<section id="content">
		<h2>Ausgabe der Einträge im Gästebuch</h2>
		<?php
		$verbindung = mysqli_connect("localhost", "ci3o", "ci3o", "aito");
		$abfrage = "SELECT EintragID, Ersteller, Eintrag, Erstelldatum FROM gaestebuch ORDER BY Erstelldatum DESC";
		$erg = mysqli_query($verbindung, $abfrage);
		$anz_eintraege = mysqli_num_rows($erg);

		if ($anz_eintraege == 0) {
			echo "<p>Es wurden keine Daten vom DB-Server zurückgegeben.</p>";
		} else {
			echo "<p>Anzahl Einträge: " . $anz_eintraege . "</p>";
			while ($row = mysqli_fetch_array($erg, MYSQLI_ASSOC)) {
		?>
				<article class="Eintrag">
					<p><strong><?php echo htmlspecialchars($row['Ersteller']); ?>:</strong> <?php echo htmlspecialchars($row['Eintrag']); ?></p>
					<p><small>Erstellt am: <?php echo $row['Erstelldatum']; ?></small></p>

					<!-- Edit Form -->
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:inline;">
						<input type="hidden" name="action" value="edit">
						<input type="hidden" name="id" value="<?php echo $row['EintragID']; ?>">
						<input type="text" name="eintrag" value="<?php echo htmlspecialchars($row['Eintrag']); ?>">
						<input type="submit" value="Bearbeiten">
					</form>

					<!-- Delete Form -->
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display:inline;" onsubmit="return confirm('Eintrag wirklich löschen?');">
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo $row['EintragID']; ?>">
						<input type="submit" value="Löschen">
					</form>
				</article>
		<?php
			}
			mysqli_free_result($erg);
		}
		mysqli_close($verbindung);
		?>
	</section>

	<footer>
		<p>Beispiel einer db-gestützten Webanwendung in PHP mit MySQL/MariaDB</p>
	</footer>
</body>

</html>