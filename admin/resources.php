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

/*================ ADD RESOURCE ================*/

if(isset($_POST['add_resource']))
{

    $resource_name = mysqli_real_escape_string($conn,$_POST['resource_name']);

    $category = mysqli_real_escape_string($conn,$_POST['category']);

    $unit = mysqli_real_escape_string($conn,$_POST['unit']);

    $available_stock = (int)$_POST['available_stock'];

    $total_stock = (int)$_POST['total_stock'];

    $expiry_date = $_POST['expiry_date'];

    $last_updated = date("Y-m-d H:i:s");

    $check = mysqli_query($conn,"
    SELECT *
FROM resource
WHERE
resource_name='$resource_name'
AND
category='$category'
AND
unit='$unit'
    ");

    if(mysqli_num_rows($check)>0)
    {

        echo "<script>

        alert('Resource already exists.');

        window.location='resources.php';

        </script>";

        exit();

    }

    mysqli_query($conn,"

    INSERT INTO resource

    (

    resource_name,

    category,

    unit,

    available_stock,

    total_stock,

    expiry_date,

    last_updated

    )

    VALUES

    (

    '$resource_name',

    '$category',

    '$unit',

    '$available_stock',

    '$total_stock',

    '$expiry_date',

    '$last_updated'

    )

    ");

    echo "<script>

    alert('Resource Added Successfully');

    window.location='resources.php';

    </script>";

    exit();

}

/*================ DELETE RESOURCE ================*/

if(isset($_GET['delete']))
{

    $id=(int)$_GET['delete'];

    mysqli_query($conn,"
    DELETE FROM resource
    WHERE resource_id='$id'
    ");

    header("Location: resources.php");

    exit();

}

/*================ SEARCH ================*/

$search="";

if(isset($_GET['search']))
{

    $search=mysqli_real_escape_string($conn,$_GET['search']);

    $sql="

    SELECT *

    FROM resource

    WHERE

    resource_name LIKE '%$search%'

    OR

    category LIKE '%$search%'

    OR

    unit LIKE '%$search%'

    ORDER BY resource_id DESC

    ";

}
else
{

    $sql="

    SELECT *

    FROM resource

    ORDER BY resource_id DESC

    ";

}

$result=mysqli_query($conn,$sql);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Resource Management</title>

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

Resource Management

</h2>

<div class="table-box">

<form method="POST">

<!-- Resource -->

<label><b>Resource Name</b></label>

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

<!-- Category -->

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

<!-- Unit -->

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

<!-- Available -->

<label><b>Available Stock</b></label>

<input
type="number"
name="available_stock"
min="0"
required
placeholder="Enter Available Stock"
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<!-- Total -->

<label><b>Total Stock</b></label>

<input
type="number"
name="total_stock"
min="0"
required
placeholder="Enter Total Stock"
style="width:100%;padding:12px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<!-- Expiry -->

<label><b>Expiry Date</b></label>

<input
type="date"
name="expiry_date"
style="width:100%;padding:12px;margin:10px 0 25px;border:1px solid #ccc;border-radius:8px;">

<button
type="submit"
name="add_resource"
style="background:#06B6D4;color:white;border:none;padding:14px 35px;border-radius:8px;font-size:16px;cursor:pointer;">

<i class="fa-solid fa-plus"></i>

Add Resource

</button>

</form>

</div>

<br><br>
<form method="GET">

<input
type="text"
name="search"
placeholder="Search Resource / Category / Unit"
value="<?php echo $search; ?>"
style="padding:12px;width:350px;border:1px solid #ccc;border-radius:8px;">

<button
style="padding:12px 22px;
background:#06B6D4;
color:white;
border:none;
border-radius:8px;
cursor:pointer;">

Search

</button>

</form>

<br>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Resource</th>

<th>Category</th>

<th>Unit</th>

<th>Available</th>

<th>Total</th>

<th>Expiry Date</th>

<th>Last Updated</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['resource_id']; ?></td>

<td><?php echo $row['resource_name']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['unit']; ?></td>

<td>

<?php echo $row['available_stock']; ?>

</td>

<td>

<?php echo $row['total_stock']; ?>

</td>

<td>

<?php

if($row['expiry_date']=="0000-00-00" || $row['expiry_date']==NULL)
{
    echo "-";
}
else
{
    echo $row['expiry_date'];
}

?>

</td>

<td>

<?php

if($row['last_updated']==NULL)
{
    echo "-";
}
else
{
    echo $row['last_updated'];
}

?>

</td>

<td>

<a
href="edit_resource.php?id=<?php echo $row['resource_id']; ?>"
style="text-decoration:none;
background:#3B82F6;
color:white;
padding:8px 14px;
border-radius:6px;">

Edit

</a>

&nbsp;

<a
href="resources.php?delete=<?php echo $row['resource_id']; ?>"
onclick="return confirm('Delete this resource?')"
style="text-decoration:none;
background:#EF4444;
color:white;
padding:8px 14px;
border-radius:6px;">

Delete

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>

</html>