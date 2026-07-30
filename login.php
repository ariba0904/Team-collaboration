<?php
session_start();
include("config/database.php");
if(isset($_POST['login']))
{

    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result)==1)
{

    $row = mysqli_fetch_assoc($result);


    if(password_verify($password,$row['password']))
    {

        $_SESSION['user_id'] = $row['user_id'];

        $_SESSION['name'] = $row['name'];

        $_SESSION['role'] = $row['role'];

        if($row['role']=="Admin")
        {
            header("Location: admin/dashboard.php");
            exit();
        }
        elseif($row['role']=="Volunteer")
        {
            header("Location: volunteer/dashboard.php");
            exit();
        }
        else
        {
            header("Location: victim/dashboard.php");
            exit();
        }

    }
    else
    {
        echo "<script>alert('Incorrect Password');</script>";
    }

}
else
{
    echo "<script>alert('Email Not Found');</script>";
}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Disaster Relief & Resource Management System</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <div class="login-header">

            <i class="fa-solid fa-shield-heart"></i>

            <h2>Welcome Back</h2>

            <p>Login to continue</p>

        </div>

        <form action="" method="POST">

            <div class="input-box">

                <i class="fa-solid fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter Email"
                    required>

            </div>

            <div class="input-box">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter Password"
                    required>

            </div>

            <button type="submit" name="login">

                Login

            </button>

        </form>

        <div class="register-link">

            Don't have an account?

            <a href="register.php">

                Register

            </a>

        </div>

    </div>

</div>

<script src="js/script.js"></script>

</body>

</html>