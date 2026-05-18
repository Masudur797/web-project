<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    die("Unauthorized");
}

$user_id = (int)$_SESSION['user_id'];
$ticket_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT t.id, u.name AS passenger_name, u.email,
               s1.station_name AS from_name, s2.station_name AS to_name,
               t.distance, t.fare, t.payment_method, t.payment_status,
               t.travel_date, t.created_at
        FROM tickets t
        JOIN users u ON t.user_id = u.id
        JOIN stations s1 ON t.from_station = s1.id
        JOIN stations s2 ON t.to_station = s2.id
        WHERE t.id = ? AND t.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$ticket = $res->fetch_assoc();

if (!$ticket) {
    die("Ticket not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticket #<?php echo htmlspecialchars($ticket['id']); ?></title>
  <style>
    body { font-family: Arial, sans-serif; }
    .ticket {
      width: 400px;
      margin: 20px auto;
      border: 2px solid #004aad;
      padding: 15px;
      border-radius: 8px;
    }
    h2 { text-align: center; color: #004aad; }
    .row { margin: 4px 0; }
    .print-btn { text-align: center; margin-top: 15px; }
    button { padding: 6px 12px; }
  </style>
</head>
<body>
  <div class="ticket">
    <h2>Dhaka Metro Ticket</h2>
    <div class="row"><strong>Passenger:</strong> <?php echo htmlspecialchars($ticket['passenger_name']); ?></div>
    <div class="row"><strong>Email:</strong> <?php echo htmlspecialchars($ticket['email']); ?></div>
    <div class="row"><strong>From:</strong> <?php echo htmlspecialchars($ticket['from_name']); ?></div>
    <div class="row"><strong>To:</strong> <?php echo htmlspecialchars($ticket['to_name']); ?></div>
    <div class="row"><strong>Distance:</strong> <?php echo htmlspecialchars($ticket['distance']); ?> km</div>
    <div class="row"><strong>Fare:</strong> <?php echo htmlspecialchars($ticket['fare']); ?> TK</div>
    <div class="row"><strong>Payment:</strong> <?php echo htmlspecialchars($ticket['payment_method']); ?> (<?php echo htmlspecialchars($ticket['payment_status']); ?>)</div>
    <div class="row"><strong>Travel Date:</strong> <?php echo htmlspecialchars($ticket['travel_date']); ?></div>
    <div class="row"><strong>Booked At:</strong> <?php echo htmlspecialchars($ticket['created_at']); ?></div>

    <div class="print-btn">
      <button onclick="window.print()">Print</button>
    </div>
  </div>
</body>
</html>
