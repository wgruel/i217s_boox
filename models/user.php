<?


class User {
  private $id;
  private $username;
  private $password;
  private $email;
  protected $db;

  public function __construct(){
    $this->db = new DBHelper();
  }

  public function setUser($id, $username, $password, $email){
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->email = $email;
  }


  public function create($username, $password, $email){
    $sql_query = "INSERT INTO `user`(`username`, `password`, `email`) ";
    $sql_query .= "VALUES('". $username ."','" . $password . "','" . $email . "')";
    // run query
    $result = $this->db->query($sql_query);

    // returns an array. First argument: true/false indicates if update was
    // successful. Second argument is a message to the user.
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Inserted user " . $username;
    $returnArray[2] = mysqli_insert_id($this->db->conn);
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
      $returnArray[2] = null;
    }
    return $returnArray;
  }

  public function update($id, $username, $password, $email){
    $sql_query = "UPDATE `user` SET `username` = '" . $username . "',
      `password` = '" . $password . "',
      `email` = '" . $email . "'
      WHERE `id` = " . $id;
    // run query
    $result = $this->db->query($sql_query);

    // returns an array. First argument: true/false indicates if update was
    // successful. Second argument is a message to the user.
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Updated user " . $username;
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
    }
    return $returnArray;
  }

  public function delete($id){
    $sql_query="DELETE FROM `user` WHERE `id` = " . $id;
    $result = $this->db->query($sql_query);
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Deleted user " . $id;
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
    }
    return $returnArray;
  }

  public function getUser($id){
    $user = new User();
    $sql_query = "SELECT * FROM `user` WHERE `id` = " . $id;
    $result = $this->db->query($sql_query);
    $row = mysqli_fetch_array($result);
    $user->setUser($row['id'], $row['username'], $row['password'], $row['email']);
    return $user;
  }

  public function getAllUsers(){
    $sql_query = "SELECT * FROM user";
    $result_set = $this->db->query($sql_query);
    $users = array();
    if(mysqli_num_rows($result_set) > 0) {
      while($row = mysqli_fetch_row($result_set)) {
        $user = new User();
        $user->setUser($row[0], $row[1], $row[2], $row[3]);
        $users[] = $user;
      }
    }
    return $users;
  }



  public function getID(){
    return $this->id;
  }
  public function getUsername(){
    return $this->username;
  }
  public function getPassword(){
    return $this->password;
  }
  public function getEmail(){
    return $this->email;
  }


}


?>
