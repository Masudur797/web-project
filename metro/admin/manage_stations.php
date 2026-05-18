<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit;
}

$msg = "";

// Add new station
if(isset($_POST['add'])){
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $distance = floatval($_POST['distance']);

    if($name && $location){
        $stmt = $conn->prepare("INSERT INTO stations (station_name, location, distance) VALUES (?,?,?)");
        $stmt->bind_param("ssd", $name, $location, $distance);
        if($stmt->execute()){
            $msg = "✅ Station added!";
        } else {
            $msg = "❌ Error adding station";
        }
        $stmt->close();
    }
}

// Delete station
if(isset($_GET['delete'])){
    $sid = (int)$_GET['delete'];
    $conn->query("DELETE FROM stations WHERE id='$sid'");
}

// Fetch stations
$res = $conn->query("SELECT * FROM stations ORDER BY distance ASC");
?>

<h2>Manage Stations</h2>
<?php if($msg!=""){ echo "<p>$msg</p>"; } ?>

<form method="POST">
<input type="text" name="name" placeholder="Station Name" required>
<input type="text" name="location" placeholder="Location" required>
<input type="number" step="0.1" name="distance" placeholder="Distance from start (km)" required>
<button type="submit" name="add">Add Station</button>
</form>

<table border="1" cellpadding="5">
<tr><th>ID</th><th>Name</th><th>Location</th><th>Distance</th><th>Action</th></tr>
<?php while($row = $res->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['station_name']; ?></td>
<td><?php echo $row['location']; ?></td>
<td><?php echo $row['distance']; ?> km</td>
<td>
<a href="manage_stations.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete station?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
