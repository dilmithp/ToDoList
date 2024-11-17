<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDoWithMe</title>
  <link rel="stylesheet" href="style/styles.css">
</head>

<body>

  <header>
    <div class="container">
      <div class="logo">
        <h1><a href="index.php">ToDoWithMe</a></h1>
      </div>

      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- If user is logged in -->
            <li><a href="tasks.php">My Tasks</a></li>
            <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
            <!-- If user is not logged in -->
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Page Content Goes Here -->

</body>

</html>