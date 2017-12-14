
# Move to MVC

## Move to object-oriented architecture 

We do not want to program sequentially any longer. Instead, we want to use object oriented programming (OOP). "Object-oriented programming (OOP) is a programming paradigm based on the concept of "objects", which may contain data, in the form of fields, often known as attributes; and code, in the form of procedures, often known as methods. A feature of objects is that an object's procedures can access and often modify the data fields of the object with which they are associated (objects have a notion of "this" or "self"). In OOP, computer programs are designed by making them out of objects that interact with one another. There is significant diversity of OOP languages, but the most popular ones are class-based, meaning that objects are instances of classes, which typically also determine their type." ([Source](https://en.wikipedia.org/wiki/Object-oriented_programming)). 
"Object-oriented programming takes the view that what we really care about are the objects we want to manipulate rather than the logic required to manipulate them. Examples of objects range from human beings (described by name, address, and so forth) to buildings and floors (whose properties can be described and managed) down to the little widgets on a computer desktop (such as buttons and scroll bars)." [Source](http://searchmicroservices.techtarget.com/definition/object-oriented-programming-OOP)

So, for the types of objects that we use, we want to apply this paradigm. We use users and books, so we'll use classes for them. We also want to use the MVC pattern. Therefore, we organize our classes in a specific way. 

## What is MVC?
This is a broad definition of the components as defined by the pattern. Later on I will describe variants of this but this is MVC as described by the original implementations in Smalltalk-80. Where variations are made, I have highlighted them.

### The Model
In its simplest form the model stores data which is to be accessed by the view and written to by the controller.

The model is the most complex of all the parts of the system and will contain all the logic which is specific to the application and where domain entities that relate to real world concepts (such as "a user" or "an order") are stored. It is the part of the application which takes data (from any source) and processes it. The model also handles all data access and storage. It has no knowledge of any controllers or views which may use it.

For example, in PHP the model may represent a "User" in the system. It will handle any operations regarding users. Saving/loading records, validating registrations.

The model is not (common mistakes made by those misinterpreting the pattern):

- A simple data access point
- A class called "model" which represents a single database table

### The View
The view contains all the display logic. In PHP it will be the part of the application which generates the HTML. It has direct access to the Model and can query the model to get its data. The View can create callbacks to its controller (for example a clicking a button in the view would trigger an action in the controller). A lot of MVC examples state that the view is decoupled from everything else and fed data by the controller. This is inaccruate. In MVC the view queries the model to request its own data.

The View is not (common mistakes made by those misinterpreting the pattern):
- Absent of logic
- Given data by the controller

### The Controller
The controller takes user input and updates the model where required. Where there is no user interaction (e.g. where a static data set is displayed and will be the same every time), no controller should be necessary. It is important to note that the controller is not a mediator or gateway between the view and the model. The view gets its own data from its model. The controller accesses the model but does not contain any display logic itself. All the controller does is respond to user input.

It's important to note that the controller is not in charge of instantiating the model or the view. The controller knows of them but flexibility is heavily reduced in implementations that force the controller to select which view and model is being used.

Each controller is linked to a single instance of a view and a single instance of a model.

The Controller is not (common mistakes made by those misinterpreting the pattern):

- A gateway/mediator between the model and the view
- The place where views get initialised based on the state of a model. The controller is linked to a single view class (although it could be assigned to multiple instances) and responds to actions on it. For example a list of products would be a single view. The controller would handle user actions directly relating to that view such as sorting the list, filtering the records it's displaying based on criteria specified by users. However, the same model/view/controller triad would not also deal with displaying the infoformation about a single product based on a given ID. This is a different responsiblity and as such requires a different view, model and controller.

### Program flow
The typical program flow in MVC is:

- The model, view and controller are initialised
- The view is displayed to the user, reading data from the model
- The user interacts with the view (e.g. presses a button) which calls a specified controller action
- The controller updates the model in some way
- The view is refreshed (fetching the updated data from the model)

## Move our software to MVC

We first create directories called "models", "views", and "controllers". 

### Model
In a new file called user.php, we write: 

```
<?
class User {
  public function __construct() {

  }
}
?>
```

The `class` keyword indicates that we want to define a class that we then call User. The `__construct` function is called whenever a new User-object is called. `getData()` will be responsible to return the data. 

We add the user-variables as private properties to the top of the class: 
```
  private $id;
  private $username;
  private $password;
  private $email;
```
These are the variables that we want to deal with. We create methods that are responsible for dealing with all the actions that we do with our user-object: 
```
  public static function create($username, $password, $email){
    
  }

  public static function update($id, $username, $password, $email){
    
  }

  public static function delete($id){
    
  }

  public static function getUser($id){
    
  }

  public static function getAllUsers(){
    
  }
```
That's the framework. We'll now, piece by piece, transfer the database methods that we already created to the model. First of all, we include the Database-Configuration-File. 
```
require_once('dbconfig.php');
```

Then, we create  new method calles 'connectToDB': 
```
  private function connectToDB(){
    mysql_connect($host, $user, $password);
    mysql_select_db($datbase);
  }
```
Now, we can copy all the SQL-related stuff from index.php to the `getAllUsers`. The function looks now like that: 
```
  public static function getAllUsers(){
    self::connectToDB();
    $sql_query = "SELECT * FROM user";
    $result_set = mysql_query($sql_query);
    $users = array();
    if(mysql_num_rows($result_set) > 0) {
      while($row = mysql_fetch_row($result_set)) {
        $users[] = $row;
      }
    }
    return $users;
  }

```
Note that I added a variable `$users` to the stuff we know from `index.php`. This is our return value. Instead of direclty writing the data directly to the HTML file, we can now move it between different functions. If we want to display it, we have to loop through this array. Also, I call the method `connectToDB` to establish a DB connection. 


### View

We copy most of the orininal index.php to the view-file (view/userListphp). We just remove all the DB stuff and some of the header... We replace the DB-stuff with the following code:
```
    <?php
      if(is_array($users) && count($users) > 0) {
        foreach ($users as $user) {
          ?>
            <tr>
              <td><?php echo $user[0] ?></td>
              <td><?php echo $user[1] ?></td>
              <td><?php echo $user[3] ?></td>
              <td></td>
              <td></td>
            </tr>
          <?php
        }
      }
      else {
          echo "<tr><td colspan='5'>No data found</td></tr>";
      }
    ?>
```
Instead of looping through the database results, we are now looping the $users object which is provided to the view and which is generated by the model (models/user.php > getAllUsers()). We could do more advanded error handling etc, but for now, we leave it as it is. 

### Controller

Now, we create the controller. 
```
<?php
  class UserController {
    private $user = null;
 
    public function __construct(){
      require_once('models/user.php');
      $this->user = new User();
    }

    public function indexAction() {
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }
  }
?>

```
The user controller defines what to do after some interaction of the user. The default action will be `indexAction` which we define here. We request an array of users by calling `getAllUsers` from the user-object that we generated. 

Before we can see anything in the browser, we have to rebuild the index.php. The index file is now controlling all the actions of our application. It receives what action to perform with the help of the get parameters. If no parameter is provided, it is going to call the indexAction. 
```
<?php
// default actions...
$controller = "user";
$action = "index";
// if actions are provided...
if (isset($_GET['controller'])){
    $controller = trim($_GET['controller']);
}
if (isset($_GET['action'])){
    $action = trim($_GET['action']);
}


$filename = 'controllers/' . $controller . '.php';
if (is_file($filename)) {
    require $filename;
    if ($controller == "user"){
      $uController = new UserController();
      if ($action == "index"){
        $uController->indexAction();
      }
    }
}
else {
  die("Controller-File Not Found" . $filename);
}
```

### Some more refactoring due to Object orientation
We want to be a little more object oriented. That is why we add the following method to the user-model: 
```
  public function setUser($id, $username, $password, $email){
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->email = $email;
  }
```

We also change our function in getAllUsers(): 
```
  while($row = mysql_fetch_row($result_set)) {
    $user = new User();
    $user->setUser($row[0], $row[1], $row[2], $row[3]);
    $users[] = $user;
  }
```
By doing that, we do not have to deal with strange arrays anymore, but can access the user objects, e.g. by using $user->getUsername() and are more conscious of what we do... 
We also need to adapt the view:
```
    <td><?php echo $user->getID() ?></td>
    <td><?php echo $user->getUsername() ?></td>
    <td><?php echo $user->getEmail() ?></td>
    <td></td>
    <td></td>
``` 

### Edit single user

Now, we want to edit a single user. We copy the edit_user.php to a file called userForm.php in the views folder.

In the UserController, we add the function `editAction`: 
```
    public function editAction($id){
      $user = $this->user->getUser($id);
      require_once('views/userForm.php');
    }
```
In the index.php, we add a new action called 'edit': 
```
      else if ($action == "edit"){
        if(isset($_GET['id'])){
          $uController->editAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
```
And in the user-model, we add some code to getUser(): 
```
  public static function getUser($id){
    self::connectToDB();
    $user = new User();
    $sql_query = "SELECT * FROM `user` WHERE `id` = " . $id;
    $result = mysql_query($sql_query);
    $row = mysql_fetch_array($result);
    $user->setUser($row['id'], $row['username'], $row['password'], $row['email']);
    return $user;
  }
```

We can not do a first test and call `index.php?action=edit&id=2` in our browser. We should see the edit-user page. The user data is loaded. The results look pretty good, but we also have to adapt the view file and replace the SQL-stuff with the new logic: 
```
<input type="text" class="form-control" id="username" name="username" value="<?php echo $user->getUsername(); ?>">

```

```
<input type="text" class="form-control" id="password" name="password" value="<?php echo $user->getPassword(); ?>"> 
```

```
<input type="text" class="form-control" id="email" name="email" value="<?php echo $user->getEmail(); ?>">
<input type="hidden" id="id" name="id" value="<?php echo $user->getID(); ?>">
```
We also have to adapt the action: We don't want to just send the data back to the form itself - in order to be able to decide what happens in case of success/failure, we want to call a different action (save). 
```
    public function saveAction($postarray){
      $updateStatus = $this->user->update($postarray['id'], $postarray['username'], $postarray['password'], $postarray['email']);
      $user = $this->user->getUser($postarray['id']);
      // we can do different things right here - e.g. redirect to the list view if update was successful... 
      $message = $updateStatus[1];
      require_once('views/userEdit.php');
    }

```
We can now edit our user... 

To make the user editable from the list view, we add a link to the table: 
```
  <td><a href="?action=edit&id=<?php echo $user->getID() ?>">edit</a></td>
```


### Database modification... 
We might notice that we get in trouble because we try to connect to the database more than once - and from more than one class (e.g. from User and from Books. That is why we outsource the common database functionality into a class that we can call DBHelper. 

```
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
      $this->conn = mysql_connect($this->host, $this->user, $this->password)
        or die("Connection error " . mysql_error());
      mysql_select_db($this->database);
  }

  public function query($sql){
    $result = mysql_query($sql, $this->conn);
    if (! $result) {
        die(mysql_errror());
    }
    return $result;
  }

}

?>
```

In the User-Class, we change the constructor and also store the database-class as a class-variable: 
```
  protected $db;

  public function __construct(){
    $this->db = new DBHelper();
  }

```

Then, we remove all: `self::connectToDB();`. Also, we remove all the 'static' keywords before the function-names in order to use the functions only when in an object-context. Then, we change the DB-queries from direct queries to `$result_set = $this->db->query($sql_query);`, so we can use the query-function of the DBHelper class. 


### Add new User

We also want to be able to add new users. First thing we do, is to modify our `Add User` button in userList.php: 
```
<a class="btn btn-primary" href="?controller=user&action=create" role="button">Add User</a>
```

In our index.php, we add:
```
      else if ($action == "create"){
        $uController->createAction();
      }
```
Now, we create the createAction in the user controller. 

```
    public function createAction(){
      $action = "new";
      $actionText = "Create User";
      // create empty user...
      $user = new User();  
      require_once('views/userForm.php');
    }
```
We can reuse the userForm for this purpose. We add two variable: `$action` is the action that is performed when updating, `$actionButton` is the text of the button... We also have to add these variables to the saveAction: 
```
    public function editAction($id){
      $user = $this->user->getUser($id);
      $action = "save";
      $actionText = "Update User";
      require_once('views/userForm.php');
    }
``` 
In the userForm.php, we have to put in these variables as well: 
```
<h1><?php echo $actionText ?></h1>
[...]
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php echo $action ?>">
[...]
<button type="submit" class="btn btn-default" name="btn-save"><?php echo $actionButton ?></button>
```
To actually create a new user, we could re-use the saveAction or create a new action - to keep thinks simple what that's what we do: 
```
    else if ($action == "new"){
      if(isset($_POST['btn-save']) && isset($_POST['id'])){
        $uController->newAction($_POST);
      }
      else {
        die("Can't save as ID is missing or button was not pressed");
      }
    }

```
In our controller file, we create this new action: 
```
    public function newAction($postarray){
      $createStatus = $this->user->create($postarray['username'], $postarray['password'], $postarray['email']);
      // If successfull insert
      $message = $createStatus[1];
      if ($createStatus[0]){
        $user = $this->user->getUser($createStatus[2]);
      }
      // if not, we create a new user and show the form again...
      else {
        $user = new User();
      }
      require_once('views/userForm.php');
    }

```

### Delete User
To delete the user, we want to keep our JS-popup. To call the popup-function, we create a link in the table for each entry in userList.php
```
<td><a href="javascript:deleteUser('<?php echo $user->getID() ?>')">delete</td>
```
We adapt the Javascript code in the header of the file: 
```
    <script type="text/javascript">
      function deleteUser(id) {
        if(confirm('Do you really want to delete this user?')) {
            window.location.href='?controller=user&action=delete&id=' + id;
        }
      }
    </script>

```

Then, we adapt the index.php. 
```
    else if ($action == "delete"){
      if(isset($_GET['id'])){
        $uController->deleteAction($_GET['id']);
      }
      else {
        die("No ID provided");
      }
    }

```

We, you probably got it, have to create a new action again: 
```
    public function deleteAction($id){
      $deleteStatus = $this->user->delete($id);
      $message = $deleteStatus[1];
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }
```
After we deleted the user, we get the list of users again and show the list with all users. 

Of course, we also add a delete function to the user-model: 
```
  public function delete($id){
    $sql_query="DELETE FROM `user` WHERE `id` = ". $id;
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

```
Finally, we can also insert the message part into userList.php in order to display messages... 
```
    <?php
      if (isset($message) && $message != ""){
        echo "<div class='alert alert-info'>";
        echo $message;
        echo "</div>";
      }
    ?>
```

