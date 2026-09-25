<?php
// edit_task.php - Edit an existing task
require_once "config.php";

$error = "";

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $due_date = $_POST['due_date'] ?: null;

    if ($task_name === '') {
        $error = "Task name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE tasks SET task_name = ?, description = ?, status = ?, due_date = ? WHERE id = ?");
        $stmt->execute([$task_name, $description, $status, $due_date, $id]);

        header("Location: index.php?msg=" . urlencode("Task updated successfully!"));
        exit;
    }
    $task = $_POST; // keep form filled with submitted values on error
} else {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Task</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>✏️ Edit Task</h1>
    <p class="subtitle">Update the task details</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form class="form-box" method="POST" action="edit_task.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Pending" <?php echo $task['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Completed" <?php echo $task['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-edit">Update Task</button>
            <a href="index.php" class="btn btn-back">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
