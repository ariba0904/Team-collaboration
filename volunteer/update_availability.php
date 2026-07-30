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

$user_id = $_SESSION['user_id'];

/* Get Volunteer ID */

$getVolunteer = mysqli_query($conn,"
SELECT *
FROM volunteer
WHERE user_id='$user_id'
");

$volunteer = mysqli_fetch_assoc($getVolunteer);

$volunteer_id = $volunteer['volunteer_id'];

/* Update Availability */

if(isset($_POST['update']))
{

    $status = $_POST['status'];

    mysqli_query($conn,"
    UPDATE volunteer
    SET availability_status='$status'
    WHERE volunteer_id='$volunteer_id'
    ");

    echo "<script>

    alert('Availability Updated Successfully');

    window.location='update_availability.php';

    </script>";

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Update Availability</title>

<link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="dashboard">

<?php include("../includes/volunteer_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/volunteer_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">

Update Availability

</h2>

<div class="table-box">

<form method="POST">

<label><b>Select Status</b></label>

<select
name="status"
style="width:100%;padding:12px;margin:20px 0;border-radius:8px;"
required>

<option value="Available"
<?php if($volunteer['availability_status']=="Available") echo "selected"; ?>>

Available

</option>

<option value="Busy"
<?php if($volunteer['availability_status']=="Busy") echo "selected"; ?>>

Busy

</option>

<option value="Offline"
<?php if($volunteer['availability_status']=="Offline") echo "selected"; ?>>

Offline

</option>

</select>

<button
type="submit"
name="update"
style="background:#2563EB;color:white;padding:12px 30px;border:none;border-radius:8px;cursor:pointer;">

Update

</button>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>

</html>