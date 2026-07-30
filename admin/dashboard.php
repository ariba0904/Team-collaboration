
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

/*================ Dashboard Statistics ================*/

$totalUsers=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));

$totalVolunteers=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM volunteer"));

$totalVictims=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM victim"));

$totalReports=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM disaster_report"));

$totalResources=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM resource"));

$totalRequests=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM resource_request"));

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

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

<div class="cards">

<div class="card">

<i class="fa-solid fa-users"></i>

<h3>Total Users</h3>

<p><?php echo $totalUsers; ?></p>

</div>

<div class="card">

<i class="fa-solid fa-user-group"></i>

<h3>Volunteers</h3>

<p><?php echo $totalVolunteers; ?></p>

</div>

<div class="card">

<i class="fa-solid fa-house-flood-water"></i>

<h3>Victims</h3>

<p><?php echo $totalVictims; ?></p>

</div>

<div class="card">

<i class="fa-solid fa-triangle-exclamation"></i>

<h3>Reports</h3>

<p><?php echo $totalReports; ?></p>

</div>

<div class="card">

<i class="fa-solid fa-box-open"></i>

<h3>Resources</h3>

<p><?php echo $totalResources; ?></p>

</div>

<div class="card">

<i class="fa-solid fa-file-circle-check"></i>

<h3>Requests</h3>

<p><?php echo $totalRequests; ?></p>

</div>

</div>

<div class="table-box">

<h2>Recent Users</h2>

<table>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

</tr>

<?php

$result=mysqli_query($conn,"
SELECT *
FROM users
ORDER BY user_id DESC
LIMIT 5
");

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['user_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['role']; ?></td>

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