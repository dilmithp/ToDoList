<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'header.php';
include 'includes/db.inc.php';

$message = "";

// Check if the task ID is provided
if (isset($_GET['id'])) {
  $taskId = intval($_GET['id']);

  // Fetch the task details
  $query = "SELECT * FROM tasks WHERE id = $taskId";
  $result = $conn->query($query);

  if ($result->num_rows == 1) {
    $task = $result->fetch_assoc();
  } else {
    die("Task not found.");
  }
}

// Handle task update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $taskName = htmlspecialchars($_POST['task_name']);
  $dueDate = htmlspecialchars($_POST['due_date']);

  $updateQuery = "UPDATE tasks SET task_name = ?, due_date = ? WHERE id = ?";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("ssi", $taskName, $dueDate, $taskId);

  if ($stmt->execute()) {
    $message = "Task updated successfully!";
    header("Location: tasks.php"); // Redirect to task list after update
    exit;
  } else {
    $message = "Error updating task: " . $conn->error;
  }
}
?>

<section class="edit-task">
  <div class="container">
    <h2>Edit Task</h2>

    <?php if ($message): ?>
      <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="task_name">Task Name:</label>
      <input type="text" name="task_name" id="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>

      <label for="due_date">Due Date:</label>
      <input type="date" name="due_date" id="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>

      <button type="submit" class="btn">Update Task</button>
    </form>
  </div>
</section>

<?php include 'footer.php'; ?>