<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'header.php';
include 'includes/db.inc.php';

// Handle Task Deletion
if (isset($_GET['delete'])) {
  $taskId = intval($_GET['delete']);
  $deleteQuery = "DELETE FROM tasks WHERE id = $taskId";
  if ($conn->query($deleteQuery)) {
    $message = "Task deleted successfully!";
  } else {
    $message = "Error deleting task: " . $conn->error;
  }
}

// Fetch all tasks
$query = "SELECT * FROM tasks ORDER BY due_date ASC";
$result = $conn->query($query);

?>

<section class="tasks">
  <div class="container">
    <h2>Manage Your Tasks</h2>

    <?php if (isset($message)): ?>
      <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Task List -->
    <?php if ($result->num_rows > 0): ?>
      <table class="task-table">
        <thead>
          <tr>
            <th>Task</th>
            <th>Due Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($task = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($task['task_name']); ?></td>
              <td><?php echo htmlspecialchars($task['due_date']); ?></td>
              <td>
                <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn edit-btn">Edit</a>
                <a href="tasks.php?delete=<?php echo $task['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No tasks available. <a href="add_task.php" class="btn">Add Your First Task</a></p>
    <?php endif; ?>
  </div>
</section>
<?php
if (isset($_GET['delete'])) {
  $taskId = intval($_GET['delete']);
  $deleteQuery = "DELETE FROM tasks WHERE id = $taskId";
  if ($conn->query($deleteQuery)) {
    $message = "Task deleted successfully!";
  } else {
    $message = "Error deleting task: " . $conn->error;
  }
}
?>
<?php include 'footer.php'; ?>