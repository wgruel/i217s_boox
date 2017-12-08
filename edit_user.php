<?php

// Database connection herstellen.
require('config.php');

/*
Variable $status initialisieren - hier können wir nachher Ausgaben
reinschreiben, die dem User erklären, was passiert ist (z.B. Edit erfolgreich,
Fehlermeldungen...
)
*/
$status = "";

/*
  Wir unterscheiden zwischen zwei Zuständen für das Skript:
  1) Es wurde per GET von einer Seite aufgerufen (z.B. von index.php):
  Es soll zunächst der zu ändernde Datensatz angezeigt werden.
  2) Daten wurden per POST zur Änderung aufgerufen:
  Die per POST übermittelten Daten sollen in der DB gespeichert werden.
*/

// Seite aufgerufen und ein GET-Parameter namens ID übergeben
// Aufruf bspw. edit_user.php?ID=1
if (! empty($_GET['ID'])){

  $ID = $_GET['ID'];
}

// Formulardaten wurden per POST übermittelt
else if (! empty($_POST['ID'])){
  // Daten für das Update in Variablen schreiben.
  // Hier kann ggf. Errorhandling und Encoding erfolgen
  // Beispielhaft für username implementiert
  if (empty ($_POST['username'])){
      die("Username muss gesetzt sein...");
  }
  else {
    $name = $_POST['username'];
  }

  $password = $_POST['password'];
  $email = $_POST['email'];
  $ID = $_POST['ID'];

  // Updatestatement zusammenbauen...
  $stmt = "UPDATE `user` SET `username` = '" . $name . "', `password` = '" . $password . "', `email` = '" . $email . "' WHERE `user`.`id` = " . $ID . ";";
  // ... und ausführen
  $result = $link->query($stmt);


  // Wenn alles erfolgreich war
  if ($result){
    $status = ">> User " . $name . " (ID: " . $ID . ") edited";
    // andere Option: Weiterleiten auf die Startseite...
    // ACHTUNG: damit das klappt, darf noch kein Text erzeugt worden sein
    // d.h. es darf kein HTML- oder sonstiger Output vor diesem
    // Statement ausgegeben werden!
    // header('Location:index.php');
  }
}
// ID wurde nicht gesetzt --> Fehler!
else {
    die("No ID set!");
}

// Egal, was passiert, wir wissen, dass wir eine ID erhalten haben
// und können folglich den entsprechenden Datensatz aus der DB
// holen...
// Statement zum Raussuchen des Datensatzes mit der ID $ID aus der DB
$stmt = "SELECT * FROM `user` WHERE `ID` = $ID";
// Statement abschicken
$result = $link->query($stmt);

// Wenn Daten vorhanden, Daten in das Array $row schreiben
if ($result->num_rows > 0){
  while ($row = mysqli_fetch_row($result)){
      $username = $row[1];
      $password = $row[2];
      $email = $row[3];
  }
}
// Datensatz wurde nicht gefunden...
else {
  die("User mit ID " . $ID . " not found");
}


?>
<!doctype html>
<html lang="de">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

</head>
<body>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <h1>Edit User</h1>
            <!-- hier können wir einen Status ausgeben, der dem User weiter hilft...   -->
			      <h2><?php echo $status ?></h2>
            <!--
              DIE NÄCHSTE ZEILE IST ZENTRAL!!!
              Hier übergeben wir die ID des Datensatzes, um den es geht.
              Wenn die Seite mit dem GET-Parameter ID
              aufgerufen wurde, schreiben wir hier die ID des zu ändernden
              Datensatzes rein. Diese ID wird dann beim Übermitteln des
              Formulars wieder mitgeschickt. D.h. beim Empfang der Formular-
              Daten ist immer noch bekannt, welcher Datensatz geändert
              werden soll (daraus wird der "WHERE ID = 3" Teil der
              SQL-Statements...)
              Wenn die Seite nach dem Editieren wieder aufgerufen wird,
              schreiben wir hier die ID des gerade geänderten (und evtl.
              erneut zu ändernden) Datensatzes rein.
              Es handelt sich um ein verstecktes Textfeld (type = "hidden")
              Dh. wir können Daten übertragen, ohne dass der User das im
              Frontend sieht...
            -->
            <input type="hidden" name="ID" value="<?php echo $ID ?>">
            <div class="form-group">
              <label for="username">User-Name:</label>
              <!--
                über &lt;?php echo $username ?&gt; schreiben wir die vorher aus der
                DB gezogenen Daten in das Textfeld - wir füllen also das
                Formular schon mal mit bestehenden Daten...
              -->
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $username ?>">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" id="password" name="password" value = "<?php echo $password ?>">
            </div>
            <div class="form-group">
              <label for="email">E-Mail:</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
            </div>
            <button type="submit" class="btn btn-default" name="btn-save">Edit User</button>

          </form>

          </div>
        </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
