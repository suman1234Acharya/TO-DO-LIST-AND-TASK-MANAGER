<?php
session_start();

// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "todo_task_manager");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// Task Counts
$total = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM tasks WHERE user_id='$user_id'"));

$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM tasks
WHERE user_id='$user_id' AND status='Pending'"));

$progress = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM tasks
WHERE user_id='$user_id' AND status='In Progress'"));

$completed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM tasks
WHERE user_id='$user_id' AND status='Completed'"));

// Recent Tasks
$tasks = mysqli_query($conn,
"SELECT * FROM tasks
WHERE user_id='$user_id'
ORDER BY due_date ASC
LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f5f5;
}

.card{
    box-shadow:0px 0px 8px rgba(0,0,0,.15);
}

.navbar{
    margin-bottom:25px;
}
</style>

</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">

<span class="navbar-brand">
To-Do List & Task Manager
</span>

<div>
<span class="text-white me-3">
Welcome, <?php echo $full_name; ?>
</span>

<a href="logout.php" class="btn btn-danger btn-sm">
Logout
</a>

</div>

</div>
</nav>

<div class="container">

<div class="row">

<div class="col-md-3">
<div class="card text-center p-3">
<h5>Total Tasks</h5>
<h2><?php echo $total['total']; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-3">
<h5>Pending</h5>
<h2><?php echo $pending['total']; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-3">
<h5>In Progress</h5>
<h2><?php echo $progress['total']; ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-3">
<h5>Completed</h5>
<h2><?php echo $completed['total']; ?></h2>
</div>
</div>

</div>

<br>

<a href="add_task.php" class="btn btn-primary">
+ Add New Task
</a>

<a href="view_tasks.php" class="btn btn-success">
View All Tasks
</a>

<hr>

<h3>Recent Tasks</h3>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Title</th>

<th>Priority</th>

<th>Status</th>

<th>Due Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php
while($row=mysqli_fetch_assoc($tasks))
{
?>

<tr>

<td><?php echo $row['task_id']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['priority']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['due_date']; ?></td>

<td>

<a href="edit_task.php?id=<?php echo $row['task_id']; ?>"
class="btn btn-warning btn-sm">
Edit
</a>

<a href="delete_task.php?id=<?php echo $row['task_id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this task?');">
Delete
</a>

</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</body>
</html>
