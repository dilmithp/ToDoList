<?php
include 'header.php';
include 'includes/db.inc.php'; // Include the database connection file

// Initialize variables for form data and errors
$email = $password = "";
$emailErr = $passwordErr = $loginError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate email
  if (empty($_POST['email'])) {
    $emailErr = "Email is required";
  } else {
    $email = htmlspecialchars($_POST['email']);
  }

  // Validate password
  if (empty($_POST['password'])) {
    $passwordErr = "Password is required";
  } else {
    $password = $_POST['password'];
  }

  // If no errors, attempt to log in
  if (empty($emailErr) && empty($passwordErr)) {
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to homepage or dashboard
        header('Location: index.php');
        exit();
      } else {
        $loginError = "Invalid email or password";
      }
    } else {
      $loginError = "Invalid email or password";
    }
  }
}
?>

<section class="login">
  <div class="container">
    <h2>Login</h2>

    <?php if ($loginError): ?>
      <div class="error"><?php echo $loginError; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
      <span class="error"><?php echo $emailErr; ?></span>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" value="<?php echo $password; ?>" required>
      <span class="error"><?php echo $passwordErr; ?></span>

      <button type="submit" class="btn">Log In</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
  </div>
</section>

<?php include 'footer.php'; ?>