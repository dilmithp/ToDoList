<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'header.php';
include 'includes/conn.inc.php';

// Fetch task data from the database
$query = "SELECT * FROM tasks ORDER BY due_date ASC LIMIT 5";
$result = $conn->query($query);

if (!$result) {
  die("Query failed: " . $conn->error);
}
?>

<section class="home">
  <div class="container">
    <h2>Welcome to ToDoWithMe</h2>
    <p>Effortlessly organize your tasks and achieve more every day.</p>
    <a href="tasks.php" class="btn">Get Started</a>
  </div>
</section>

<section class="tasks-summary">
  <div class="container">
    <h3>Upcoming Tasks</h3>
    <?php if ($result->num_rows > 0): ?>
      <ul class="task-list">
        <?php while ($task = $result->fetch_assoc()): ?>
          <li>
            <span class="task-name"><?php echo htmlspecialchars($task['task_name']); ?></span>
            <span class="task-date"><?php echo htmlspecialchars($task['due_date']); ?></span>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No tasks found. <a href="add_task.php">Add your first task</a>.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>