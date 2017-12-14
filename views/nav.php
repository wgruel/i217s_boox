<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Boox</a>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="?controller=user&action=index">Books</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?controller=user&action=index">Users</a>
      </li>
    </ul>
    <span class="navbar-text">
      <?php
        if (!empty($_SESSION['user'])){
          echo '<a class="nav-link" href="?controller=user&action=logout">Logout</a>';
        }
        else {
          echo '<a class="nav-link" href="?controller=user&action=login">Login</a>';
        }

      ?>
    </span>
  </div>
</nav>
