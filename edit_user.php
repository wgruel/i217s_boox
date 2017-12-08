<?php
require('config.php');

$status = "";



if(isset($_POST['username'])){

  $name = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $ID = $_POST['ID'];

  $stmt = "UPDATE `user` SET `username` = '" . $name . "', `password` = '" . $password . "', `email` = '" . $email . "' WHERE `user`.`id` = " . $ID . ";";

  $result = $link->query($stmt);

  $status = ">> User edited";

  header('Location:index.php');

}
else {
  if (empty($_GET['ID'])){
    die("No ID set!");
  }
  else {
    $ID = $_GET['ID'];
  }

  $stmt = "SELECT * FROM `user` WHERE `ID` = $ID";
  $result = $link->query($stmt);

  if ($result->num_rows > 0){
    while ($row = mysqli_fetch_row($result)){
        $username = $row[1];
        $password = $row[2];
        $email = $row[3];
    }
  }
  else {
    die("user not found");

  }

  $status = ">> noch nichts gesendet";

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

	<script>
	  function replaceUsername(){
		document.getElementById('username').value = "";
	  }
	</script>
</head>
<body>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <h1>Edit User</h1>
			      <h2><?php echo $status ?></h2>
            <input type="hidden" name="ID" value="<?php echo $ID ?>">
            <div class="form-group">
              <label for="username">User-Name:</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $username ?>" onclick="replaceUsername()">
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
