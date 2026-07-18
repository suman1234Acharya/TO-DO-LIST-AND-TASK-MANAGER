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

// Check Task ID
if (!isset($_GET['id'])) {
    header("Location: view_tasks.php");
    exit();
}

$task_id = (int)$_GET['id'];

// Fetch Categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Fetch Task
$sql = "SELECT * FROM tasks WHERE task_id='$task_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Task not found.");
}

$task = mysqli_fetch_assoc($result);

$message = "";

// Update Task
if (isset($_POST['update'])) {

    $category_id = $_POST['category_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];

    $update = "UPDATE tasks SET
                category_id='$category_id',
                title='$title',
                description='$description',
                priority='$priority',
                status='$status',
                due_date='$due_date',
                due_time='$due_time'
                WHERE task_id='$task_id'
                AND user_id='$user_id'";

    if (mysqli_query($conn, $update)) {

        header("Location: view_tasks.php?updated=1");
        exit();

    } else {

        $message = "<div class='alert alert-danger'>
                    Failed to Update Task!
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Edit Task</title>

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

<h2 class="text-center mb-4">Edit Task</h2>

<?php echo $message; ?>

<form method="POST">

<div class="mb-3">

<label>Category</label>

<select name="category_id" class="form-control" required>

<?php
while($row = mysqli_fetch_assoc($categories))
{
?>

<option value="<?php echo $row['category_id']; ?>"
<?php
if($row['category_id']==$task['category_id'])
echo "selected";
?>>

<?php echo $row['category_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="mb-3">

<label>Task Title</label>

<input
type="text"
name="title"
class="form-control"
value="<?php echo htmlspecialchars($task['title']); ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label>Priority</label>

<select name="priority" class="form-control">

<option value="Low"
<?php if($task['priority']=="Low") echo "selected"; ?>>
Low
</option>

<option value="Medium"
<?php if($task['priority']=="Medium") echo "selected"; ?>>
Medium
</option>

<option value="High"
<?php if($task['priority']=="High") echo "selected"; ?>>
High
</option>

</select>

</div>

<div class="col-md-6">

<label>Status</label>

<select name="status" class="form-control">

<option value="Pending"
<?php if($task['status']=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="In Progress"
<?php if($task['status']=="In Progress") echo "selected"; ?>>
In Progress
</option>

<option value="Completed"
<?php if($task['status']=="Completed") echo "selected"; ?>>
Completed
</option>

</select>

</div>

</div>

<br>

<div class="row">

<div class="col-md-6">

<label>Due Date</label>

<input
type="date"
name="due_date"
class="form-control"
value="<?php echo $task['due_date']; ?>">

</div>

<div class="col-md-6">

<label>Due Time</label>

<input
type="time"
name="due_time"
class="form-control"
value="<?php echo $task['due_time']; ?>">

</div>

</div>

<br>

<input
type="submit"
name="update"
value="Update Task"
class="btn btn-success">

<a href="view_tasks.php" class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</div>

</div>

</body>
</html>
