<?php
  if (!is_object($user)){
    die("missing user-object");
  }
  if (empty($action)){
    $action = "save";
    $actionText = "Update User";
  }
?>
<html>
  <head>
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  </head>
  <body>
      <div class="row">
          <div class="col-md-8 col-md-offset-2">

          <h1><?php echo $actionText ?></h1>
          <?php
            if (isset($message) && $message != ""){
              echo "<div class='alert alert-info'>";
              echo $message;
              echo "</div>";
            }
          ?>
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php echo $action ?>">
            <div class="form-group">
              <label for="usr">User-Name:</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->getUsername(); ?>">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="text" class="form-control" id="password" name="password" value="<?php echo $user->getPassword(); ?>">
            </div>
            <div class="form-group">
              <label for="email">E-Mail:</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $user->getEmail(); ?>">
              <input type="hidden" id="id" name="id" value="<?php echo $user->getID(); ?>">
            </div>
            <a class="btn btn-primary" href="index.php" role="button">Cancel</a>

            <button type="submit" class="btn btn-default" name="btn-save"><?php echo $actionText ?></button>

          </form>
        </div>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
