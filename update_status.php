<?php
// update_status.php - Toggle task status between Pending and Completed
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $current_status = $_POST['current_status'];

    $new_status = ($current_status === 'Pending') ? 'Completed' : 'Pending';

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);

    header("Location: index.php?msg=" . urlencode("Status updated to $new_status!"));
    exit;
}

header("Location: index.php");
exit;
?>
