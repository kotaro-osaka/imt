<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="utf8" />
	<meta name="author" content="kotaro" />
	<meta name="description" content="... als Beispiel für eine db-gestützte Webanwendung mit PHP und MySQL/MariaDB" />
	<title>Gaestebuch</title>
</head>

<body>
	<header>
		<?php
		$DatumUhrzeit = date("d.m.Y, H:i:s", time());
		?>
		<p>Datum: <?php echo $DatumUhrzeit; ?></p>
	</header>
	<h1>Elektronisches Gästebuch</h1>
	<p>(DB-gestützte Webanwendung mit PHP und MySQL/MariaDB)</p>
	<section id="formular">
		<h2>Formular für einen Eintrag ins Gaestebuch</h2>
		<?php
		$Submit = $_POST['submit'] ?? null; // Ohne null-safety
		$Ersteller = $_POST['ersteller'] ?? ''; // Ohne null-safety
		$Eintrag = $_POST['eintrag'] ?? ''; // Ohne null-safety
		if ($Submit && ($Ersteller != "") && ($Eintrag != "")) {
			$verbindung = mysqli_connect("localhost", "ci3o", "ci3o", "aito");
			if (!$verbindung) {
				echo "Keine Verbindung möglich!\n";
				exit;
			}
			$eingabe = "INSERT INTO gaestebuch (Ersteller, Eintrag) VALUES ('$Ersteller','$Eintrag')";
			$erg = mysqli_query($verbindung, $eingabe);
			echo "<p>Ihre Daten von der IP " . $_SERVER['REMOTE_ADDR'] . " wurden abgeschickt! Vielen Dank!</p>";
			mysqli_close($verbindung);
			echo "<p>Ihr n&auml;chster Eintrag:</p>";
		}
		?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<label for="ersteller">Name (Ersteller):</label><input type="text" name="ersteller" value="<?php echo $Ersteller; ?>" size="15">
			Eintrag: <input type="text" name="eintrag" value="">
			<input type="submit" name="submit" value="Abschicken">
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
			echo "<p> Es wurde kein Datensatz vom DB-Server zurückgegeben </p>";
		} else {
			echo "<p> Anzahl Eintr&auml;ge: " . $anz_eintraege . " </p>";
			$i = 0;
			while ($i < $anz_eintraege) {
		?>
		<pre>
		<?php
		while ($row = mysqli_fetch_array($erg, MYSQLI_ASSOC)) {
			echo '<article class="Eintrag">';
			echo '<p><strong>Ersteller:</strong> ' . htmlspecialchars($row['Ersteller']) . '</p>';
			echo '<p><strong>Eintrag:</strong> ' . htmlspecialchars($row['Eintrag']) . '</p>';
			echo '<p><strong>Erstelldatum:</strong> ' . htmlspecialchars($row['Erstelldatum']) . '</p>';
			echo '</article>';
			echo '<hr>';
		}
		?>
		</pre>
		<?php
				$i++;
			}
			mysqli_free_result($erg);
		}
		mysqli_close($verbindung);
		?>
	</section>
	<footer>
		<p>Beispiel einer db-gestützten Webanwendung in PHP mit MySQL/MariaDB</>
	</footer>
</body>

</html>