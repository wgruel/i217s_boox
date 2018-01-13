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
            <th>Location</th>
            <th></th>
            <th></th>
          </tr>
          <?php
            if(is_array($users) && count($users) > 0) {
              // Array to store some map infos...
              $map_info = array();

              foreach ($users as $user) {
                  // We want to display multiple maps, so we need a unique
                  // element where we can put our map
                  // We create that by using a string like "map_" and
                  // adding the ID of the list element (ID is unique)
                  // Another way to store data from the database is shown
                  // in the book-view. There, we store data as an attribute
                  // of a html-tag. Here, we dynamically create Javascript
                  // function calls with the help of the information that
                  // is stored in the PHP array $map_info.
                  $map_div = "map_" . $user->getID();
                  $map_info[] = array(
                    "id" => $map_div,
                    "lat" => $user->getLat(),
                    "lng" => $user->getLng()
                  );

                  // We will use different stylesheets for Admins and Non-Admins
                  $class = "";
                  if ($user->isAdmin()) {
                    $class = "table-danger";
                  }
                ?>
                  <tr <?php echo "class = " . $class; ?>>
                    <td><?php echo $user->getID() ?></td>
                    <td><a href="?controller=user&action=detail&id=<?php echo $user->getID() ?>" ><?php echo $user->getUsername() ?></a></td>
                    <td><?php echo $user->getEmail() ?></td>
                    <td><div id='<?php echo $map_div ?>' style='width: 150px; height: 150px'></div></td>
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
      <script>
      // hier kommen ein paar Map-Funktionen...
      function initMap() {

          var mapOptions = {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            fullscreenControl: false
           }

           // für jedes Element im $map_info Array zeichnen wir jetzt eine eigene Karte
           // dazu erzeugen wir mit Hilfe von PHP auf dem Server verschiedene
           // Javascript-Aufrufe, die dann auf dem Client ausgeführt werden...
           // eine Zeile sieht dann etwa so aus:
           // mapOptions.center = new google.maps.LatLng(51.509865, -0.118092); // London
           // var map_1 = new google.maps.Map(document.getElementById("map_1"), mapOptions);
           <?php
           foreach ($map_info as $mp){
             // wir zeichnen nur eine Karte, wenn Location-Infos hinterlegt sind
             if (!empty($mp['lat']) && !empty($mp['lng'])){
               echo "mapOptions.center = new google.maps.LatLng(" . $mp['lat'] . ", " . $mp['lng'] .");\n";
               echo "var " . $mp['id'] . " = new google.maps.Map(document.getElementById('" . $mp['id'] . "'), mapOptions)\n";
             }
           }
           ?>
       }
      </script>
      <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOfH33yqSm78VVt9COBYIojovNCh0ByVM&callback=initMap">
      </script>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>  </body>
</html>
