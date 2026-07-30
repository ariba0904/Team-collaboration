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

if(isset($_POST['submit_request']))
{
    $resource_name = trim($_POST['resource_name']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);
    $quantity = (float)$_POST['quantity'];

    if($resource_name === "" || $category === "" || $unit === "" || $quantity <= 0)
    {
        echo "<script>alert('Please fill all fields with a valid quantity.'); window.location='request_resource.php';</script>";
        exit();
    }

    $safeName = mysqli_real_escape_string($conn, $resource_name);
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $safeUnit = mysqli_real_escape_string($conn, $unit);

    /* Must exist in admin inventory */
    $resQuery = mysqli_query($conn, "
    SELECT *
    FROM resource
    WHERE resource_name='$safeName'
    AND category='$safeCategory'
    AND unit='$safeUnit'
    AND available_stock > 0
    LIMIT 1
    ");

    $resource = mysqli_fetch_assoc($resQuery);

    if(!$resource)
    {
        echo "<script>alert('This resource is not available in admin inventory. Please choose another option.'); window.location='request_resource.php';</script>";
        exit();
    }

    if($quantity > (float)$resource['available_stock'])
    {
        echo "<script>alert('Requested quantity exceeds available stock (" . (int)$resource['available_stock'] . " " . htmlspecialchars($resource['unit']) . ").'); window.location='request_resource.php';</script>";
        exit();
    }

    $today = date("Y-m-d");

    $sql = "INSERT INTO resource_request
    (victim_id, resource_name, category, unit, quantity, request_date, status)
    VALUES
    ('$victim_id', '$safeName', '$safeCategory', '$safeUnit', '$quantity', '$today', 'Pending')";

    if(mysqli_query($conn, $sql))
    {
        echo "<script>
        alert('Resource Request Submitted Successfully');
        window.location='my_requests.php';
        </script>";
        exit();
    }

    echo "<script>alert('Failed to submit request. Please try again.'); window.location='request_resource.php';</script>";
    exit();
}

$page_title = "Request Resources";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Resources</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:10px;">Request Resources</h2>
<p style="color:#64748b;margin-bottom:25px;">
Select resource, category, unit and quantity. Requests are accepted only if the item exists in admin inventory.
</p>

<div class="table-box" style="max-width:720px;">

<form method="POST">

<label><b>Resource</b></label>
<select
name="resource_name"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<option value="">Select Resource</option>

<optgroup label="🍚 Food">
<option value="Rice">Rice</option>
<option value="Lentils">Lentils (Dal)</option>
<option value="Biscuits">Biscuits</option>
<option value="Bread">Bread</option>
<option value="Dry Food Package">Dry Food Package</option>
<option value="Baby Food">Baby Food</option>
<option value="Nuts">Nuts</option>
</optgroup>

<optgroup label="💧 Water">
<option value="Drinking Water">Drinking Water</option>
<option value="Water Bottle">Water Bottle</option>
</optgroup>

<optgroup label="💊 Medicine">
<option value="Paracetamol">Paracetamol</option>
<option value="Antibiotic">Antibiotic</option>
<option value="Bandage">Bandage</option>
<option value="First Aid Kit">First Aid Kit</option>
<option value="Tooth Paste">Tooth Paste</option>
</optgroup>

<optgroup label="👕 Clothing">
<option value="Blanket">Blanket</option>
<option value="Jacket">Jacket</option>
<option value="Sweater">Sweater</option>
<option value="Raincoat">Raincoat</option>
<option value="Baby Clothes">Baby Clothes</option>
<option value="Warm Clothes">Warm Clothes</option>
<option value="Slippers">Slippers</option>
<option value="Socks">Socks</option>
<option value="Cap">Cap</option>
</optgroup>

</select>

<label><b>Category</b></label>
<select
name="category"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<option value="">Select Category</option>
<option value="Food">Food</option>
<option value="Water">Water</option>
<option value="Medicine">Medicine</option>
<option value="Clothing">Clothing</option>

</select>

<label><b>Unit</b></label>
<select
name="unit"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<option value="">Select Unit</option>
<option value="kg">kg</option>
<option value="Packets">Packets</option>
<option value="Liters">Liters</option>
<option value="Bottles">Bottles</option>
<option value="Tablets">Tablets</option>
<option value="Rolls">Rolls</option>
<option value="Kits">Kits</option>
<option value="Pieces">Pieces</option>
<option value="Pairs">Pairs</option>

</select>

<label><b>Quantity</b></label>
<input
type="number"
name="quantity"
min="0.5"
step="0.5"
placeholder="Enter quantity"
required
style="width:100%;padding:12px;margin:10px 0 25px;border:1px solid #ccc;border-radius:8px;">

<button
type="submit"
name="submit_request"
style="background:linear-gradient(135deg,#14b8a6,#0e7490);color:white;border:none;padding:14px 35px;border-radius:8px;font-size:16px;font-weight:bold;cursor:pointer;">
<i class="fa-solid fa-paper-plane"></i>
Submit Request
</button>

&nbsp;

<a
href="my_requests.php"
style="background:#6B7280;color:white;padding:14px 30px;border-radius:8px;text-decoration:none;display:inline-block;">
My Requests
</a>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
