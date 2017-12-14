<?php

  class UserController {
    public $user = null;
    public $view = null;

    public function __construct(){
      require_once('models/user.php');
      $this->user = new User();
    }

    public function indexAction() {
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }

    public function editAction($id){
      $user = $this->user->getUser($id);
      $action = "save";
      $actionText = "Update User";
      require_once('views/userForm.php');
    }

    public function saveAction($postarray){
      $updateStatus = $this->user->update($postarray['id'], $postarray['username'], $postarray['password'], $postarray['email']);
      $user = $this->user->getUser($postarray['id']);
      $message = $updateStatus[1];
      require_once('views/userForm.php');
    }

    public function createAction(){
      $action = "new";
      $actionText = "Create User";
      // create empty user...
      $user = new User();
      require_once('views/userForm.php');
    }

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

    public function deleteAction($id){
      $deleteStatus = $this->user->delete($id);
      $message = $deleteStatus[1];
      $users = $this->user->getAllUsers();
      require_once('views/userList.php');
    }

  }
?>
