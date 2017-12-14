<?php
  if (!is_object($user)){
    die("missing user-object");
  }

  $action = "login";
  $actionText = "Login";

?>
<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
  <body>
      <?php include('nav.php'); ?>
      <div class="container">
          <div class="col-md-12">

          <h1><?php echo $actionText ?></h1>
          <?php
            if (isset($message) && $message != ""){
              echo "<div class='alert alert-info'>";
              echo $message;
              echo "</div>";
            }
          ?>
          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=loginCheck">
            <div class="form-group">
              <label for="usr">User-Name:</label>
              <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->getUsername(); ?>">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="text" class="form-control" id="password" name="password" value="<?php echo $user->getPassword(); ?>">
            </div>
            <a class="btn btn-default" href="index.php" role="button">Cancel</a>
            <button type="submit" class="btn btn-primary" name="btn-save"><?php echo $actionText ?></button>
          </form>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>  </body>
  </body>
</html>
