<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="..." />
	<meta name="author" content="..." />
	<meta name="description" content="... als Beispiel für eine db-gestützte Webanwendung mit PHP und MySQL/MariaDB" />
	<title>...</title>
</head>

<body>
	<header>
		<?php
		$DatumUhrzeit = date("d.m.Y, H:i:s", time());
		?>
		<p>Datum: <?php echo $DatumUhrzeit; ?></p>
	</header>
	<h1>Elektronisches ...</h1>
	<p>(db-gest&uuml;tzte Webanwendung mit PHP und MySQL/MariaDB)</p>
	<section id="formular">
		<h2>Formular f&uuml;r einen Eintrag ins ...</h2>
		<?php
		$Submit = $_POST['submit'];
		$Ersteller = $_POST['ersteller'];
		$Eintrag = $_POST['eintrag'];
		if ($Submit && ($Ersteller != "") && ($Eintrag != "")) {
			$verbindung = mysql_connect("localhost", "ci3o", "ci3o");
			if (!$verbindung) {
				echo "Keine Verbindung möglich!\n";
				exit;
			}
			$eingabe = "INSERT INTO gaestebuch (Ersteller, Eintrag) VALUES ('$Ersteller','$Eintrag')";
			$erg = mysql_db_query("aito", $eingabe, $verbindung);
			echo "<p>Ihre Daten von der IP " . $_SERVER['REMOTE_ADDR'] . " wurden abgeschickt! Vielen Dank!</p>";
			mysql_close($verbindung);
			echo "<p>Ihr n&auml;chster Eintrag:</p>";
		}
		?>
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
					<pre><?php print_r(mysql_fetch_array($erg, MYSQL_ASSOC)); ?>
</pre>
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