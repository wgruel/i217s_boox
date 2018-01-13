<html>
  <head>
    <title>Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <script type="text/javascript">
      /*

        This function opens a popup-window to ask the user if she really wants to delete a certain book.
        If the user confirms, the function calls the book controller and asks it to delete the user with the
        given id. (action=delete)

      */
      function deleteUser(id) {
        if(confirm('Do you really want to delete this book?')) {
            window.location.href='?controller=book&action=delete&id=' + id;
        }
      }

      /*

        Loads covers from a remote server...
        In class, we used the booknomads.com API. Unfortunately, the service
        only has very few book covers, so I changed the API to the
        Google Books API.

      */
      function loadCover(isbn){
        // if isbn is not provided, we don't do anything and just show
        // the default image ...
        if (isbn != "" &&  isbn != 0){
          // When the function was called, we display a loading icon...
          // it will be replaced as soon as we get data back...
          var elem = document.getElementById("pic_" + isbn);
          elem.setAttribute("src", "views/img/loadingIcon.gif");


          // Create new XMLHttpRequest
          var xhttp = new XMLHttpRequest();

          // Whenever the State of the Object changes...
          xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {
              // default picture: Picture not Found
              // if a picture is found, we will replace that pic...
              pic = "views/img/notFound.gif";

              myObj = JSON.parse(xhttp.responseText);
              // if you want to check the results of the API calls,
              // uncomment the followint two lines
              // console.log(xhttp.responseText);
              // console.log(myObj);

              // In returning JSON object, the number of covers is
              // returned in the property totalItems. If the number
              // is 0, we keep the notFound image. If not, we replace
              // the picture with the thumbnail picture that is
              // provided by the API
              if (myObj.totalItems != 0){
                pic = myObj.items[0].volumeInfo.imageLinks.smallThumbnail
              }
              // now, we get the image element that we want to put
              // our image to...
              elem = document.getElementById("pic_" + isbn);
              // and change the src attribute (link it to whatever we have
              // stored in the pic variable)
              elem.setAttribute("src", pic);
            }
          };
          // Open and send GET request to the API
          // Make sure, parameter ISBN is set...
          xhttp.open("GET", "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn, true);
          xhttp.send();

        }
      }

      function loadCovers(){
          // we get all elements with the class "coverimage"
          // pics is an array
          pics = document.getElementsByClassName('coverimage');
          // we loop through all the elements
          // length is an attribute of the pics array - it knows how many
          // elements are in the array...
          // so, we start with 0 and do some stuff for every element in the
          // array
          for (i = 0; i < pics.length; i++) {
              // we get the isbn-number
              isbn = pics[i].getAttribute("data-isbn");
              // we then call the loadCover function to load the covers
              // and replace the picture...
              loadCover(isbn);
          }


      }

    </script>

  </head>
  <body>
    <?php include('nav.php'); ?>
    <div class="container">
        <div class="col-md-12">
        <p style="margin-top: 10px;">
          <button onclick = "loadCovers()">Cover laden</button>
          Load covers (this section can be removed and called whenever the page is loaded)
        </p>
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
            <th>Cover</th>
            <th>OwnerID</th>
            <th>Price</th>
            <th></th>
            <th></th>
          </tr>
          <?php
            $isbn = array(9789000035526, 9789000035526);

            if(is_array($books) && count($books) > 0) {
              foreach ($books as $book) {
                  // hack to simulate ISBN numbers

                  $class = "";
                  if (isset($_SESSION['user']) && $book->isMine($_SESSION['user'])) {
                    $class = "table-primary";
                  }
                ?>
                  <tr>
                    <td><?php echo $book->getID() ?></td>
                    <td><?php echo $book->getAuthor() ?></td>
                    <td><?php echo $book->getTitle() ?></td>
                    <!--
                      We use the class "coverimage" in order to loop throug all
                      the images later....
                      The data-attribute is an attribute that is used to store
                      custom data private to the page or application.
                      So you can define your own data and store it in HTML-tags...
                      Data elements have to start with "data-".
                    -->
                    <td><img id="pic_<?php echo $book->getISBN() ?>" class="coverimage" data-isbn="<?php echo $book->getISBN() ?>" src ="views/img/defaultCover.gif"/><br>
                    </td>
                    <td><?php echo $book->getOwnerID() ?></td>
                    <td><?php echo $book->getPrice() ?></td>
                    <td>
                      <!-- show edit only if user owner or is admin ... -->
                      <?php if (isset($_SESSION['user']) && ($book->isMine($_SESSION['user']) || $_SESSION['user']->isAdmin())){ ?>
                        <a href="?controller=book&action=edit&id=<?php echo $book->getID('9789000010134') ?>">edit</a>
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
