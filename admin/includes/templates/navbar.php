<nav class="navbar navbar-expand-lg navbar-dark mynav" >
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="category.php">Category</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="items.php">Items</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="members.php">Members</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="comments.php">Comments</a>
      </li> 
    
    </ul>

    <ul class="navbar-nav ml-auto" >

      <li class="nav-item dropdown ml-auto">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['user'] ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="members.php?do=Edit&userId= <?php echo $_SESSION['id'] ?>">Edit Profile</a>
          <a class="dropdown-item" href="#">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </li>
     
    </ul>

  </div>
</nav>