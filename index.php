<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="..." />
	<meta name="author" content="..." />
	<meta name="description" content="... als Beispiel für eine db-gestützte Webanwendung mit PHP und MySQL/MariaDB" />
	<title>...</title>
</head>
<!-- mysql_*-Funktionen werden nicht mehr verwendet, da sie ab PHP 5.5.0 veraltet sind und in PHP 7.0.0 vollständig entfernt wurden. -->
<body>
	<header>
		<?php
		$DatumUhrzeit = date("d.m.Y, H:i:s", time()); // Aktuelle Uhrzeit
		?>
		<!-- Darstellung der aktuellen Uhrzeit -->
		<p>Datum: <?php echo $DatumUhrzeit; ?></p>
	</header>
	<h1>Elektronisches ...</h1>
	<p>(db-gest&uuml;tzte Webanwendung mit PHP und MySQL/MariaDB)</p>
	<section id="formular">
		<h2>Formular f&uuml;r einen Eintrag ins ...</h2>
		<?php
		// Bei POST requests werden Daten aus Submit-Button "submit" und Inputs "ersteller" & "eintrag" in dazugehörigen Variablen gespeichert
		$Submit = $_POST['submit'];
		$Ersteller = $_POST['ersteller'];
		$Eintrag = $_POST['eintrag'];
		// Wenn "Submit == true" und Daten in Inputs nicht leere Strings
		if ($Submit && ($Ersteller != "") && ($Eintrag != "")) {
			$verbindung = mysql_connect("localhost", "ci3o", "ci3o"); // Verbindung mit MySQL Server herstellen (username: "ci3o", password: "ci3o")
			// Wenn Verbinung nicht hergestellt werden kann
			if (!$verbindung) {
				echo "Keine Verbindung möglich!\n"; // String auf Website darstellen
				exit; // Datenbankverbindungsversuch abbrechen
			}
			$eingabe = "INSERT INTO gaestebuch (Ersteller, Eintrag) VALUES ('$Ersteller','$Eintrag')"; // SQL-Abfrage
			$erg = mysql_db_query("aito", $eingabe, $verbindung); // Resultat aus SQL-Abfrage "eingabe" auf Datenbank "aito" durch bestehende Verbindung in "verbindung"
			echo "<p>Ihre Daten von der IP " . $_SERVER['REMOTE_ADDR'] . " wurden abgeschickt! Vielen Dank!</p>"; // String mit IP Adresse des Clients wird auf Website dargestellt 
			mysql_close($verbindung); // Verbindung zu Datenbank schließen
			echo "<p>Ihr n&auml;chster Eintrag:</p>"; // String auf Website darstellen
		}
		?>
		<!-- Dateiname des derzeit ausgefuehrten Skript -->
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			Name (Ersteller): <input type="text" name="ersteller" value="<?php echo $Ersteller; ?>" size="15">
			Eintrag: <input type="text" name="eintrag" value="">
			<input type="submit" name="submit" value="Abschicken">
		</form>
	</section>
	<section id="content">
		<h2>Ausgabe der Eintr&auml;ge im ...</h2>
		<?php
		$verbindung = mysql_connect("localhost", "ci3o", "ci3o");
		$abfrage = "SELECT EintragID, Ersteller, Eintrag, Erstelldatum FROM gaestebuch ORDER BY Erstelldatum DESC";
		$erg = mysql_db_query("ci3o", $abfrage, $verbindung);
		$anz_eintraege = mysql_num_rows($erg);
		if ($anz_eintraege == 0) {
			echo "<p> Es wurde kein Datensatz vom DB-Server zurückgegeben </p>";
		} else {
			echo "<p> Anzahl Eintr&auml;ge: " . $anz_eintraege . " </p>";
			$i = 0;
			while ($i < $anz_eintraege) {
		?>
				<article class="Eintrag">
					<pre><?php print_r(mysql_fetch_array($erg, MYSQL_ASSOC)); ?></pre>
				</article>
		<?php
				$i++;
			}
			mysql_free_result($erg);
		}
		mysql_close($verbindung);
		?>
	</section>
	<footer>
		<p>Beispiel einer db-gest&uuml;tzten Webanwendung in PHP mit MySQL/MariaDB</>
	</footer>
</body>

</html>