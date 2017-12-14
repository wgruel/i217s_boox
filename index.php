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
    // we also need some db connection...
    require_once('helpers/dbhelper.php');
    if ($controller == "user"){
      $uController = new UserController();
      if ($action == "index"){
        $uController->indexAction();
      }
      else if ($action == "edit"){
        if(isset($_GET['id'])){
          $uController->editAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
      else if ($action == "save"){
        if(isset($_POST['btn-save']) && isset($_POST['id'])){
          $uController->saveAction($_POST);
        }
        else {
          die("Can't save as ID is missing or button was not pressed");
        }
      }
      else if ($action == "create"){
        $uController->createAction();
      }
      else if ($action == "new"){
        if(isset($_POST['btn-save']) && isset($_POST['id'])){
          $uController->newAction($_POST);
        }
        else {
          die("Can't save as ID is missing or button was not pressed");
        }
      }
      else if ($action == "delete"){
        if(isset($_GET['id'])){
          $uController->deleteAction($_GET['id']);
        }
        else {
          die("No ID provided");
        }
      }
      else {
        die("no valid action provided");
      }
    }
}
else {
  die("Controller-File Not Found" . $filename);
}
