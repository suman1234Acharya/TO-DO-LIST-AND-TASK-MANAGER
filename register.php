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

$message = "";

if(isset($_POST['register']))
{
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);

    if($password != $confirm_password)
    {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    }
    else
    {
        // Check Email Exists
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check)>0)
        {
            $message = "<div class='alert alert-warning'>Email already registered!</div>";
        }
        else
        {
            // Insert User
            $sql = "INSERT INTO users(full_name,email,password)
                    VALUES('$fullname','$email','$password')";

            if(mysqli_query($conn,$sql))
            {
                $message = "<div class='alert alert-success'>
                Registration Successful!
                <br><a href='index.php'>Click here to Login</a>
                </div>";
            }
            else
            {
                $message = "<div class='alert alert-danger'>Registration Failed!</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>User Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f2f2f2;
}

.register-box{

width:450px;

margin:50px auto;

background:white;

padding:30px;

border-radius:10px;

box-shadow:0px 0px 10px gray;

}

h2{

text-align:center;

margin-bottom:20px;

}

.btn-success{

width:100%;

}

</style>

</head>

<body>

<div class="register-box">

<h2>Create Account</h2>

<?php

echo $message;

?>

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<input type="text" name="fullname" class="form-control" required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email" name="email" class="form-control" required>

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" name="password" class="form-control" required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input type="password" name="confirm_password" class="form-control" required>

</div>

<input type="submit" name="register" value="Register" class="btn btn-success">

</form>

<hr>

<p align="center">

Already have an account?

<a href="index.php">Login Here</a>

</p>

</div>

</body>

</html>
