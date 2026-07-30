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

if(!isset($_GET['request']) || (int)$_GET['request'] <= 0)
{
    header("Location: resource_requests.php");
    exit();
}

$request_id = (int)$_GET['request'];

$requestQuery = mysqli_query($conn, "
SELECT
resource_request.*,
users.name AS victim_name
FROM resource_request
JOIN victim ON resource_request.victim_id=victim.victim_id
JOIN users ON victim.user_id=users.user_id
WHERE resource_request.request_id='$request_id'
");

$request = mysqli_fetch_assoc($requestQuery);

if(!$request)
{
    echo "<script>alert('Request not found'); window.location='resource_requests.php';</script>";
    exit();
}

if($request['status'] != "Approved" && $request['status'] != "Assigned")
{
    echo "<script>alert('Only Approved or Assigned requests can be assigned.'); window.location='resource_requests.php';</script>";
    exit();
}

if(isset($_POST['assign']))
{
    $volunteer_id = (int)$_POST['volunteer_id'];

    if($volunteer_id <= 0)
    {
        echo "<script>alert('Please select a volunteer'); window.location='assign_volunteer.php?request=$request_id';</script>";
        exit();
    }

    $volCheck = mysqli_query($conn, "
    SELECT volunteer_id
    FROM volunteer
    WHERE volunteer_id='$volunteer_id'
    AND availability_status='Available'
    ");

    if(mysqli_num_rows($volCheck) == 0)
    {
        echo "<script>alert('Selected volunteer is not available'); window.location='assign_volunteer.php?request=$request_id';</script>";
        exit();
    }

    /* Free previous volunteer if reassigning */
    if(!empty($request['volunteer_id']) && (int)$request['volunteer_id'] !== $volunteer_id)
    {
        $oldVid = (int)$request['volunteer_id'];
        mysqli_query($conn, "
        UPDATE volunteer
        SET availability_status='Available'
        WHERE volunteer_id='$oldVid'
        ");
    }

    mysqli_query($conn, "
    UPDATE resource_request
    SET volunteer_id='$volunteer_id',
        status='Assigned'
    WHERE request_id='$request_id'
    ");

    mysqli_query($conn, "
    UPDATE volunteer
    SET availability_status='Busy'
    WHERE volunteer_id='$volunteer_id'
    ");

    echo "<script>
    alert('Volunteer Assigned Successfully');
    window.location='resource_requests.php';
    </script>";
    exit();
}

$volunteers = mysqli_query($conn, "
SELECT
volunteer.volunteer_id,
users.name
FROM volunteer
INNER JOIN users ON volunteer.user_id = users.user_id
WHERE volunteer.availability_status='Available'
ORDER BY users.name
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Volunteer</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">Assign Volunteer</h2>

<div class="table-box" style="max-width:720px;margin-bottom:20px;">
<h3 style="margin-bottom:15px;">Request Details</h3>
<p><b>Request ID:</b> <?php echo (int)$request['request_id']; ?></p>
<p><b>Victim:</b> <?php echo htmlspecialchars($request['victim_name']); ?></p>
<p><b>Resource:</b> <?php echo htmlspecialchars($request['resource_name']); ?></p>
<p><b>Category:</b> <?php echo htmlspecialchars($request['category']); ?></p>
<p><b>Unit:</b> <?php echo htmlspecialchars($request['unit']); ?></p>
<p><b>Quantity:</b> <?php echo $request['quantity']; ?></p>
<p><b>Status:</b> <?php echo htmlspecialchars($request['status']); ?></p>
</div>

<div class="table-box" style="max-width:720px;">

<form method="POST">

<label><b>Select Volunteer</b></label>
<select
name="volunteer_id"
required
style="width:100%;padding:12px;margin:15px 0 25px;border:1px solid #ccc;border-radius:8px;">
<option value="">Choose Volunteer</option>
<?php
if(mysqli_num_rows($volunteers) > 0)
{
    while($row = mysqli_fetch_assoc($volunteers))
    {
?>
<option value="<?php echo (int)$row['volunteer_id']; ?>">
<?php echo htmlspecialchars($row['name']); ?>
</option>
<?php
    }
}
else
{
?>
<option value="" disabled>No available volunteers</option>
<?php } ?>
</select>

<button
type="submit"
name="assign"
style="background:linear-gradient(135deg,#14b8a6,#0e7490);color:white;padding:12px 30px;border:none;border-radius:8px;cursor:pointer;font-weight:600;">
<i class="fa-solid fa-user-plus"></i>
Assign Volunteer
</button>

&nbsp;

<a
href="resource_requests.php"
style="background:#6B7280;color:white;padding:12px 30px;border-radius:8px;text-decoration:none;display:inline-block;">
Back
</a>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
