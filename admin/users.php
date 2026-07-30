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

/*================ DELETE USER ================*/

if(isset($_GET['delete']))
{
    $id = (int)$_GET['delete'];

    mysqli_query($conn,"DELETE FROM users WHERE user_id='$id'");

    header("Location: users.php");
    exit();
}

/*================ SEARCH ================*/

$search="";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);

    $sql="SELECT * FROM users
    WHERE
    name LIKE '%$search%'
    OR
    email LIKE '%$search%'
    OR
    role LIKE '%$search%'
    ORDER BY user_id DESC";
}
else
{
    $sql="SELECT * FROM users
    ORDER BY user_id DESC";
}

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>User Management</title>

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

<h2 style="margin-bottom:25px;">User Management</h2>

<form method="GET">

<input
type="text"
name="search"
placeholder="Search by Name, Email or Role"
style="padding:12px;width:350px;border-radius:8px;border:1px solid #ccc;"
>

<button
style="padding:12px 20px;background:#06B6D4;color:white;border:none;border-radius:8px;cursor:pointer;">
Search
</button>

</form>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Role</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['user_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td><?php echo $row['role']; ?></td>

<td>

<a
href="users.php?delete=<?php echo $row['user_id']; ?>"
onclick="return confirm('Delete this user?')"
style="color:red;text-decoration:none;font-weight:bold;">
Delete
</a>

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