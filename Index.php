<?php
session_start();

// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "todo_task_manager";

$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$error = "";

// Login
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['full_name'] = $row['full_name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>To-Do List & Task Manager</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f5f5;
}

.login-box{
    width:400px;
    margin:80px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.btn-primary{
    width:100%;
}
</style>

</head>
<body>

<div class="login-box">

<h2>To-Do List & Task Manager</h2>

<?php
if($error!=""){
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<input type="submit" name="login" value="Login" class="btn btn-primary">

</form>

<hr>

<p class="text-center">
Don't have an account?
<a href="register.php">Register Here</a>
</p>

</div>

</body>
</html>
