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

/* Admin can also assign / mark delivered from delivery page */
if(isset($_GET['deliver']))
{
    $id = (int)$_GET['deliver'];

    $req = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT volunteer_id
    FROM resource_request
    WHERE request_id='$id'
    AND status='In Transit'
    "));

    if($req)
    {
        mysqli_query($conn, "
        UPDATE resource_request
        SET status='Delivered'
        WHERE request_id='$id'
        AND status='In Transit'
        ");

        if(!empty($req['volunteer_id']))
        {
            $vid = (int)$req['volunteer_id'];
            mysqli_query($conn, "
            UPDATE volunteer
            SET availability_status='Available'
            WHERE volunteer_id='$vid'
            ");
        }
    }

    header("Location: delivery_management.php");
    exit();
}

$deliveries = mysqli_query($conn, "
SELECT
resource_request.*,
users.name AS victim_name,
vusers.name AS volunteer_name
FROM resource_request
INNER JOIN victim ON resource_request.victim_id=victim.victim_id
INNER JOIN users ON victim.user_id=users.user_id
LEFT JOIN volunteer ON resource_request.volunteer_id=volunteer.volunteer_id
LEFT JOIN users AS vusers ON volunteer.user_id=vusers.user_id
WHERE resource_request.status IN ('Assigned','In Transit','Delivered')
ORDER BY request_id DESC
");

$total = mysqli_num_rows($deliveries);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delivery Management</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Delivery Management</h2>
<p style="color:#64748b;margin-bottom:25px;">
Track assigned, in-transit, and completed resource deliveries.
</p>

<div class="table-box">

<div class="table-meta">
<span>Total Deliveries: <strong><?php echo (int)$total; ?></strong></span>
</div>

<?php if($total > 0){ ?>

<div class="table-responsive">
<table>
<tr>
<th>ID</th>
<th>Victim</th>
<th>Volunteer</th>
<th>Resource</th>
<th>Category</th>
<th>Unit</th>
<th>Quantity</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($deliveries))
{
    $status = $row['status'];
    $statusClass = strtolower(str_replace(' ', '-', $status));
    $id = (int)$row['request_id'];
?>
<tr>
<td><?php echo $id; ?></td>
<td><?php echo htmlspecialchars($row['victim_name']); ?></td>
<td>
<?php
echo !empty($row['volunteer_name'])
    ? htmlspecialchars($row['volunteer_name'])
    : '<span style="color:#94a3b8;">Not Assigned</span>';
?>
</td>
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

<a class="assign-btn"
href="assign_volunteer.php?request=<?php echo $id; ?>">
<i class="fa-solid fa-user-pen"></i> Reassign
</a>

<?php } elseif($status == "In Transit"){ ?>

<a class="approve-btn"
href="?deliver=<?php echo $id; ?>"
onclick="return confirm('Mark this delivery as completed?');">
<i class="fa-solid fa-box-open"></i> Mark Delivered
</a>

<?php } else { ?>

<span class="action-none">Completed</span>

<?php } ?>

</div>
</td>
</tr>
<?php } ?>

</table>
</div>

<?php } else { ?>

<div class="empty-state">
<i class="fa-solid fa-truck"></i>
<h3>No deliveries yet</h3>
<p>Assigned deliveries will appear here.</p>
</div>

<?php } ?>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
