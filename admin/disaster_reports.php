<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "Admin")
{
    header("Location: ../login.php");
    exit();
}

/* Approve report */
if(isset($_GET['approve']))
{
    $id = (int)$_GET['approve'];

    mysqli_query($conn, "
    UPDATE disaster_report
    SET status='Approved'
    WHERE report_id='$id'
    ");

    header("Location: disaster_reports.php");
    exit();
}

$sql = mysqli_query($conn, "
SELECT
disaster_report.*,
users.name
FROM disaster_report
INNER JOIN victim ON disaster_report.victim_id=victim.victim_id
INNER JOIN users ON victim.user_id=users.user_id
ORDER BY report_id DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Disaster Reports</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">Disaster Reports</h2>

<div class="table-box">

<table>
<tr>
<th>ID</th>
<th>Victim</th>
<th>Area</th>
<th>Disaster</th>
<th>Victims</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($sql))
{
    $status = $row['status'];
    $id = (int)$row['report_id'];
?>
<tr>
<td><?php echo $id; ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['area_name']); ?></td>
<td><?php echo htmlspecialchars($row['disaster_type']); ?></td>
<td><?php echo (int)$row['number_of_victims']; ?></td>
<td><?php echo htmlspecialchars($row['report_date']); ?></td>
<td>
<?php if($status == "Approved"){ ?>
<span class="status-badge status-approved">Approved</span>
<?php } else { ?>
<span class="status-badge status-pending">Pending</span>
<?php } ?>
</td>
<td>
<div class="action-cell">
<?php if($status != "Approved"){ ?>
<a class="approve-btn"
href="?approve=<?php echo $id; ?>"
onclick="return confirm('Approve this disaster report?');">
<i class="fa-solid fa-check"></i> Approve
</a>
<?php } else { ?>
<span class="action-none">Approved</span>
<?php } ?>
</div>
</td>
</tr>
<?php } ?>

</table>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
