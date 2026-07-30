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

$getUser = mysqli_query($conn, "
SELECT *
FROM users
WHERE user_id='$user_id'
");

$user = mysqli_fetch_assoc($getUser);

if(!$user)
{
    header("Location: ../login.php");
    exit();
}

$getVictim = mysqli_query($conn, "
SELECT *
FROM victim
WHERE user_id='$user_id'
");

$victim = mysqli_fetch_assoc($getVictim);

if(isset($_POST['update_profile']))
{
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $family = (int)$_POST['number_of_family_members'];
    $emergency = $_POST['emergency_level'] ?? '';

    $allowedLevels = ['Low', 'Medium', 'High', 'Critical', ''];

    if(!in_array($emergency, $allowedLevels, true))
    {
        echo "<script>alert('Invalid emergency level'); window.location='profile.php';</script>";
        exit();
    }

    $emailCheck = mysqli_query($conn, "
    SELECT user_id FROM users
    WHERE email='$email' AND user_id != '$user_id'
    ");

    if(mysqli_num_rows($emailCheck) > 0)
    {
        echo "<script>alert('Email already exists'); window.location='profile.php';</script>";
        exit();
    }

    mysqli_query($conn, "
    UPDATE users
    SET
    name='$name',
    email='$email',
    phone='$phone',
    address='$address'
    WHERE user_id='$user_id'
    ");

    if($victim)
    {
        $victim_id = (int)$victim['victim_id'];
        $safeLevel = $emergency === "" ? "NULL" : "'".mysqli_real_escape_string($conn, $emergency)."'";

        mysqli_query($conn, "
        UPDATE victim
        SET
        number_of_family_members='$family',
        emergency_level=$safeLevel
        WHERE victim_id='$victim_id'
        ");
    }

    $_SESSION['name'] = trim($_POST['name']);

    echo "<script>
    alert('Profile Updated Successfully');
    window.location='profile.php';
    </script>";
    exit();
}

$page_title = "My Profile";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Victim Profile</title>
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">

<?php include("../includes/victim_sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/victim_header.php"); ?>

<div class="content">

<h2 style="margin-bottom:25px;">My Profile</h2>

<!-- Profile Card -->
<div style="background:white;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,.08);
overflow:hidden;
margin-bottom:30px;">

<div style="background:linear-gradient(135deg,#14b8a6,#0e7490);
height:130px;
position:relative;">

<div style="
position:absolute;
left:40px;
bottom:-45px;
width:90px;
height:90px;
background:white;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
border:5px solid white;">

<i class="fa-solid fa-user"
style="font-size:42px;color:#0f766e;"></i>

</div>

</div>

<div style="padding:60px 40px 30px;">

<h2 style="margin:0;">
<?php echo htmlspecialchars($user['name']); ?>
</h2>

<p style="color:#6b7280;margin:8px 0;">
<i class="fa-solid fa-envelope"></i>
<?php echo htmlspecialchars($user['email']); ?>
</p>

<p style="color:#6b7280;margin:8px 0;">
<i class="fa-solid fa-phone"></i>
<?php echo htmlspecialchars($user['phone']); ?>
</p>

<p style="color:#6b7280;margin:8px 0;">
<i class="fa-solid fa-location-dot"></i>
<?php echo htmlspecialchars($user['address']); ?>
</p>

<?php if($victim){ ?>
<p style="color:#6b7280;margin:8px 0;">
<i class="fa-solid fa-people-roof"></i>
Family Members:
<?php echo $victim['number_of_family_members'] !== null ? (int)$victim['number_of_family_members'] : 'Not set'; ?>
</p>

<p style="color:#6b7280;margin:8px 0 14px;">
<i class="fa-solid fa-triangle-exclamation"></i>
Emergency Level:
<?php echo !empty($victim['emergency_level']) ? htmlspecialchars($victim['emergency_level']) : 'Not set'; ?>
</p>
<?php } ?>

<span style="
background:#22C55E;
color:white;
padding:6px 18px;
border-radius:30px;
font-size:14px;
font-weight:bold;">
<?php echo htmlspecialchars($user['role']); ?>
</span>

</div>

</div>

<!-- Edit Form -->
<div class="table-box">

<h3 style="margin-bottom:20px;">Edit Profile</h3>

<form method="POST">

<label><b>Full Name</b></label>
<input
type="text"
name="name"
value="<?php echo htmlspecialchars($user['name']); ?>"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Email</b></label>
<input
type="email"
name="email"
value="<?php echo htmlspecialchars($user['email']); ?>"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Phone Number</b></label>
<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($user['phone']); ?>"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Address</b></label>
<textarea
name="address"
required
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;resize:vertical;min-height:90px;"><?php echo htmlspecialchars($user['address']); ?></textarea>

<label><b>Number of Family Members</b></label>
<input
type="number"
name="number_of_family_members"
min="0"
value="<?php echo isset($victim['number_of_family_members']) ? (int)$victim['number_of_family_members'] : 0; ?>"
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">

<label><b>Emergency Level</b></label>
<select
name="emergency_level"
style="width:100%;padding:13px;margin:10px 0 20px;border:1px solid #ccc;border-radius:8px;">
<option value="">Select Level</option>
<?php
$levels = ['Low','Medium','High','Critical'];
$currentLevel = $victim['emergency_level'] ?? '';
foreach($levels as $level){
?>
<option value="<?php echo $level; ?>" <?php echo ($currentLevel === $level) ? 'selected' : ''; ?>>
<?php echo $level; ?>
</option>
<?php } ?>
</select>

<label><b>Role</b></label>
<input
type="text"
value="<?php echo htmlspecialchars($user['role']); ?>"
readonly
style="width:100%;padding:13px;margin:10px 0 25px;background:#F3F4F6;border:1px solid #ccc;border-radius:8px;">

<button
type="submit"
name="update_profile"
style="
background:linear-gradient(135deg,#14b8a6,#0e7490);
color:white;
border:none;
padding:14px 35px;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;
transition:.3s;">
<i class="fa-solid fa-floppy-disk"></i>
Update Profile
</button>

</form>

</div>

</div>

<?php include("../includes/admin_footer.php"); ?>

</div>

</div>

</body>
</html>
