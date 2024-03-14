<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  $loggedin = false; // Set to false when the user is not logged in
}
else{
  $loggedin = true; // Set to true when the user is logged in
}
echo '<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/Fruitwala/index.php">
    <img src="logo.png" alt="Logo" width="40" height="34" class="d-inline-block align-text-top">
    फ्रूटwala
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/Fruitwala/index.php">Home</a>
        </li>
      <li class="nav-item">
          <a class="nav-link" href="/Fruitwala/fruits.php">Fruits</a>
        </li>';
        echo '<li class="nav-item">
          <a class="nav-link" href="/Fruitwala/your_fruits.php">Your Fruits</a>
        </li>';
        if(!$loggedin){
        echo '<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
           Register
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/Fruitwala/login.php">Login</a></li>
            <li><a class="dropdown-item" href="/Fruitwala/signup.php">Signup</a></li>';
          }
        if($loggedin){
          echo  '<li><a class="nav-link" href="/Fruitwala/logout.php">Logout</a></li>';
          }
          echo '</ul>
        </li>
      </ul>
    </div>
  </div>
</nav>';
?>
