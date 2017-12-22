<html>
  <head>
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <script type="text/javascript">
      function deleteUser(id) {
        if(confirm('Do you really want to delete this user?')) {
            window.location.href='?controller=user&action=delete&id=' + id;
        }
      }
    </script>

  </head>
  <body>
    <?php include('nav.php'); ?>
    <div class="container">
        <div class="col-md-12">

        <h1>Users</h1>
        <?php
          if (isset($message) && $message != ""){
            echo "<div class='alert alert-info'>";
            echo $message;
            echo "</div>";
          }
        ?>
        <table class="table table-striped">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>E-Mail</th>
            <th></th>
            <th></th>
          </tr>
          <?php
            if(is_array($users) && count($users) > 0) {
              foreach ($users as $user) {
                  $class = "";
                  if ($user->isAdmin()) {
                    $class = "table-danger";
                  }
                ?>
                  <tr <?php echo "class = " . $class; ?>>
                    <td><?php echo $user->getID() ?></td>
                    <td><a href="?controller=user&action=detail&id=<?php echo $user->getID() ?>" ><?php echo $user->getUsername() ?></a></td>
                    <td><?php echo $user->getEmail() ?></td>
                    <td>
                      <!-- show edit only if user is admin ... -->
                      <?php if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin()){ ?>
                        <a href="?controller=user&action=edit&id=<?php echo $user->getID() ?>">edit</a>
                      <?php } ?>
                    </td>
                    <td>
                      <!-- show edit only if user is admin ... -->
                      <?php if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin()){ ?>
                        <a href="javascript:deleteUser('<?php echo $user->getID() ?>')">delete</a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php
              }
            }
            else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }
          ?>
          </table>
          <?php if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin()){ ?>
          <div class="float-right">
                <a class="btn btn-primary" href="?controller=user&action=create" role="button">Add User</a>
          </div>
          <?php
            }
          ?>
        </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>  </body>
</html>
