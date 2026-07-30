<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "Victim")
{
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$getVictim = mysqli_query($conn, "
SELECT victim_id
FROM victim
WHERE user_id='$user_id'
");

$victim = mysqli_fetch_assoc($getVictim);

if(!$victim)
{
    echo "<script>
    alert('Victim profile not found. Please contact admin.');
    window.location='dashboard.php';
    </script>";
    exit();
}

$victim_id = (int)$victim['victim_id'];

$requests = mysqli_query($conn, "
SELECT *
FROM resource_request
WHERE victim_id='$victim_id'
ORDER BY request_id DESC
");

$total = mysqli_num_rows($requests);
$page_title = "Track Requests";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Resource Requests</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">My Resource Requests</h2>
<p style="color:#64748b;margin-bottom:25px;">
Track all resources you have requested.
</p>

<div class="table-box">

<div class="table-meta">
<span>Total Requests: <strong><?php echo (int)$total; ?></strong></span>
</div>

<?php if($total > 0){ ?>

<div class="table-responsive">
<table>
<tr>
<th>ID</th>
<th>Resource</th>
<th>Category</th>
<th>Unit</th>
<th>Quantity</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($requests))
{
    $status = $row['status'];
    $statusClass = strtolower(str_replace(' ', '-', $status));
?>
<tr>
<td><?php echo (int)$row['request_id']; ?></td>
<td><?php echo htmlspecialchars($row['resource_name']); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>
<td><?php echo htmlspecialchars($row['unit']); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo htmlspecialchars($row['request_date']); ?></td>
<td>
<span class="status-badge status-<?php echo htmlspecialchars($statusClass); ?>">
<?php echo htmlspecialchars($status); ?>
</span>
</td>
</tr>
<?php } ?>

</table>
</div>

<?php } else { ?>

<div class="empty-state">
<i class="fa-solid fa-inbox"></i>
<h3>No requests yet</h3>
<p>You have not requested any resources.</p>
<a href="request_resource.php" class="btn">
<i class="fa-solid fa-plus"></i> Request Resources
</a>
</div>

<?php } ?>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
