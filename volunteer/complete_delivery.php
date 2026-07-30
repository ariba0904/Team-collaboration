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

if(!isset($_GET['id']) || (int)$_GET['id'] <= 0)
{
    header("Location: assigned_requests.php");
    exit();
}

$request_id = (int)$_GET['id'];

$update = mysqli_query($conn, "
UPDATE resource_request
SET status='Delivered'
WHERE request_id='$request_id'
AND volunteer_id='$volunteer_id'
AND status='In Transit'
");

if($update && mysqli_affected_rows($conn) > 0)
{
    mysqli_query($conn, "
    UPDATE volunteer
    SET availability_status='Available'
    WHERE volunteer_id='$volunteer_id'
    ");

    echo "<script>
    alert('Delivery Completed Successfully');
    window.location='assigned_requests.php';
    </script>";
    exit();
}

echo "<script>
alert('Unable to complete this delivery.');
window.location='assigned_requests.php';
</script>";
exit();

?>
