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

/*================ SEARCH ================*/

$search="";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);

    $sql="

    SELECT

    volunteer.volunteer_id,
    volunteer.availability_status,

    users.user_id,
    users.name,
    users.email,
    users.phone

    FROM volunteer

    INNER JOIN users

    ON volunteer.user_id=users.user_id

    WHERE

    users.name LIKE '%$search%'

    OR

    users.email LIKE '%$search%'

    ORDER BY volunteer.volunteer_id DESC

    ";

}
else
{

    $sql="

    SELECT

    volunteer.volunteer_id,
    volunteer.availability_status,

    users.user_id,
    users.name,
    users.email,
    users.phone

    FROM volunteer

    INNER JOIN users

    ON volunteer.user_id=users.user_id

    ORDER BY volunteer.volunteer_id DESC

    ";

}

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Volunteer Management</title>

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

Volunteer Management

</h2>

<form method="GET">

<input

type="text"

name="search"

placeholder="Search Volunteer"

style="padding:12px;width:350px;border:1px solid #ccc;border-radius:8px;">

<button

style="padding:12px 22px;
background:#06B6D4;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

Search

</button>

</form>

<br>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Availability</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['volunteer_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td>

<?php

echo $row['availability_status'];

?>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>

</html>