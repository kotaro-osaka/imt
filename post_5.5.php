<?php
$dsn = "mysql:host=localhost;dbname=ci3o;charset=utf8";
$dbusername = "ci3o";
$dbpassword = "ci3o";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Verbindung fehlgeschlagen: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
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
    <p>(db-gestützte Webanwendung mit PHP und MySQL/MariaDB)</p>

    <section id="formular">
        <h2>Formular für einen Eintrag ins ...</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $Ersteller = $_POST['ersteller'];
            $Eintrag = $_POST['eintrag'];

            if (!empty($Ersteller) && !empty($Eintrag)) {
                $stmt = $pdo->prepare("INSERT INTO gaestebuch (Ersteller, Eintrag) VALUES (:ersteller, :eintrag)");
                $stmt->bindParam(':ersteller', $Ersteller);
                $stmt->bindParam(':eintrag', $Eintrag);

                if ($stmt->execute()) {
                    echo "<p>Ihre Daten von der IP " . $_SERVER['REMOTE_ADDR'] . " wurden abgeschickt! Vielen Dank!</p>";
                    echo "<p>Ihr nächster Eintrag:</p>";
                } else {
                    echo "<p>Fehler beim Absenden des Eintrags!</p>";
                }
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            Name (Ersteller): <input type="text" name="ersteller" value="" size="15">
            Eintrag: <input type="text" name="eintrag" value="">
            <input type="submit" name="submit" value="Abschicken">
        </form>
    </section>

    <section id="content">
        <h2>Ausgabe der Einträge im ...</h2>
        <?php
        $stmt = $pdo->query("SELECT EintragID, Ersteller, Eintrag, Erstelldatum FROM gaestebuch ORDER BY Erstelldatum DESC");
        $eintraege = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($eintraege)) {
            echo "<p>Es wurde kein Datensatz vom DB-Server zurückgegeben</p>";
        } else {
            echo "<p>Anzahl Einträge: " . count($eintraege) . "</p>";
            foreach ($eintraege as $eintrag) {
                echo "<article class='Eintrag'><pre>";
                print_r($eintrag);
                echo "</pre></article>";
            }
        }
        ?>
    </section>

    <footer>
        <p>Beispiel einer db-gestützten Webanwendung in PHP mit MySQL/MariaDB</p>
    </footer>
</body>
</html>