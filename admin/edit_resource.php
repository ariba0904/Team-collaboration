
<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="Admin")
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: resources.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn,"
SELECT *
FROM resource
WHERE resource_id='$id'
");

if(mysqli_num_rows($result)==0)
{
    header("Location: resources.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update_resource']))
{

    $resource_name = mysqli_real_escape_string($conn,$_POST['resource_name']);

    $category = mysqli_real_escape_string($conn,$_POST['category']);

    $available_stock = (int)$_POST['available_stock'];

    $total_stock = (int)$_POST['total_stock'];

    $expiry_date = $_POST['expiry_date'];

    $last_updated = date("Y-m-d H:i:s");

    mysqli_query($conn,"

    UPDATE resource

    SET

    resource_name='$resource_name',

    category='$category',

    available_stock='$available_stock',

    total_stock='$total_stock',

    expiry_date='$expiry_date',

    last_updated='$last_updated'

    WHERE resource_id='$id'

    ");

    echo "<script>

    alert('Resource Updated Successfully');

    window.location='resources.php';

    </script>";

    exit();

}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Resource</title>

<link rel="stylesheet" href="../css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="dashboard">

<?php include("../includes/admin_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/admin_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">

Edit Resource

</h2>

<div class="table-box">

<form method="POST">

<label><b>Resource Name</b></label>

<input
type="text"
name="resource_name"
value="<?php echo $row['resource_name']; ?>"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Category</b></label>

<select
name="category"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<option value="Food"
<?php if($row['category']=="Food") echo "selected"; ?>>
Food
</option>

<option value="Water"
<?php if($row['category']=="Water") echo "selected"; ?>>
Water
</option>

<option value="Medicine"
<?php if($row['category']=="Medicine") echo "selected"; ?>>
Medicine
</option>

<option value="Clothing"
<?php if($row['category']=="Clothing") echo "selected"; ?>>
Clothing
</option>

</select>

<label><b>Available Stock</b></label>

<input
type="number"
name="available_stock"
value="<?php echo $row['available_stock']; ?>"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Total Stock</b></label>

<input
type="number"
name="total_stock"
value="<?php echo $row['total_stock']; ?>"
required
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Expiry Date</b></label>

<input
type="date"
name="expiry_date"
value="<?php echo $row['expiry_date']; ?>"
style="width:100%;padding:12px;margin:10px 0 25px;border:1px solid #ccc;border-radius:8px;">

<button
type="submit"
name="update_resource"
style="background:#06B6D4;color:white;border:none;padding:14px 35px;border-radius:8px;font-size:16px;cursor:pointer;">

<i class="fa-solid fa-pen"></i>

Update Resource

</button>

&nbsp;

<a
href="resources.php"
style="background:#6B7280;color:white;padding:14px 30px;border-radius:8px;text-decoration:none;">

Cancel

</a>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>

</html>