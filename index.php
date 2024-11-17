<?php
include 'header.php';
include 'includes/db.inc.php'; // Include the database connection file

// Initialize task variables and errors
$task_name = $description = $due_date = "";
$task_nameErr = $descriptionErr = $due_dateErr = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate task name
  if (empty($_POST['task_name'])) {
    $task_nameErr = "Task name is required";
  } else {
    $task_name = htmlspecialchars($_POST['task_name']);
  }

  // Validate description
  if (empty($_POST['description'])) {
    $descriptionErr = "Description is required";
  } else {
    $description = htmlspecialchars($_POST['description']);
  }

  // Validate due date
  if (empty($_POST['due_date'])) {
    $due_dateErr = "Due date is required";
  } else {
    $due_date = htmlspecialchars($_POST['due_date']);
  }

  // If no errors, insert the task into the database
  if (empty($task_nameErr) && empty($descriptionErr) && empty($due_dateErr)) {
    $query = "INSERT INTO tasks (task_name, description, due_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $task_name, $description, $due_date);

    if ($stmt->execute()) {
      $successMessage = "Task successfully added!";
      // Clear form data after submission
      $task_name = $description = $due_date = "";
    } else {
      $successMessage = "Error adding task. Please try again.";
    }
  }
}

// Fetch tasks from the database
$query = "SELECT * FROM tasks ORDER BY due_date ASC LIMIT 5"; // Get the 5 upcoming tasks
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDoWithMe</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0;">

  <section style="padding: 40px 20px; background-color: white; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
      <h2>Welcome to ToDoWithMe</h2>
      <p>Start managing your tasks efficiently today!</p>

      <!-- Success message after adding a task -->
      <?php if ($successMessage): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
          <?php echo $successMessage; ?>
        </div>
      <?php endif; ?>

      <!-- Add Task Form -->
      <h3>Add a New Task</h3>
      <form method="POST" action="index.php" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px;">
        <input type="text" name="task_name" id="task_name" placeholder="Task Name" value="<?php echo $task_name; ?>" style="padding: 10px; font-size: 1rem; border-radius: 5px; border: 1px solid #ddd;">
        <span style="color: red;"><?php echo $task_nameErr; ?></span>

        <textarea name="description" id="description" placeholder="Task Description" style="padding: 10px; font-size: 1rem; border-radius: 5px; border: 1px solid #ddd;"><?php echo $description; ?></textarea>
        <span style="color: red;"><?php echo $descriptionErr; ?></span>

        <input type="date" name="due_date" id="due_date" value="<?php echo $due_date; ?>" style="padding: 10px; font-size: 1rem; border-radius: 5px; border: 1px solid #ddd;">
        <span style="color: red;"><?php echo $due_dateErr; ?></span>

        <button type="submit" style="background-color: #007bff; color: white; padding: 10px; font-size: 1rem; border: none; border-radius: 5px; cursor: pointer;">Add Task</button>
      </form>

      <!-- Display Upcoming Tasks -->
      <h3>Upcoming Tasks</h3>
      <?php if ($result->num_rows > 0): ?>
        <ul style="list-style: none; padding: 0; text-align: left;">
          <?php while ($task = $result->fetch_assoc()): ?>
            <li style="background-color: #f9f9f9; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ddd;">
              <span style="font-weight: bold;"><?php echo htmlspecialchars($task['task_name']); ?></span><br>
              <span style="font-style: italic; color: #888;"><?php echo htmlspecialchars($task['due_date']); ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>No tasks found. <a href="add_task.php" style="color: #007bff;">Add your first task</a>.</p>
      <?php endif; ?>
    </div>
  </section>

  <?php
  include 'footer.php';
  ?>