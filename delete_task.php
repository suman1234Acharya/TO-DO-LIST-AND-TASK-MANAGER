<?php
session_start();

// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "todo_task_manager");

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Check Task ID
if (!isset($_GET['id'])) {
    header("Location: view_tasks.php");
    exit();
}

$task_id = (int)$_GET['id'];

// Verify task belongs to logged-in user
$check = mysqli_query($conn,
"SELECT * FROM tasks
WHERE task_id='$task_id'
AND user_id='$user_id'");

if (mysqli_num_rows($check) == 0) {
    die("Task not found or access denied.");
}

// Delete Task
$delete = mysqli_query($conn,
"DELETE FROM tasks
WHERE task_id='$task_id'
AND user_id='$user_id'");

if ($delete) {
    header("Location: view_tasks.php?deleted=1");
    exit();
} else {
    echo "<h3>Failed to delete task.</h3>";
}
?>
