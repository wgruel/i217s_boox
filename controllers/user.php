<?php

  /**

    This is the user-controller.
    It is the connection between the model (data storage) and the view.
    It contains all important actions for the user and is controlling
    data processing and views...

    Not yet done:
    - Testing / Bugfixing
    - Extensive documentation
    - Security (e.g. make sure underprivilleged users are not allowed to add, edit, update a user)

  */

  class UserController {
    // we have these attributes for the class...
    public $user = null;
    public $view = null;

    // this is the constructor - it will always be called when a new
    // UserController obejct is created
    public function __construct(){
      // user always needs to be included...
      // make sure you do something like that in your index.php
      // require_once('models/user.php');
      $this->user = new User();
    }

    /**
      show users as list
    */
    public function indexAction() {
      // get all users from the database
      // (done by the model...)
      // returns an array of users - this can be displayed
      // in the userList-view...
      // NOTE: we use a static method: a static method or
      // property can be used without instantiating the object first
      $users = User::getAllUsers();
      // now, we call the list view.
      // here, we just process the $users-array that we just received
      require_once('views/userList.php');
    }

    /**
      provide form to edit user...
    */
    public function editAction($id){
      // get user with ID $id
      $user = User::getUser($id);
      // set $action = "save" - this is used as the action
      // in the form, i.e., it is the action that will be performed
      // after the form has been submitted
      $action = "save";
      // just some text that we want to display in our form
      $actionText = "Update User";
      // the form that displays the user that we want to change...
      // if we submit it, the content will be stored (database-update)
      require_once('views/userForm.php');
    }

    /**
      save a user that has been edited (update user)
    */
    public function saveAction($postarray){
      // in case the admin-field is not checked, it will be empty
      // in order to create a valid DB statement, we need to set it
      // properly. So, whenever "admin" is provided, we set the
      // variable to 1. If not, we set it to 0.
      if (isset($postarray['admin'])){
        $admin = 1;
      }
      else {
        $admin = 0;
      }
      // update the user
      // the variable $updateStatus is an array that contains
      // status information about the DB update
      $updateStatus = $this->user->update($postarray['id'], $postarray['username'], $postarray['password'], $admin, $postarray['email']);
      // and read the user again - so we can show the changed user in the view
      $user = User::getUser($postarray['id']);
      // the messge is just the text in field 1 of the status array...
      $message = $updateStatus[1];
      require_once('views/userForm.php');
    }

    /**
      provide a form to create a new user ...
    */
    public function createAction(){
      // set $action = "new" - this is used as the action
      // in the form, i.e., it is the action that will be performed
      // after the form has been submitted
      $action = "new";
      $actionText = "Create User";
      // create empty user... This is required as the form
      // expects a user-object to be present. This can be
      // an empty object...
      $user = new User();
      // again: just show the form...
      require_once('views/userForm.php');
    }

    /**
      save a user that has just been entered
    */
    public function newAction($postarray){
      // in case the admin-field is not checked, it will be empty
      // in order to create a valid DB statement, we need to set it
      // properly. So, whenever "admin" is provided, we set the
      // variable to 1. If not, we set it to 0.
      if (isset($postarray['admin'])){
        $admin = 1;
      }
      else {
        $admin = 0;
      }
      // we create a new user in the database...
      // the variable $createStatus is an array that contains
      // status information about the DB update
      $createStatus = $this->user->create($postarray['username'], $postarray['password'], $admin, $postarray['email']);
      // If successfull insert
      $message = $createStatus[1];
      // creating the user was successful. We get the user
      // that has just been created to show it in the form...
      if ($createStatus[0] == true){
        $user = User::getUser($createStatus[2]);
      }
      // if not, we create a new user and show the form again...
      else {
        $user = new User();
      }
      // and now, we show the form
      require_once('views/userForm.php');
    }

    /**
      Delete user
    */
    public function deleteAction($id){
      // we ask the user-object to delete the user with $id
      $deleteStatus = $this->user->delete($id);
      // $message that shows if deletion was successful
      $message = $deleteStatus[1];
      // after deletion, show the list ...
      $users = User::getAllUsers();
      require_once('views/userList.php');
    }

    /**
      Show user Form
    */
    public function loginAction(){
      // we only want to show the login-form and give it a nice title...
      $actionText = "Login";
      require_once('views/userLogin.php');
    }

    /**
      Check login data
    */
    public function loginCheck($postarray){
      // call the user's loginCheck method. It checks a given
      // username exists and the passwords match
      $loginStatus = $this->user->loginCheck($postarray['username'], $postarray['password']);
      // if login was successful ...
      if ($loginStatus[0]){
        // in order to make the user available across multiple sites
        // we store the user-object in the session-variable...
        // When you work with an application, you open it, do some changes,
        // and then you close it. This is much like a Session. The computer
        // knows who you are. It knows when you start the application and
        // when you end. But on the internet there is one problem: the
        // web server does not know who you are or what you do, because
        // the HTTP address doesn't maintain state.
        // Session variables solve this problem by storing user information
        // to be used across multiple pages (e.g. username, favorite
        // color, etc). By default, session variables last until the
        // user closes the browser.
        // So; Session variables hold information about one single user,
        // and are available to all pages in one application.
        $myUser = User::getUserByUserName($postarray['username']);
        $_SESSION['user'] = $myUser;
        // ... and we show the index page...
        $this->indexAction();
      }
      // if login was not successful...
      else {
        // ... we try again and display the error message...
        $actionText = "Login";
        $message = $loginStatus[1];
        require_once('views/userLogin.php');
      }
    }

    /**
      Logout -> destroy user session object...
    */
    public function logoutAction(){
      // to log the user out, we delete the user object that was
      // stored in the session...
      unset($_SESSION['user']);
      require_once('views/userLogin.php');
    }

  }
?>
