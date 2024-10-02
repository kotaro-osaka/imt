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
			$verbindung = mysqli_connect("localhost", "ci3o", "ci3o", "gaestebuch");
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
			Name (Ersteller): <input type="text" name="ersteller" value="<?php echo $Ersteller; ?>" size="15">
			Eintrag: <input type="text" name="eintrag" value="">
			<input type="submit" name="submit" value="Abschicken">
		</form>
	</section>
	<section id="content">
		<h2>Ausgabe der Eintr&auml;ge im ...</h2>
		<?php
		$verbindung = mysqli_connect("localhost", "ci3o", "ci3o");
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
				<article class="Eintrag">
					<pre><?php print_r(mysqli_fetch_array($erg, MYSQLI_ASSOC)); ?>
</pre>
				</article>
		<?php
				$i++;
			}
			mysqli_free_result($erg);
		}
		mysqli_close($verbindung);
		?>
	</section>
	<footer>
		<p>Beispiel einer db-gest&uuml;tzten Webanwendung in PHP mit MySQL/MariaDB</>
	</footer>
</body>

</html>