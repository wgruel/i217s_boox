<?php
// we also need some db connection...
require_once('helpers/dbhelper.php');
require_once('models/user.php');

// in order to store variables across multiple websites, we start a session
session_start();

// we use different controllers that come with different actions.
// these controllers and actions determine that our software is supposed to do
// we will pass controllers and actions to the script via the URL of
// the script.
// What does this mean?
// The URL index.php?controller=user&action=index will call
// the index action of the user-controller (i.e. it will just list all users)
// The URL index.php?controller=book&action=create will open
// a form to input a new book.


// if no variables are set, we need some default actions
$controller = "user";
$action = "index";

// however, if actions are provided, we take these actions ...
if (isset($_GET['controller'])){
    $controller = trim($_GET['controller']);
}
if (isset($_GET['action'])){
    $action = trim($_GET['action']);
}

// the contents of the variable $controller only determines which
// controller-file to include ...
// the controller-files are stored in the controller-folder
// the filename consists of the variable $controller and the ".php" ending
$filename = 'controllers/' . $controller . '.php';

// check if the file exists
if (is_file($filename)) {
    // ... now, include that controller-file
    require $filename;

    // now, we determine what the contoller is supposed to do

    /**
      User related stuff - determine which functions of the controller
      should be used and pass the right variables to these functions...
    */
    if ($controller == "user"){

      // first use of object-oriented-programming...
      // create an instance of the class "UserController"
      $uController = new UserController();

      // if the $action variable is index, we call the index-action
      // (responsible for showing the list of users)
      if ($action == "index"){
        // Call the function indexAction of the object $uController
        // The action is defined in the controller. It will query the DB,
        // get all users that will be stored in an array. This array will
        // be passed to the view. The view is then responsible to
        // display this list...
        // To understand what happens, check function indexAction() in
        // the file controllers/user.php
        $uController->indexAction();
      }

      else if ($action == "detail"){
        // call uController's createAction method: This method only
        // shows a form to enter the user data...
        $uController->detailAction($_GET['id']);
      }

      // The action is "create". Creating a user is a two step approach:
      // 1) display a form that allows the user to enter the user data
      // 2) store that data in the DB (create statement - no ID required).
      // Depending on the results, the output will be different
      // Ok. Start with step 1 - The form
      else if ($action == "create"){
        // call uController's createAction method: This method only
        // shows a form to enter the user data...
        $uController->createAction();
      }
      // Now: Step 2 - store the data
      else if ($action == "new"){
        // check if we received data from our form...
        if(isset($_POST['btn-save'])){
          // now, we call the newAction. This action is responsible
          // to enter data into the DB
          $uController->newAction($_POST);
        }
        else {
          // something went wrong... Button was probably not pressed.
          die("Can't save user. Did you really push the button?");
        }
      }
      // We want to edit an existing user - $action == "edit"
      // To edit a user, we need to know which user - we pass the ID
      // via a get-parameter. If this parameter is not set, we show an error
      // The rest of the process is similar to the creation process:
      // 1) display a form that allows the user to enter the user data
      // 2) store that data in the DB (update statement - ID required)
      // step 1: the form
      else if ($action == "edit"){
        if(isset($_GET['id'])){
          // call uController's createAction method: This method
          // gets the user data from the DB and then shows a form to
          // update the user data...
          $uController->editAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
      // step 2: update user
      else if ($action == "save"){
        if(isset($_POST['btn-save']) && isset($_POST['id'])){
          $uController->saveAction($_POST);
        }
        else {
          die("Can't save as ID is missing or button was not pressed");
        }
      }
      // Now, we want to delete a user... In order to delete the user,
      // we also need to know his/her ID which we expect to be provided
      // via $_GET
      else if ($action == "delete"){
        // in case the ID is provided...
        if(isset($_GET['id'])){
          // we call the deleteAction of the $uController object
          $uController->deleteAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }

      // After the create, read, update, delete actions, we
      // also want to do something else with the user:
      // we want log the user in and out...
      // The login-process works as follows:
      // - Show form (username, password)
      // - Check if user exists and password is correct:
      //  -- check if user exists
      //  -- get user with respective username from the DB
      //  -- Check if password that has been entered
      //    is identical to stored password
      //  -- if so, everything is fine and we store the user in $_SESSION
      //    we do that as we want to use the user-object in other
      //    sites as well. E.g., we only allow the owner and admins
      //    to update a user...
      else if ($action == "login"){
        // responsible for displaying the login form...
        $uController->loginAction();
      }
      else if ($action == "loginCheck"){
        // if button was passed, we check if login was correct..
        if(isset($_POST['btn-save']) && isset($_POST['username'])){
          $uController->loginCheck($_POST);
        }
        else {
          die("Something went wrong while logging in...");
        }
      }
      // we also want to be able to log the user out...
      // this means, we delete user-object that is stored in $_SESSION
      else if ($action == "logout"){
        $uController->logoutAction();
      }
      // If no valid action was provided...
      else {
        die("no valid action for user provided");
      }
    }
    /**
      All book related stuff
      -- works basically in the same way as the user-actions...
    */
    if ($controller == "book"){
      $bController = new BookController();
      if ($action == "index"){
        $bController->indexAction();
      }
      else if ($action == "create"){
        $bController->createAction();
      }
      else if ($action == "new"){
        if(isset($_POST['btn-save']) && isset($_POST['id'])){
          $bController->newAction($_POST);
        }
        else {
          die("Can't save as ID is missing or button was not pressed");
        }
      }
      else if ($action == "edit"){
        if(isset($_GET['id'])){
          $bController->editAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
      else if ($action == "save"){
        if(isset($_POST['btn-save']) && isset($_POST['id'])){
          $bController->saveAction($_POST);
        }
        else {
          die("Can't save as ID is missing or button was not pressed");
        }
      }
      else if ($action == "delete"){
        if(isset($_GET['id'])){
          $bController->deleteAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
      else {
        die("no valid action for books provided");
      }
    }
}
else {
  die("Controller-File Not Found" . $filename);
}
