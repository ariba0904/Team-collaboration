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

$sql = mysqli_query($conn, "
SELECT *
FROM disaster_report
WHERE victim_id='$victim_id'
ORDER BY report_id DESC
");

$total = mysqli_num_rows($sql);

$page_title = "My Reports";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Disaster Reports</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<div class="page-head">
    <div>
        <h2>My Disaster Reports</h2>
        <p class="page-sub">Track the status of all reports you have submitted.</p>
    </div>
    <a href="report_disaster.php" class="btn">
        <i class="fa-solid fa-plus"></i> New Report
    </a>
</div>

<div class="table-box">

<div class="table-meta">
    <span>Total Reports: <strong><?php echo (int)$total; ?></strong></span>
</div>

<?php if($total > 0){ ?>

<div class="table-responsive">
<table>
<tr>
    <th>ID</th>
    <th>Disaster</th>
    <th>Area</th>
    <th>Victims</th>
    <th>Date</th>
    <th>Status</th>
    <th>Notes</th>
</tr>

<?php while($row = mysqli_fetch_assoc($sql)){
    $status = $row['status'];
    $statusClass = strtolower(str_replace(' ', '-', $status));
?>
<tr>
    <td><?php echo (int)$row['report_id']; ?></td>
    <td><?php echo htmlspecialchars($row['disaster_type']); ?></td>
    <td><?php echo htmlspecialchars($row['area_name']); ?></td>
    <td><?php echo (int)$row['number_of_victims']; ?></td>
    <td><?php echo htmlspecialchars($row['report_date']); ?></td>
    <td>
        <span class="status-badge status-<?php echo htmlspecialchars($statusClass); ?>">
            <?php echo htmlspecialchars($status); ?>
        </span>
    </td>
    <td class="notes-cell" title="<?php echo htmlspecialchars($row['notes']); ?>">
        <?php echo htmlspecialchars($row['notes']); ?>
    </td>
</tr>
<?php } ?>

</table>
</div>

<?php } else { ?>

<div class="empty-state">
    <i class="fa-solid fa-folder-open"></i>
    <h3>No reports yet</h3>
    <p>You have not submitted any disaster reports.</p>
    <a href="report_disaster.php" class="btn">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Report Disaster
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
