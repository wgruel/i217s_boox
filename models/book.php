<?


class Book {
  private $id;
  private $title;
  private $author;
  private $ownerID;
  private $price;
  private $isbn;
  protected static $db;

  public function __construct(){
    self::$db = new DBHelper();
  }

  public function setBook($id, $author, $title, $ownerID, $price, $isbn){
    $this->id = $id;
    $this->title = $title;
    $this->author = $author;
    $this->ownerID = $ownerID;
    $this->price = $price;
    $this->isbn = $isbn;
  }


  public function create($author, $title, $ownerID, $price, $isbn){
    $sql_query = "INSERT INTO `books`(`owner_id`, `author`, `title`, `price`, `isbn`) ";
    $sql_query .= "VALUES('" . $ownerID ."','" . $author . "','" . $title . "','" . $price . "', '" . $isbn . "')";
    // run query
    $result = self::$db->query($sql_query);
    $errorText = mysqli_error(self::$db->conn);

    // returns an array. First argument: true/false indicates if update was
    // successful. Second argument is a message to the user.
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Inserted book " . $title;
    $returnArray[2] = mysqli_insert_id(self::$db->conn);
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
      $returnArray[2] = null;
    }
    return $returnArray;
  }

  public function update($id, $author, $title, $ownerID, $price, $isbn){
    $sql_query = "UPDATE `books` SET `title` = '" . $title . "',
      `author` = '" . $author . "',
      `owner_id` = '" . $ownerID . "',
      `price` = '" . $price . "',
      `isbn` = '" . $isbn . "'
      WHERE `id` = " . $id;
    // run query
    $result = self::$db->query($sql_query);
    $errorText = mysqli_error(self::$db->conn);

    // returns an array. First argument: true/false indicates if update was
    // successful. Second argument is a message to the user.
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Updated book " . $title;
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
    }
    return $returnArray;
  }

  public function delete($id){
    $sql_query="DELETE FROM `books` WHERE `id` = " . $id;
    $result = self::$db->query($sql_query);
    $errorText = mysqli_error(self::$db->conn);

    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Deleted book " . $id;
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
    }
    return $returnArray;
  }

  public static function getBook($id){
    $book = new Book();
    $sql_query = "SELECT * FROM `books` WHERE `id` = " . $id;
    $result = self::$db->query($sql_query);
    $row = mysqli_fetch_array($result);
    $book->setBook($row['id'], $row['author'], $row['title'], $row['owner_id'], $row['price'], $row['isbn']);
    return $book;
  }


  public static function getAllBooks(){
    $sql_query = "SELECT * FROM books";
    $result_set = self::$db->query($sql_query);
    $books = array();
    if(mysqli_num_rows($result_set) > 0) {
      while($row = mysqli_fetch_array($result_set)) {
        $book = new Book();
        $book->setBook($row['id'], $row['author'], $row['title'], $row['owner_id'], $row['price'], $row['isbn']);
        $books[] = $book;
      }
    }

    return $books;
  }

  public function getID(){
    return $this->id;
  }
  public function getOwnerID(){
    return $this->ownerID;
  }
  public function getTitle(){
    return $this->title;
  }
  public function getAuthor(){
    return $this->author;
  }
  public function getPrice(){
    return $this->price;
  }
  public function getISBN(){
    return $this->isbn;
  }

  public function isMine($user){
    if ($user->getID() == $this->ownerID){
      return true;
    }
    else {
      return false;
    }
  }


}


?>
