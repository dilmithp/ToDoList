<?php
include 'header.php';
include 'includes/db.inc.php'; // Include the database connection file

// Initialize variables for form data and errors
$name = $email = $message = "";
$nameErr = $emailErr = $messageErr = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate name
  if (empty($_POST['name'])) {
    $nameErr = "Name is required";
  } else {
    $name = htmlspecialchars($_POST['name']);
  }

  // Validate email
  if (empty($_POST['email'])) {
    $emailErr = "Email is required";
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
  } else {
    $email = htmlspecialchars($_POST['email']);
  }

  // Validate message
  if (empty($_POST['message'])) {
    $messageErr = "Message is required";
  } else {
    $message = htmlspecialchars($_POST['message']);
  }

  // If no errors, insert into the database
  if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
    $query = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
      $successMessage = "Your message has been sent successfully!";
    } else {
      $successMessage = "There was an error submitting your message. Please try again.";
    }

    // Clear the form data after submission
    $name = $email = $message = "";
  }
}
?>

<section class="contact">
  <div class="container">
    <h2>Contact Us</h2>

    <?php if ($successMessage): ?>
      <div class="message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="contact.php">
      <label for="name">Your Name:</label>
      <input type="text" name="name" id="name" value="<?php echo $name; ?>" required>
      <span class="error"><?php echo $nameErr; ?></span>

      <label for="email">Your Email:</label>
      <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
      <span class="error"><?php echo $emailErr; ?></span>

      <label for="message">Your Message:</label>
      <textarea name="message" id="message" required><?php echo $message; ?></textarea>
      <span class="error"><?php echo $messageErr; ?></span>

      <button type="submit" class="btn">Send Message</button>
    </form>

    <?php if ($successMessage): ?>
      <h3>Your message has been received:</h3>
      <p><strong>Email:</strong> <?php echo $email; ?></p>
      <p><strong>Message:</strong> <?php echo nl2br($message); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>