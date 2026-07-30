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

/* Approve Request */
if(isset($_GET['approve']))
{
    $id = (int)$_GET['approve'];

    mysqli_query($conn, "
    UPDATE resource_request
    SET status='Approved'
    WHERE request_id='$id'
    AND status='Pending'
    ");

    header("Location: resource_requests.php");
    exit();
}

/* Reject Request */
if(isset($_GET['reject']))
{
    $id = (int)$_GET['reject'];

    mysqli_query($conn, "
    UPDATE resource_request
    SET status='Rejected',
        volunteer_id=NULL
    WHERE request_id='$id'
    AND status='Pending'
    ");

    header("Location: resource_requests.php");
    exit();
}

/* Mark Delivered (admin override) */
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

    header("Location: resource_requests.php");
    exit();
}

$requests = mysqli_query($conn, "
SELECT
resource_request.*,
users.name AS victim_name,
vusers.name AS volunteer_name
FROM resource_request
JOIN victim ON resource_request.victim_id=victim.victim_id
JOIN users ON victim.user_id=users.user_id
LEFT JOIN volunteer ON resource_request.volunteer_id=volunteer.volunteer_id
LEFT JOIN users AS vusers ON volunteer.user_id=vusers.user_id
ORDER BY request_id DESC
");

$total = mysqli_num_rows($requests);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resource Requests</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Resource Requests</h2>
<p style="color:#64748b;margin-bottom:25px;">
Approve or reject victim requests, then assign volunteers for delivery.
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
<th>Victim</th>
<th>Resource</th>
<th>Category</th>
<th>Unit</th>
<th>Quantity</th>
<th>Date</th>
<th>Volunteer</th>
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
<td><?php echo htmlspecialchars($row['resource_name']); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>
<td><?php echo htmlspecialchars($row['unit']); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo htmlspecialchars($row['request_date']); ?></td>
<td>
<?php
echo !empty($row['volunteer_name'])
    ? htmlspecialchars($row['volunteer_name'])
    : '<span style="color:#94a3b8;">Not Assigned</span>';
?>
</td>
<td>
<span class="status-badge status-<?php echo htmlspecialchars($statusClass); ?>">
<?php echo htmlspecialchars($status); ?>
</span>
</td>
<td>
<div class="action-cell">

<?php if($status == "Pending"){ ?>

<a class="approve-btn"
href="?approve=<?php echo $id; ?>"
onclick="return confirm('Approve this resource request?');">
<i class="fa-solid fa-check"></i> Approve
</a>

<a class="reject-btn"
href="?reject=<?php echo $id; ?>"
onclick="return confirm('Reject this resource request?');">
<i class="fa-solid fa-xmark"></i> Reject
</a>

<?php } elseif($status == "Approved"){ ?>

<a class="assign-btn"
href="assign_volunteer.php?request=<?php echo $id; ?>">
<i class="fa-solid fa-user-plus"></i> Assign
</a>

<?php } elseif($status == "Assigned"){ ?>

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

<span class="action-none">No action</span>

<?php } ?>

</div>
</td>
</tr>
<?php } ?>

</table>
</div>

<?php } else { ?>

<div class="empty-state">
<i class="fa-solid fa-inbox"></i>
<h3>No resource requests</h3>
<p>Victims have not submitted any requests yet.</p>
</div>

<?php } ?>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
