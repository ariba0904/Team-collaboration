<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="Victim")
{
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$getVictim = mysqli_query($conn,"
SELECT victim_id
FROM victim
WHERE user_id='$user_id'
");

$victim = mysqli_fetch_assoc($getVictim);

if(!$victim)
{
    echo "<script>
    alert('Victim profile not found. Please contact admin.');
    window.location='../logout.php';
    </script>";
    exit();
}

$victim_id = (int)$victim['victim_id'];

$totalReports = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM disaster_report
WHERE victim_id='$victim_id'
"));

$pendingReports = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM disaster_report
WHERE victim_id='$victim_id'
AND status='Pending'
"));

$approvedReports = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM disaster_report
WHERE victim_id='$victim_id'
AND status='Approved'
"));

$totalRequests = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM resource_request
WHERE victim_id='$victim_id'
"));

$page_title = "Dashboard";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Victim Dashboard</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">Victim Dashboard</h2>

<div class="cards">

<div class="card">
<i class="fa-solid fa-file-circle-plus"></i>
<h3>Total Reports</h3>
<p><?php echo (int)$totalReports['total']; ?></p>
</div>

<div class="card">
<i class="fa-solid fa-clock"></i>
<h3>Pending Reports</h3>
<p><?php echo (int)$pendingReports['total']; ?></p>
</div>

<div class="card">
<i class="fa-solid fa-circle-check"></i>
<h3>Approved Reports</h3>
<p><?php echo (int)$approvedReports['total']; ?></p>
</div>

<div class="card">
<i class="fa-solid fa-box"></i>
<h3>Resource Requests</h3>
<p><?php echo (int)$totalRequests['total']; ?></p>
</div>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
