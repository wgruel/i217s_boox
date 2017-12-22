<?php
/**

  The user-model. It contains all properties of a user (e.g. name, id, password)
  and all methods that are required to manage these properties.
  It is also responsible for all database interaction, i.e. for
  reading users, creating new users, editing users etc.

*/
class User {
  // unique id of the user
  private $id;
  // username of the user (should be unique, too - uniqueness not yet impelemnted)
  private $username;
  // password of the user (at the moment in plain text. should be hashed for security reasons)
  private $password;
  // email-address of the user
  private $email;
  // indicates if the user is an admin user (more rights)
  private $isAdmin;
  // address of the user - default: HdM
  private $address = "Nobelstr. 10, 70569 Stuttgart";
  // latitude
  private $lat = 0.0;
  // longitude
  private $lng = 0.0;
  // database helper
  protected static $db;

  /**
  *  Constructor. Initiates a DBHelper whenever a user object is created...
  */
  public function __construct(){
    self::$db = new DBHelper();
  }

  /**
  *
  * setUser() sets the properties of a user-object, e.g. name etc.
  * @param int id - unique id of the user
  * @param string username - username of the user (should be unique, too - uniqueness not yet impelemnted)
  * @param string password - password of the user (at the moment in plain text. should be hashed for security reasons)
  * @param boolean admin - indicates if the user is an admin user (more rights)
  * @param int email - email-address of the user
  * @return void
  *
  */
  public function setUser($id, $username, $password, $admin, $email, $address, $lat, $lng){
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->admin = $admin;
    $this->email = $email;
    $this->address = $address;
    $this->lat = $lat;
    $this->lng = $lng;
  }

  /**
  *
  * creates a new user in the datapase
  * @param string username
  * @param string password
  * @param string admin
  * @param string email
  * @return array returnArray: contains information about success of operation
  *               [0] boolean: true if creation was successful, else false
  *               [1] string: message (to be displayed to user)
  *               [2] int: id of the row that has just been inserted
  */

  public function create($username, $password, $admin, $email, $address){
    $lat = 0.0;
    $long = 0.0;
    $sql_query = "INSERT INTO `user`(`username`, `password`, `admin`, `email`, `address`, `lat`, `lng`) ";
    $sql_query .= "VALUES('". $username ."','" . $password ."','" . $admin . "','" . $email . "','" . $address ."','" . $lat . "','" . $long . "')";
    // run query
    $result = self::$db->query($sql_query);
    $errorText = mysqli_error(self::$db->conn);

    // returns an array. First argument: true/false indicates if update was
    // successful. Second argument is a message to the user.
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Inserted user " . $username;
    $returnArray[2] = mysqli_insert_id(self::$db->conn);
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
      $returnArray[2] = null;
    }
    return $returnArray;
  }

  /**
  *
  * updates user with ID $id in the datapase
  * @param int id
  * @param string username
  * @param string password
  * @param string admin
  * @param string email
  * @return array returnArray: contains information about success of operation
  *               [0] boolean: true if creation was successful, else false
  *               [1] string: message (to be displayed to user)
  */
  public function update($id, $username, $password, $admin, $email, $address){
    $location['lat'] = 0.0;
    $location['lng'] = 0.0;

    include('helpers/maphelper.php');
    $location = MapHelper::geoCode($address);

    $sql_query = "UPDATE `user` SET `username` = '" . $username . "',
      `password` = '" . $password . "',
      `admin` = '" . $admin . "',
      `email` = '" . $email . "',
      `address` = '" . $address . "',
      `lat` = '" . $location['lat'] . "',
      `lng` = '" . $location['lng'] . "'
      WHERE `id` = " . $id;
    // run query
    $result = self::$db->query($sql_query);

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

  /**
  *
  * deletes user with ID $id from datapase
  * @param int id
  * @return array returnArray: contains information about success of operation
  *               [0] boolean: true if creation was successful, else false
  *               [1] string: message (to be displayed to user)
  */
  public function delete($id){
    $sql_query="DELETE FROM `user` WHERE `id` = " . $id;
    $result = self::$db->query($sql_query);
    $returnArray = array();
    $returnArray[0] = true;
    $returnArray[1] = "Deleted user " . $id;
    if (!empty($errorText)){
      $returnArray[0] = false;
      $returnArray[1] = $errorText;
    }
    return $returnArray;
  }


  /**
  *
  * get user with an ID from DB
  * @param int id
  * @return object user object...
  *
  */
  public static function getUser($id){
    $user = new User();
    $sql_query = "SELECT * FROM `user` WHERE `id` = " . $id;
    $result = self::$db->query($sql_query);
    $row = mysqli_fetch_array($result);
    $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email'], $row['address'], $row['lat'], $row['lng']);
    return $user;
  }

  /**
  *
  * get user with certain username from DB
  * assumes that there is just one user with a certain username in DB
  * (not yet checked)
  * @param string username
  * @return object user object...
  *
  */
  public static function getUserByUserName($username){
    $user = new User();
    $sql_query = "SELECT * FROM `user` WHERE `username` = '" . $username . "'";
    $result = self::$db->query($sql_query);
    $row = mysqli_fetch_array($result);
    $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email'], $row['address'], $row['lat'], $row['lng']);
    return $user;
  }

  /**
  *
  * get all usesr from DB
  * @return array array of user objects...
  *
  */
  public static function getAllUsers(){
    $sql_query = "SELECT * FROM user";
    $result_set = self::$db->query($sql_query);
    $users = array();
    if(mysqli_num_rows($result_set) > 0) {
      while($row = mysqli_fetch_array($result_set)) {
        $user = new User();
        $user->setUser($row['id'], $row['username'], $row['password'], $row['admin'], $row['email'], $row['address'], $row['lat'], $row['lng']);
        $users[] = $user;
      }
    }
    return $users;
  }

  /**
  *  loginCheck: checks if user/password match...
  *  @param string username name that has been entered
  *  @param string password password that has been entered
  *  @return array returnArray: contains information about success of operation
  *               [0] boolean: true if creation was successful, else false
  *               [1] string: message (to be displayed to user)
  */
  public function loginCheck($username, $password){
    $returnArray = array();
    $returnArray[0] = false;
    $returnArray[1] = "";

    // checks if required data is provided
    if (! empty($username) && ! empty($password) ){
      $myUser = null;
      $myUser = $this::getUserByUserName($username);

      // check: user exists? passwords match?
      if (! empty($myUser->username) && $password == $myUser->password){
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


  /**
  *
  * Get methods for private properties... (provides reading access
  * for external classes...)
  *
  */

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

  public function getAddress(){
    return $this->address;
  }

  public function getLat(){
    return $this->lat;
  }

  public function getLng(){
    return $this->lng;
  }

  public function getEmail(){
    return $this->email;
  }

}


?>
