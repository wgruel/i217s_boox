<?php

class DBHelper {
  // specify your own database credentials
  private $host = "localhost";
  private $database = "bookexchange";
  private $user = "root";
  private $password = "";
  public  $conn = false;

  // get the database connection
  public function __construct(){
      $this->conn = mysqli_connect($this->host, $this->user, $this->password)
        or die("Connection error " . mysqli_error());
      mysqli_select_db($this->conn, $this->database);
  }

  public function query($sql){
    $result = mysqli_query($this->conn, $sql);
    if (! $result) {
        // die(mysqli_errror());
    }
    return $result;
  }

}

?>
