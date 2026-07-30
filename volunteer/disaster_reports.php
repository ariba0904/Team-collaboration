<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="Volunteer")
{
    header("Location: ../login.php");
    exit();
}

$sql=mysqli_query($conn,"

SELECT

disaster_report.*,
users.name

FROM disaster_report

INNER JOIN victim

ON disaster_report.victim_id=victim.victim_id

INNER JOIN users

ON victim.user_id=users.user_id

WHERE disaster_report.status='Approved'

ORDER BY report_id DESC

");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Approved Disaster Reports</title>

<link rel="stylesheet"
href="../css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="dashboard">

<?php include("../includes/volunteer_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/volunteer_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">

Approved Disaster Reports

</h2>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Victim</th>

<th>Area</th>

<th>Disaster</th>

<th>No. of Victims</th>

<th>Date</th>

<th>Status</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($sql))
{

?>

<tr>

<td><?php echo $row['report_id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['area_name']; ?></td>

<td><?php echo $row['disaster_type']; ?></td>

<td><?php echo $row['number_of_victims']; ?></td>

<td><?php echo $row['report_date']; ?></td>

<td><?php echo $row['status']; ?></td>

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