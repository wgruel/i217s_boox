<?


class User {
  private $id;
  private $username;
  private $password;
  private $email;
  private $isAdmin;
  protected $db;

  public function __construct(){
    $this->db = new DBHelper();
  }

  public function setUser($id, $username, $password, $admin, $email){
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->admin = $admin;
    $this->email = $email;
  }


  public function create($username, $password, $admin, $email){
    $sql_query = "INSERT INTO `user`(`username`, `password`, `admin`, `email`) ";
    $sql_query .= "VALUES('". $username ."','" . $password ."','" . $admin . "','" . $email . "')";
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

  public function update($id, $username, $password, $admin, $email){
    $sql_query = "UPDATE `user` SET `username` = '" . $username . "',
      `password` = '" . $password . "',
      `admin` = '" . $admin . "',
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
    $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email']);
    return $user;
  }

  public function getUserByUserName($username){
    $user = new User();
    $sql_query = "SELECT * FROM `user` WHERE `username` = '" . $username . "'";
    $result = $this->db->query($sql_query);
    $row = mysqli_fetch_array($result);
    $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email']);
    return $user;
  }


  public function getAllUsers(){
    $sql_query = "SELECT * FROM user";
    $result_set = $this->db->query($sql_query);
    $users = array();
    if(mysqli_num_rows($result_set) > 0) {
      while($row = mysqli_fetch_array($result_set)) {
        $user = new User();
        $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email']);
        $users[] = $user;
      }
    }
    return $users;
  }
  /**
    check if user/password match...
    if yes: write user to session...
  */
  public function loginCheck($username, $password){
    $returnArray = array();
    $returnArray[0] = false;
    $returnArray[1] = "";

    $myUser = null;

    if (! empty($username) && ! empty($password) ){

      $myUser = $this::getUserByUserName($username);

      if (! empty($myUser->username) && $password == $myUser->password){
        // in order to make the user available across multiple sites
        // we store the user-object in the session-variable...
        $_SESSION['user'] = $myUser;

        $returnArray[0] = true;
        $returnArray[1] = "User erfolgreich eingeloggt";
        return $returnArray;
      }
      else {
        $returnArray[0] = false;
        $returnArray[1] = "Username und Passwort passen nicht zusammen";
        return $returnArray;
      }

    }
    else {
        $returnArray[0] = false;
        $returnArray[1] = "Username oder Passwort fehlen";
        return $returnArray;
    }

  }

  public function logout(){
    unset($_SESSION['user']);
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
  public function isAdmin(){
    if (isset($this->admin) && $this->admin == true){
      return true;
    }
    else {
      return false;
    }
  }

  public function getEmail(){
    return $this->email;
  }


}


?>
