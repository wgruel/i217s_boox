<?php
require('config.php');

if(isset($_POST['username'])){
  $name = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  $stmt = "INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES (NULL, '" . $name . "', '" . $password . "', '" . $email ."');";
  $result = $link->query($stmt);

}
else {
  echo "nichts gesendet";
}


?>
<script>
  function replaceUsername(){
    document.getElementById('username').value = "";
  }
</script>

<form method="post">

  <label for="username">Username</label>
  <input type="text" id="username" name="username" value="username" onclick="replaceUsername()">
  <label for="username">Passwort</label>
  <input type="text" id="password" name="password" value="passwort">
  <label for="username">E-Mail</label>
  <input type="text" id="email" name="email">
  <button type="submit" name="btn_save">Add User</button>


</form>
