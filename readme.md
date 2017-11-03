# Create the backend of our book-exchange-service

The following file describes how to create a bookexchange service. It describes creating the service step-by-step. If you want to build on the state that we discussed during our class, you can download the code on Github: https://github.com/wgruel/i217w_boox

Make sure, you put the files into a subfolder of your document root (see Setup system section). There will an .sql files in that folder - this contains the current database. You can open that file with a normal texteditor (e.g., ATOM or Sublime) and copy the text to the SQL-Tab in PhpMyAdmin AFTER having selected a database (see Setup database section).

## Setup system

Install Xampp on your machine (https://www.apachefriends.org/de/download.html).
The directory that the webserver works with is called "htdocs" (= document root). It is a subdirectory of your Xampp-Installation folder (on Mac "/Applications/XAMPP/htdocs", on Windows ususally something like "C:\xampp\htdocs")

## Setup database

Open PhpMyAdmin on your machine (http://localhost/phpmyadmin/). In the sidebar on the lefthand-side, click "New". Create a database. Give it some name (e.g. "bookexchange" ... I did this on 1blu, you could do this on your machine XAMPP.


### Create DB-Tables

Now, create a user table. Select 'SQL' in the top menu bar and enter the following statement.

```
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


```

### Put in some sample data

You can either go to "Insert" and enter some sample users, or you can use the following statement:

```
INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES (NULL, 'mmueller', '1234', 'mmueller@mail.com'), (NULL, 'smayer', '5678', 'smayer@mail.com');

```
We now have a very basic database that we can work with...


## Create HTML-Pages to deal with objects (Insert, Delete, Update User/Books)

We now want to create PHP-Files that access our database, so we can view, change and delete our books and users from the browser. In order to learn the concepts, we do this in a simplistic way without considering modern architecture patterns...


### Show Users

We want to show all users in our browser. Therefore, we create a new file called index.php. We use a very simple html structure:
```
<html>
  <head>
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  </head>
  <body>
    <h1>Users</h1>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>    
  </body>
</html>

```
I added bootstrap already as we might want to beautify our stuff pretty soon... However, this is not required to make this demo work.

Before doing anything else, we want to connect to the database. In your PHP-file, add:

```
<?php
  $link = mysqli_connect("localhost", "root", "", "i217_boox");
?>
```

`localhost` is the DB-host, `root` is the DB-User, the Password is empty in that case (`""`). The database is called `i217_boox`.

Now, we want to read the users from the database. To read them, we use the statement `SELECT * FROM user`.
```
  $stmt = "SELECT * FROM `user`";
  $result = $link->query($stmt);

  if ($result->num_rows > 0){
    while ($row = mysqli_fetch_row($result)){
      echo $row[1];
      echo $row[3];
    }				
  }
  else {
    // nothing found :-(
  }

```
The SQL-Statement will be executed with the help of the $link-object's query method: We tell this object (a mysqli-object) that we want to execute the statment that is stored in the $stmt variable. We then check, if we get any results. If not, we'll do nothing. If we receive results, we output column 1 and 3 ($row[1] - don't forget: counting starts at 0, we skip the password as we don't want to display that... ). $row is an array that contains all the result-data that is stored in one table row, in this case (the star in the SQL-statement indicates that we want to have all columns), we get id, username, password, email.

What we want to do now, is to just display our used data in a table. So, we can quickly build a table around our loop and apply the bootstrap classes table and table-striped in order to make the table not too ugly...

```
<table class="table-striped table">
  <th>Name</th>
  <th>E-Mail</th>
  <?php
    $link = mysqli_connect("localhost", "root", "", "i217_boox");
    $stmt = "SELECT * FROM `user`";
    $result = $link->query($stmt);

    if ($result->num_rows > 0){
      while ($row = mysqli_fetch_row($result)){
        echo "<tr>\n";
        echo "<td>" . $row[1] . "</td>\n";
        echo "<td>" . $row[2] . "</td>\n";
        echo "</tr>";
      }
    }
    else {
        echo "<tr><td colspan='2'>No data found</td></tr>";
    }
  ?>
</table>

```
 I use the "echo" command to output some HTML-text. The colspan-attribute indicates that we want to have a table-cell that merges three cells....
