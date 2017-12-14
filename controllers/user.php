<?php

  /**

    This is the user controller.
    It is the connection between the model (data storage) and the view.
    It contains all important actions regarding the user and is controlling
    data processing and views...

    Not yet done:
    - Testing / Bugfixing
    - Extensive documentation
    - Security (e.g. make sure underprivilleged users are not allowed to add, edit, update a user)

  */

  class UserController {
    public $user = null;
    public $view = null;

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
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }
    /**
      provide form do edit user...
    */
    public function editAction($id){
      $user = $this->user->getUser($id);
      $action = "save";
      $actionText = "Update User";
      require_once('views/userForm.php');
    }

    /**
      save a user that has been edited
    */
    public function saveAction($postarray){
      if (isset($postarray['admin'])){
        $admin = 1;
      }
      else {
        $admin = 0;
      }
      $updateStatus = $this->user->update($postarray['id'], $postarray['username'], $postarray['password'], $admin, $postarray['email']);
      $user = $this->user->getUser($postarray['id']);
      $message = $updateStatus[1];
      require_once('views/userForm.php');
    }

    /**
      provide a form to create a new user ...
    */
    public function createAction(){
      $action = "new";
      $actionText = "Create User";
      // create empty user...
      $user = new User();
      require_once('views/userForm.php');
    }

    /**
      save a user that has just been entered
    */
    public function newAction($postarray){
      if (isset($postarray['admin'])){
        $admin = 1;
      }
      else {
        $admin = 0;
      }
      $createStatus = $this->user->create($postarray['username'], $postarray['password'], $admin, $postarray['email']);
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

    /**
      Delete user
    */
    public function deleteAction($id){
      $deleteStatus = $this->user->delete($id);
      $message = $deleteStatus[1];
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }

    /**
      Show user Form
    */
    public function loginAction(){
      $action = "new";
      $actionText = "Login";
      // create empty user...
      $user = new User();
      require_once('views/userLogin.php');
    }

    /**
      Check login data
    */
    public function loginCheck($postarray){
      $loginStatus = $this->user->loginCheck($postarray['username'], $postarray['password']);
      if ($loginStatus[0]){
        $this->indexAction();
      }
      else {
        $action = "new";
        $actionText = "Login";
        $message = $loginStatus[1];
        // create empty user...
        $user = new User();
        require_once('views/userLogin.php');
      }
    }

    /**
      Logout -> destroy user session object...
    */
    public function logoutAction(){
      unset($_SESSION['user']);
      $action = "logout";
      $actionText = "Login";
      // create empty user...
      $user = new User();
      require_once('views/userLogin.php');
    }

  }
?>
