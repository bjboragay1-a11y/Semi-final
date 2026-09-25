<?php
// index.php - View all tasks
require_once "config.php";

$stmt = $conn->prepare("SELECT * FROM tasks ORDER BY due_date ASC, id DESC");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task Manager</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📋 Task Manager</h1>
    <p class="subtitle">Add, edit, delete, and track your tasks</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="top-actions">
        <a href="add_task.php" class="btn btn-add">+ Add Task</a>
    </div>

    <?php if (count($tasks) === 0): ?>
        <p class="empty-msg">Wala pa gani'y task. Click "Add Task" para mag-add.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo $task['id']; ?></td>
                <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                <td><?php echo htmlspecialchars($task['description']); ?></td>
                <td><?php echo $task['due_date'] ? htmlspecialchars($task['due_date']) : '—'; ?></td>
                <td><span class="status <?php echo $task['status']; ?>"><?php echo $task['status']; ?></span></td>
                <td>
                    <div class="actions">
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-edit">Edit</a>

                        <form action="update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <input type="hidden" name="current_status" value="<?php echo $task['status']; ?>">
                            <button type="submit" class="btn btn-status">
                                <?php echo $task['status'] === 'Pending' ? 'Mark Completed' : 'Mark Pending'; ?>
                            </button>
                        </form>

                        <form action="delete_task.php" method="POST" style="display:inline;"
                              onsubmit="return confirm('Sigurado ka nga i-delete kini nga task?');">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
