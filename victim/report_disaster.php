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

$victimData = mysqli_fetch_assoc($getVictim);

if(!$victimData)
{
    echo "<script>
    alert('Victim profile not found. Please contact admin.');
    window.location='dashboard.php';
    </script>";
    exit();
}

$victim_id = (int)$victimData['victim_id'];
$allowedTypes = ['Flood','Fire','Cyclone','Earthquake','Landslide','Storm','Others'];

if(isset($_POST['submit_report']))
{
    $area = trim($_POST['area_name']);
    $type = $_POST['disaster_type'] ?? '';
    $victims = (int)$_POST['number_of_victims'];
    $notes = trim($_POST['notes']);
    $date = date("Y-m-d");

    if($area === "" || $notes === "" || $type === "")
    {
        echo "<script>alert('Please fill in all required fields.'); window.location='report_disaster.php';</script>";
        exit();
    }

    if(!in_array($type, $allowedTypes, true))
    {
        echo "<script>alert('Please select a valid disaster type.'); window.location='report_disaster.php';</script>";
        exit();
    }

    if($victims < 1)
    {
        echo "<script>alert('Number of victims must be at least 1.'); window.location='report_disaster.php';</script>";
        exit();
    }

    $area = mysqli_real_escape_string($conn, $area);
    $type = mysqli_real_escape_string($conn, $type);
    $notes = mysqli_real_escape_string($conn, $notes);

    $sql = "INSERT INTO disaster_report
    (victim_id, area_name, disaster_type, number_of_victims, report_date, notes, status)
    VALUES
    ('$victim_id', '$area', '$type', '$victims', '$date', '$notes', 'Pending')";

    if(mysqli_query($conn, $sql))
    {
        echo "<script>
        alert('Disaster Report Submitted Successfully');
        window.location='my_reports.php';
        </script>";
        exit();
    }

    echo "<script>alert('Failed to submit report. Please try again.'); window.location='report_disaster.php';</script>";
    exit();
}

$page_title = "Report Disaster";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Disaster</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Submit Disaster Report</h2>
<p style="color:#64748b;margin-bottom:25px;">
Report an emergency so relief teams can respond quickly.
</p>

<div class="table-box" style="max-width:720px;">

<form method="POST">

<label><b>Disaster Type</b></label>
<select
name="disaster_type"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">
<option value="">Select Disaster Type</option>
<?php foreach($allowedTypes as $option){ ?>
<option value="<?php echo $option; ?>"><?php echo $option; ?></option>
<?php } ?>
</select>

<label><b>Area Name</b></label>
<input
type="text"
name="area_name"
placeholder="Enter affected area name"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Number of Victims</b></label>
<input
type="number"
name="number_of_victims"
min="1"
placeholder="Enter number of affected people"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Description / Notes</b></label>
<textarea
name="notes"
rows="5"
placeholder="Describe the disaster situation..."
required
style="width:100%;padding:13px;margin:10px 0 25px;border:1px solid #ccc;border-radius:8px;resize:vertical;"></textarea>

<button
type="submit"
name="submit_report"
style="
background:linear-gradient(135deg,#14b8a6,#0e7490);
color:white;
border:none;
padding:14px 35px;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;">
<i class="fa-solid fa-paper-plane"></i>
Submit Report
</button>

&nbsp;

<a
href="my_reports.php"
style="
background:#6B7280;
color:white;
padding:14px 30px;
border-radius:8px;
text-decoration:none;
display:inline-block;">
My Reports
</a>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
