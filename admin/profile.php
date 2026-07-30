
<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="Admin")
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*================ GET ADMIN INFO ================*/

$getUser = mysqli_query($conn,"
SELECT *
FROM users
WHERE user_id='$user_id'
");

$user = mysqli_fetch_assoc($getUser);

/*================ UPDATE PROFILE ================*/

if(isset($_POST['update_profile']))
{

    $name = mysqli_real_escape_string($conn,$_POST['name']);

    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $phone = mysqli_real_escape_string($conn,$_POST['phone']);

    mysqli_query($conn,"

    UPDATE users

    SET

    name='$name',

    email='$email',

    phone='$phone'

    WHERE

    user_id='$user_id'

    ");

    echo "<script>

    alert('Profile Updated Successfully');

    window.location='profile.php';

    </script>";

    exit();

}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Profile</title>

<link rel="stylesheet" href="../css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">

My Profile

</h2>

<!-- Profile Card -->

<div style="background:white;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,.08);
overflow:hidden;
margin-bottom:30px;">

<!-- Blue Header -->

<div style="background:linear-gradient(135deg,#06B6D4,#2563EB);
height:130px;
position:relative;">

<div style="
position:absolute;
left:40px;
bottom:-45px;
width:90px;
height:90px;
background:white;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
border:5px solid white;">

<i class="fa-solid fa-user"
style="font-size:42px;color:#06B6D4;"></i>

</div>

</div>

<div style="padding:60px 40px 30px;">

<h2 style="margin:0;">

<?php echo $user['name']; ?>

</h2>

<p style="color:#6b7280;
margin:8px 0;">

<i class="fa-solid fa-envelope"></i>

<?php echo $user['email']; ?>

</p>

<span style="
background:#22C55E;
color:white;
padding:6px 18px;
border-radius:30px;
font-size:14px;
font-weight:bold;">

<?php echo $user['role']; ?>

</span>

</div>

</div>

<!-- Edit Form -->

<div class="table-box">

<h3 style="margin-bottom:20px;">

Edit Profile

</h3>

<form method="POST">

<label><b>Full Name</b></label>

<input
type="text"
name="name"
value="<?php echo $user['name']; ?>"
required
style="width:100%;
padding:13px;
margin:10px 0 20px;
border:1px solid #ccc;
border-radius:8px;">

<label><b>Email</b></label>

<input
type="email"
name="email"
value="<?php echo $user['email']; ?>"
required
style="width:100%;
padding:13px;
margin:10px 0 20px;
border:1px solid #ccc;
border-radius:8px;">

<label><b>Phone Number</b></label>

<input
type="text"
name="phone"
value="<?php echo $user['phone']; ?>"
required
style="width:100%;
padding:13px;
margin:10px 0 20px;
border:1px solid #ccc;
border-radius:8px;">

<label><b>Role</b></label>

<input
type="text"
value="<?php echo $user['role']; ?>"
readonly
style="width:100%;
padding:13px;
margin:10px 0 25px;
background:#F3F4F6;
border:1px solid #ccc;
border-radius:8px;">
<button
type="submit"
name="update_profile"
style="
background:linear-gradient(135deg,#06B6D4,#2563EB);
color:white;
border:none;
padding:14px 35px;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;
transition:.3s;">

<i class="fa-solid fa-floppy-disk"></i>

Update Profile

</button>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>

</html>