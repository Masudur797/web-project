<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: passenger_login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$sql = "SELECT t.id, s1.station_name AS from_name, s2.station_name AS to_name,
               t.distance, t.fare, t.payment_method, t.payment_status,
               t.travel_date, t.created_at
        FROM tickets t
        JOIN stations s1 ON t.from_station = s1.id
        JOIN stations s2 ON t.to_station = s2.id
        WHERE t.user_id = ?
        ORDER BY t.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Tickets</title>
  <link rel="stylesheet" href="css/my_tickets.css">
</head>
<body>
  <h2>My Tickets</h2>
  <p><a href="passenger_dashboard.php">← Back to Dashboard</a></p>

  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>From</th>
      <th>To</th>
      <th>Distance (km)</th>
      <th>Fare</th>
      <th>Payment</th>
      <th>Status</th>
      <th>Travel Date</th>
      <th>Booked At</th>
      <th>Print</th>
    </tr>
    <?php while ($t = $res->fetch_assoc()) { ?>
      <tr>
        <td><?php echo htmlspecialchars($t['id']); ?></td>
        <td><?php echo htmlspecialchars($t['from_name']); ?></td>
        <td><?php echo htmlspecialchars($t['to_name']); ?></td>
        <td><?php echo htmlspecialchars($t['distance']); ?></td>
        <td><?php echo htmlspecialchars($t['fare']); ?></td>
        <td><?php echo htmlspecialchars($t['payment_method']); ?></td>
        <td><?php echo htmlspecialchars($t['payment_status']); ?></td>
        <td><?php echo htmlspecialchars($t['travel_date']); ?></td>
        <td><?php echo htmlspecialchars($t['created_at']); ?></td>
        <td>
          <a href="print_ticket.php?id=<?php echo $t['id']; ?>" target="_blank">Print</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
