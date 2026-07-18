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
$message = "";

// Fetch Categories
$category = mysqli_query($conn, "SELECT * FROM categories");

// Add Task
if(isset($_POST['save']))
{
    $category_id = $_POST['category_id'];
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];

    $sql = "INSERT INTO tasks
    (user_id, category_id, title, description, priority, status, due_date, due_time)

    VALUES
    ('$user_id','$category_id','$title','$description',
    '$priority','$status','$due_date','$due_time')";

    if(mysqli_query($conn,$sql))
    {
        $message = "<div class='alert alert-success'>
        Task Added Successfully!
        </div>";
    }
    else
    {
        $message = "<div class='alert alert-danger'>
        Failed to Add Task!
        </div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Add Task</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f4f4;
}

.container{

margin-top:40px;

}

.card{

padding:25px;

box-shadow:0px 0px 10px gray;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card">

<h2 class="text-center mb-4">Add New Task</h2>

<?php echo $message; ?>

<form method="POST">

<div class="mb-3">

<label>Category</label>

<select name="category_id" class="form-control" required>

<option value="">Select Category</option>

<?php

mysqli_data_seek($category,0);

while($row=mysqli_fetch_assoc($category))
{
?>

<option value="<?php echo $row['category_id']; ?>">
<?php echo $row['category_name']; ?>
</option>

<?php
}
?>

</select>

</div>

<div class="mb-3">

<label>Task Title</label>

<input type="text"
name="title"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="4"></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label>Priority</label>

<select name="priority"
class="form-control">

<option>Low</option>

<option selected>Medium</option>

<option>High</option>

</select>

</div>

<div class="col-md-6">

<label>Status</label>

<select name="status"
class="form-control">

<option selected>Pending</option>

<option>In Progress</option>

<option>Completed</option>

</select>

</div>

</div>

<br>

<div class="row">

<div class="col-md-6">

<label>Due Date</label>

<input type="date"
name="due_date"
class="form-control">

</div>

<div class="col-md-6">

<label>Due Time</label>

<input type="time"
name="due_time"
class="form-control">

</div>

</div>

<br>

<input
type="submit"
name="save"
value="Save Task"
class="btn btn-success">

<a href="dashboard.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</body>

</html>
