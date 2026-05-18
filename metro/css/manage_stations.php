<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit;
}

$msg = "";

// Add station
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name     = trim($_POST['station_name'] ?? '');
    $distance = trim($_POST['distance'] ?? '');

    if ($name === '' || $distance === '') {
        $msg = "❌ All fields are required";
    } elseif (!is_numeric($distance)) {
        $msg = "❌ Distance must be numeric";
    } else {
        $name_esc = $conn->real_escape_string($name);
        $dist_val = (float)$distance;
        $conn->query("INSERT INTO stations (station_name, distance) VALUES ('$name_esc', '$dist_val')");
        $msg = "✅ Station added";
    }
}

// Delete station
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM stations WHERE id = $id");
    header("Location: manage_stations.php");
    exit;
}

// Fetch stations
$res = $conn->query("SELECT * FROM stations ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Stations</title>
  <link rel="stylesheet" href="admin_table.css">
</head>
<body>
  <h2>Stations</h2>
  <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>

  <?php if ($msg !== ""): ?>
    <p class="msg"><?php echo htmlspecialchars($msg); ?></p>
  <?php endif; ?>

  <h3>Add New Station</h3>
  <form method="post" action="">
    <input type="text" name="station_name" placeholder="Station Name" required>
    <input type="text" name="distance" placeholder="Distance to next (km)" required>
    <button type="submit" name="add">Add Station</button>
  </form>

  <h3>Existing Stations</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Station Name</th>
      <th>Distance (km)</th>
      <th>Action</th>
    </tr>
    <?php while ($s = $res->fetch_assoc()) { ?>
      <tr>
        <td><?php echo htmlspecialchars($s['id']); ?></td>
        <td><?php echo htmlspecialchars($s['station_name']); ?></td>
        <td><?php echo htmlspecialchars($s['distance']); ?></td>
        <td>
          <a href="manage_stations.php?delete=<?php echo $s['id']; ?>"
             onclick="return confirm('Delete this station?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
