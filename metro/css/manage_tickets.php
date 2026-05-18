<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Delete ticket
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM tickets WHERE id = $id");
    header("Location: manage_tickets.php");
    exit;
}

$sql = "SELECT t.id, u.name AS passenger_name,
               s1.station_name AS from_name,
               s2.station_name AS to_name,
               t.distance, t.fare, t.payment_method, t.payment_status,
               t.travel_date, t.created_at
        FROM tickets t
        JOIN users u ON t.user_id = u.id
        JOIN stations s1 ON t.from_station = s1.id
        JOIN stations s2 ON t.to_station = s2.id
        ORDER BY t.created_at DESC";

$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Tickets</title>
  <link rel="stylesheet" href="admin_table.css">
</head>
<body>
  <h2>Tickets</h2>
  <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>

  <table>
    <tr>
      <th>ID</th>
      <th>Passenger</th>
      <th>From</th>
      <th>To</th>
      <th>Distance (km)</th>
      <th>Fare</th>
      <th>Payment</th>
      <th>Status</th>
      <th>Travel Date</th>
      <th>Booked At</th>
      <th>Action</th>
    </tr>
    <?php while ($t = $res->fetch_assoc()) { ?>
      <tr>
        <td><?php echo htmlspecialchars($t['id']); ?></td>
        <td><?php echo htmlspecialchars($t['passenger_name']); ?></td>
        <td><?php echo htmlspecialchars($t['from_name']); ?></td>
        <td><?php echo htmlspecialchars($t['to_name']); ?></td>
        <td><?php echo htmlspecialchars($t['distance']); ?></td>
        <td><?php echo htmlspecialchars($t['fare']); ?></td>
        <td><?php echo htmlspecialchars($t['payment_method']); ?></td>
        <td><?php echo htmlspecialchars($t['payment_status']); ?></td>
        <td><?php echo htmlspecialchars($t['travel_date']); ?></td>
        <td><?php echo htmlspecialchars($t['created_at']); ?></td>
        <td>
          <a href="manage_tickets.php?delete=<?php echo $t['id']; ?>"
             onclick="return confirm('Delete this ticket?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
