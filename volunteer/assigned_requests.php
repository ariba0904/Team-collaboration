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

$getVolunteer = mysqli_query($conn, "
SELECT volunteer_id
FROM volunteer
WHERE user_id='$user_id'
");

$volunteer = mysqli_fetch_assoc($getVolunteer);

if(!$volunteer)
{
    echo "<script>alert('Volunteer profile not found'); window.location='dashboard.php';</script>";
    exit();
}

$volunteer_id = (int)$volunteer['volunteer_id'];

$requests = mysqli_query($conn, "
SELECT
resource_request.*,
users.name AS victim_name,
users.phone AS victim_phone,
users.address AS victim_address
FROM resource_request
INNER JOIN victim ON resource_request.victim_id=victim.victim_id
INNER JOIN users ON victim.user_id=users.user_id
WHERE resource_request.volunteer_id='$volunteer_id'
ORDER BY request_id DESC
");

$total = mysqli_num_rows($requests);
$page_title = "Assigned Requests";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assigned Requests</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/volunteer_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/volunteer_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Assigned Requests</h2>
<p style="color:#64748b;margin-bottom:25px;">
Accept assigned deliveries and mark them complete after delivery.
</p>

<div class="table-box">

<div class="table-meta">
<span>Total Assigned: <strong><?php echo (int)$total; ?></strong></span>
</div>

<?php if($total > 0){ ?>

<div class="table-responsive">
<table>
<tr>
<th>ID</th>
<th>Victim</th>
<th>Phone</th>
<th>Address</th>
<th>Resource</th>
<th>Category</th>
<th>Unit</th>
<th>Quantity</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($requests))
{
    $status = $row['status'];
    $statusClass = strtolower(str_replace(' ', '-', $status));
    $id = (int)$row['request_id'];
?>
<tr>
<td><?php echo $id; ?></td>
<td><?php echo htmlspecialchars($row['victim_name']); ?></td>
<td><?php echo htmlspecialchars($row['victim_phone']); ?></td>
<td><?php echo htmlspecialchars($row['victim_address']); ?></td>
<td><?php echo htmlspecialchars($row['resource_name']); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>
<td><?php echo htmlspecialchars($row['unit']); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td>
<span class="status-badge status-<?php echo htmlspecialchars($statusClass); ?>">
<?php echo htmlspecialchars($status); ?>
</span>
</td>
<td>
<div class="action-cell">

<?php if($status == "Assigned"){ ?>

<a class="approve-btn"
href="accept_delivery.php?id=<?php echo $id; ?>"
onclick="return confirm('Accept this delivery and start transit?');">
<i class="fa-solid fa-truck"></i> Accept
</a>

<?php } elseif($status == "In Transit"){ ?>

<a class="assign-btn"
href="complete_delivery.php?id=<?php echo $id; ?>"
onclick="return confirm('Mark this delivery as completed?');">
<i class="fa-solid fa-circle-check"></i> Complete
</a>

<?php } elseif($status == "Delivered"){ ?>

<span class="action-none">Completed</span>

<?php } else { ?>

<span class="action-none">-</span>

<?php } ?>

</div>
</td>
</tr>
<?php } ?>

</table>
</div>

<?php } else { ?>

<div class="empty-state">
<i class="fa-solid fa-box"></i>
<h3>No assigned requests</h3>
<p>Admin has not assigned any deliveries to you yet.</p>
</div>

<?php } ?>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
