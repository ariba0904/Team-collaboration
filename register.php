<?php

session_start();
include("config/database.php");

if(isset($_POST['register']))
{
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $nid = mysqli_real_escape_string($conn, trim($_POST['nid']));
    $role = $_POST['role'];

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password Match Check
    if($password != $confirm_password)
    {
        echo "<script>alert('Passwords do not match!');</script>";
    }
    else
    {
        // Email Check
        $emailCheck = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($emailCheck) > 0)
        {
            echo "<script>alert('Email already exists!');</script>";
        }
        else
        {
            // Phone Check
            $phoneCheck = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");

            if(mysqli_num_rows($phoneCheck) > 0)
            {
                echo "<script>alert('Phone number already exists!');</script>";
            }
            else
            {
                // NID Check
                $nidCheck = mysqli_query($conn, "SELECT * FROM users WHERE nid_number='$nid'");

                if(mysqli_num_rows($nidCheck) > 0)
                {
                    echo "<script>alert('NID already exists!');</script>";
                }
                else
                {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $today = date("Y-m-d");

                    $sql = "INSERT INTO users
                    (name,email,phone,address,password,registration_date,nid_number,role)

                    VALUES

                    ('$name','$email','$phone','$address',
                    '$hashedPassword','$today','$nid','$role')";

                    if(mysqli_query($conn, $sql))
                    {
                        $user_id = mysqli_insert_id($conn);

                        if($role=="Volunteer")
                        {
                            mysqli_query($conn,"INSERT INTO volunteer(user_id)
                            VALUES('$user_id')");
                        }

                        elseif($role=="Victim")
                        {
                            mysqli_query($conn,"INSERT INTO victim(user_id)
                            VALUES('$user_id')");
                        }

                        echo "<script>
                        alert('Registration Successful');
                        window.location='login.php';
                        </script>";
                    }
                    else
                    {
                        echo "<script>alert('Registration Failed');</script>";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="register-container">

    <div class="register-box">

        <h2>Create Account</h2>

        <form method="POST">

            <input type="text" name="name" placeholder="Full Name" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" name="phone" placeholder="Phone Number" required>

            <textarea name="address" placeholder="Address" required></textarea>

            <input type="text" name="nid" placeholder="NID Number" required>

            <select name="role" required>

                <option value="">Select Role</option>

                <option value="Volunteer">Volunteer</option>

                <option value="Victim">Victim</option>

            </select>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit" name="register">

                Register

            </button>

        </form>

        <div class="register-link">

            Already have an account?

            <a href="login.php">Login</a>

        </div>

    </div>

</div>

</body>

</html>