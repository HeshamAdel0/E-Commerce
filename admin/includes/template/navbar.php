<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">  
    <a class="navbar-brand" href="dashbord.php">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="categories.php">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="items.php">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="members.php">Members</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="comment.php">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Statistics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Logs</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">  
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../index.php">Vist Shop</a>
                    <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a>
                    <a class="dropdown-item" href="#">Seating</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
 </div>  
</nav>