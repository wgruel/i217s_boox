<html>
  <head>
    <title>Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <script type="text/javascript">
      function deleteUser(id) {
        if(confirm('Do you really want to delete this book?')) {
            window.location.href='?controller=book&action=delete&id=' + id;
        }
      }
    </script>

  </head>
  <body>
    <?php include('nav.php'); ?>
    <div class="container">
        <div class="col-md-12">

        <h1>Books</h1>
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
            <th>Author</th>
            <th>Title</th>
            <th>OwnerID</th>
            <th>Price</th>
            <th></th>
            <th></th>
          </tr>
          <?php
            if(is_array($books) && count($books) > 0) {
              foreach ($books as $book) {
                  $class = "";
                  if (isset($_SESSION['user']) && $book->isMine($_SESSION['user'])) {
                    $class = "table-primary";
                  }
                ?>
                  <tr <?php echo "class = " . $class; ?>>
                    <td><?php echo $book->getID() ?></td>
                    <td><?php echo $book->getAuthor() ?></td>
                    <td><?php echo $book->getTitle() ?></td>
                    <td><?php echo $book->getOwnerID() ?></td>
                    <td><?php echo $book->getPrice() ?></td>
                    <td>
                      <!-- show edit only if user owner or is admin ... -->
                      <?php if (isset($_SESSION['user']) && ($book->isMine($_SESSION['user']) || $_SESSION['user']->isAdmin())){ ?>
                        <a href="?controller=book&action=edit&id=<?php echo $book->getID() ?>">edit</a>
                      <?php } ?>
                    </td>
                    <td>
                      <!-- show edit only if user is owner or is admin ... -->
                      <?php if (isset($_SESSION['user']) && ($book->isMine($_SESSION['user']) || $_SESSION['user']->isAdmin())){ ?>
                        <a href="javascript:deleteUser('<?php echo $book->getID() ?>')">delete
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
          <?php if (isset($_SESSION['user'])){ ?>
          <div class="float-right">
                <a class="btn btn-primary" href="?controller=book&action=create" role="button">Add Book</a>
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
