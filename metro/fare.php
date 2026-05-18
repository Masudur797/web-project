
<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: passenger_login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = "";

// Ajax / normal submit দুইভাবেই use করা যাবে, এখানে normal form handle করছি
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $from = (int)($_POST['from_station'] ?? 0);
    $to   = (int)($_POST['to_station'] ?? 0);
    $date = $_POST['travel_date'] ?? '';
    $method = $_POST['payment_method'] ?? 'card';

    if ($from === 0 || $to === 0 || $date === '') {
        $msg = "❌ All fields are required";
    } elseif ($from === $to) {
        $msg = "❌ From & To stations cannot be same";
    } else {
        // distance হিসাব
        if ($from < $to) {
            $res2 = $conn->query("SELECT SUM(distance) AS dist FROM stations WHERE id >= $from AND id < $to");
        } else {
            $res2 = $conn->query("SELECT SUM(distance) AS dist FROM stations WHERE id >= $to AND id < $from");
        }
        $dist_row = $res2->fetch_assoc();
        $distance_km = (float)($dist_row['dist'] ?? 0);

        if ($distance_km <= 0) {
            $msg = "❌ Invalid station selection";
        } else {
            $rate_per_km = 5;
            $fare = $distance_km * $rate_per_km;

            // simple ভাবে payment success ধরে নিচ্ছি ⇒ status = paid
            $stmt = $conn->prepare("INSERT INTO tickets 
                (user_id, from_station, to_station, distance, fare, payment_method, payment_status, travel_date)
                VALUES (?, ?, ?, ?, ?, ?, 'paid', ?)");
            $stmt->bind_param("iiiddss", $user_id, $from, $to, $distance_km, $fare, $method, $date);

            if ($stmt->execute()) {
                $msg = "✅ Ticket booked successfully. Distance: {$distance_km} km, Fare: {$fare} TK";
            } else {
                $msg = "❌ Failed to book ticket";
            }
            $stmt->close();
        }
    }
}

// stations dropdown
$stations = [];
$res = $conn->query("SELECT * FROM stations ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    $stations[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fare Calculator & Ticket Booking</title>
  <link rel="stylesheet" href="css/fare.css">
</head>
<body>
  <h2>Fare Calculator & Ticket Booking</h2>
  <p><a href="passenger_dashboard.php">← Back to Dashboard</a></p>

  <?php if ($msg !== ""): ?>
    <p><?php echo htmlspecialchars($msg); ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label>From Station:</label><br>
    <select name="from_station" required>
      <option value="">Select</option>
      <?php foreach ($stations as $st): ?>
        <option value="<?php echo $st['id']; ?>">
          <?php echo htmlspecialchars($st['station_name']); ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>To Station:</label><br>
    <select name="to_station" required>
      <option value="">Select</option>
      <?php foreach ($stations as $st): ?>
        <option value="<?php echo $st['id']; ?>">
          <?php echo htmlspecialchars($st['station_name']); ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Travel Date:</label><br>
    <input type="date" name="travel_date" required><br><br>

    <label>Payment Method:</label><br>
    <select name="payment_method" required>
      <option value="card">Card</option>
      <option value="mobile_banking">Mobile Banking</option>
    </select><br><br>

    <button type="submit" name="book">Book Ticket</button>
  </form>
</body>
</html>
