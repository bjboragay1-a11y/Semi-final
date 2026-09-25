<?php
// delete_task.php - Delete a task
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php?msg=" . urlencode("Task deleted successfully!"));
    exit;
}

header("Location: index.php");
exit;
?>
