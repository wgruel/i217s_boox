<html>
  <head>
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script type="text/javascript">
      function deleteUser(id) {
        if(confirm('Do you really want to delete this user?')) {
            window.location.href='?controller=user&action=delete&id=' + id;
        }
      }
    </script>
  </head>
  <body>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

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
                ?>
                  <tr>
                    <td><?php echo $user->getID() ?></td>
                    <td><?php echo $user->getUsername() ?></td>
                    <td><?php echo $user->getEmail() ?></td>
                    <td><a href="?controller=user&action=edit&id=<?php echo $user->getID() ?>">edit</a></td>
                    <td><a href="javascript:deleteUser('<?php echo $user->getID() ?>')">delete</td>
                  </tr>
                <?php
              }
            }
            else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }
          ?>
            <tr>
              <td colspan="3">
              </td>
              <td colspan="2" style="text-align: center">
                <a class="btn btn-primary" href="?controller=user&action=create" role="button">Add User</a>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
