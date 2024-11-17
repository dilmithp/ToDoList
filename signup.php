<?php
include 'header.php';
include 'includes/db.inc.php'; // Include the database connection file

// Initialize variables for form data and errors
$username = $email = $password = $confirm_password = "";
$usernameErr = $emailErr = $passwordErr = $confirm_passwordErr = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate username
  if (empty($_POST['username'])) {
    $usernameErr = "Username is required";
  } else {
    $username = htmlspecialchars($_POST['username']);
  }

  // Validate email
  if (empty($_POST['email'])) {
    $emailErr = "Email is required";
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
  } else {
    $email = htmlspecialchars($_POST['email']);
  }

  // Validate password
  if (empty($_POST['password'])) {
    $passwordErr = "Password is required";
  } elseif (strlen($_POST['password']) < 6) {
    $passwordErr = "Password must be at least 6 characters";
  } else {
    $password = $_POST['password'];
  }

  // Confirm password
  if (empty($_POST['confirm_password'])) {
    $confirm_passwordErr = "Please confirm your password";
  } elseif ($password !== $_POST['confirm_password']) {
    $confirm_passwordErr = "Passwords do not match";
  }

  // If no errors, insert user into the database
  if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirm_passwordErr)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
      $successMessage = "You have successfully registered. You can now <a href='login.php'>log in</a>.";
    } else {
      $successMessage = "Error registering. Please try again.";
    }

    // Clear the form data after submission
    $username = $email = $password = $confirm_password = "";
  }
}
?>

<section class="signup">
  <div class="container">
    <h2>Create an Account</h2>

    <?php if ($successMessage): ?>
      <div class="message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="signup.php">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>
      <span class="error"><?php echo $usernameErr; ?></span>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
      <span class="error"><?php echo $emailErr; ?></span>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" value="<?php echo $password; ?>" required>
      <span class="error"><?php echo $passwordErr; ?></span>

      <label for="confirm_password">Confirm Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" value="<?php echo $confirm_password; ?>" required>
      <span class="error"><?php echo $confirm_passwordErr; ?></span>

      <button type="submit" class="btn">Sign Up</button>
    </form>
  </div>
</section>

<?php include 'footer.php'; ?>