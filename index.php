<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
  <body>
  	<h1>Users</h1>
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
					echo "<td>" . $row[3] . "</td>\n";
					echo "</tr>";
				}
			}
      else {
          echo "<tr><td colspan='2'>No data found</td></tr>";
      }
		?>
	</table>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
