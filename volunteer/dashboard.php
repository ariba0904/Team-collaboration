<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "Volunteer")
{
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$getVolunteer = mysqli_query($conn,"
SELECT volunteer_id, availability_status
FROM volunteer
WHERE user_id='$user_id'
");

$volunteer = mysqli_fetch_assoc($getVolunteer);

if(!$volunteer)
{
    echo "<script>
    alert('Volunteer profile not found. Please contact admin.');
    window.location='../logout.php';
    </script>";
    exit();
}

$volunteer_id = (int)$volunteer['volunteer_id'];

$assigned = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM resource_request
WHERE volunteer_id='$volunteer_id'
AND status IN ('Assigned','In Transit','Delivered')
"));

$transit = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM resource_request
WHERE volunteer_id='$volunteer_id'
AND status='In Transit'
"));

$completed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM resource_request
WHERE volunteer_id='$volunteer_id'
AND status='Delivered'
"));

$recent = mysqli_query($conn,"
SELECT resource_request.*, users.name AS victim_name
FROM resource_request
INNER JOIN victim ON resource_request.victim_id=victim.victim_id
INNER JOIN users ON victim.user_id=users.user_id
WHERE resource_request.volunteer_id='$volunteer_id'
ORDER BY request_id DESC
LIMIT 5
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volunteer Dashboard</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/volunteer_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/volunteer_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Volunteer Dashboard</h2>
<p style="margin-bottom:25px;color:#64748b;">
Availability:
<strong><?php echo htmlspecialchars($volunteer['availability_status']); ?></strong>
</p>

<div class="cards">

<div class="card">
<i class="fa-solid fa-box"></i>
<h3>Assigned Deliveries</h3>
<p><?php echo (int)$assigned['total']; ?></p>
</div>

<div class="card">
<i class="fa-solid fa-truck-fast"></i>
<h3>In Transit</h3>
<p><?php echo (int)$transit['total']; ?></p>
</div>

<div class="card">
<i class="fa-solid fa-circle-check"></i>
<h3>Completed</h3>
<p><?php echo (int)$completed['total']; ?></p>
</div>

</div>

<div class="table-box">

<h2>Recent Assigned Deliveries</h2>

<table>
<tr>
<th>Request ID</th>
<th>Victim</th>
<th>Resource</th>
<th>Quantity</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php
if(mysqli_num_rows($recent) > 0)
{
    while($row = mysqli_fetch_assoc($recent))
    {
?>
<tr>
<td><?php echo $row['request_id']; ?></td>
<td><?php echo htmlspecialchars($row['victim_name']); ?></td>
<td><?php echo htmlspecialchars($row['resource_name']); ?> (<?php echo htmlspecialchars($row['category']); ?>)</td>
<td><?php echo $row['quantity']; ?> <?php echo htmlspecialchars($row['unit']); ?></td>
<td><?php echo $row['request_date']; ?></td>
<td><?php echo $row['status']; ?></td>
</tr>
<?php
    }
}
else
{
?>
<tr>
<td colspan="6">No Assigned Deliveries Yet</td>
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
