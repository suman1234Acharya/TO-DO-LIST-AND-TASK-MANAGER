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

// Fetch Tasks
$sql = "SELECT tasks.*, categories.category_name
        FROM tasks
        LEFT JOIN categories
        ON tasks.category_id = categories.category_id
        WHERE tasks.user_id = '$user_id'
        ORDER BY tasks.task_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>View Tasks</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
}

.container{
    margin-top:40px;
}

.card{
    padding:20px;
    box-shadow:0px 0px 10px gray;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2 class="text-center mb-4">My Tasks</h2>

<div class="mb-3">

<a href="dashboard.php" class="btn btn-primary">
Dashboard
</a>

<a href="add_task.php" class="btn btn-success">
Add New Task
</a>

<a href="logout.php" class="btn btn-danger float-end">
Logout
</a>

</div>

<table class="table table-bordered table-hover table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Category</th>

<th>Title</th>

<th>Description</th>

<th>Priority</th>

<th>Status</th>

<th>Due Date</th>

<th>Due Time</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
?>

<tr>

<td><?php echo $row['task_id']; ?></td>

<td><?php echo $row['category_name']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>
<span class="badge bg-warning text-dark">
<?php echo $row['priority']; ?>
</span>
</td>

<td>

<?php

if($row['status']=="Completed")
{
    echo "<span class='badge bg-success'>Completed</span>";
}
elseif($row['status']=="In Progress")
{
    echo "<span class='badge bg-primary'>In Progress</span>";
}
else
{
    echo "<span class='badge bg-danger'>Pending</span>";
}

?>

</td>

<td><?php echo $row['due_date']; ?></td>

<td><?php echo $row['due_time']; ?></td>

<td>

<a href="edit_task.php?id=<?php echo $row['task_id']; ?>" class="btn btn-warning btn-sm">
Edit
</a>

<a href="delete_task.php?id=<?php echo $row['task_id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this task?');">
Delete
</a>

</td>

</tr>

<?php

    }
}
else
{
?>

<tr>

<td colspan="9" class="text-center">
No Tasks Found
</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>

</html>
