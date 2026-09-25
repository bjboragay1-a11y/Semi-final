<?php
// add_task.php - Add a new task
require_once "config.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $due_date = $_POST['due_date'] ?: null;

    if ($task_name === '') {
        $error = "Task name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tasks (task_name, description, status, due_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$task_name, $description, $status, $due_date]);

        header("Location: index.php?msg=" . urlencode("Task added successfully!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Task</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>➕ Add Task</h1>
    <p class="subtitle">Fill in the details below</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form class="form-box" method="POST" action="add_task.php">
        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="task_name" value="<?php echo isset($_POST['task_name']) ? htmlspecialchars($_POST['task_name']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" value="<?php echo isset($_POST['due_date']) ? htmlspecialchars($_POST['due_date']) : ''; ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-add">Save Task</button>
            <a href="index.php" class="btn btn-back">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
