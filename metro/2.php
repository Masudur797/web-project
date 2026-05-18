<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: passenger_login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = "";

// submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $from   = (int)($_POST['from_station'] ?? 0);
    $to     = (int)($_POST['to_station'] ?? 0);
    $date   = $_POST['travel_date'] ?? '';
    $method = $_POST['payment_method'] ?? '';

    // NEW fields
    $card_number     = preg_replace('/\s+/', '', trim($_POST['card_number'] ?? ''));
    $mobile_provider = trim($_POST['mobile_provider'] ?? '');
    $mobile_number   = trim($_POST['mobile_number'] ?? '');
    $trx_id          = trim($_POST['trx_id'] ?? '');

    if ($from === 0 || $to === 0 || $date === '' || $method === '') {
        $msg = "❌ All fields are required";
    } elseif ($from === $to) {
        $msg = "❌ From & To stations cannot be same";
    } else {

        // payment validation (server-side)
        if ($method === 'card') {
            if ($card_number === '') {
                $msg = "❌ Card number required";
            } elseif (!ctype_digit($card_number) || strlen($card_number) < 12 || strlen($card_number) > 19) {
                $msg = "❌ Invalid card number";
            }
        } elseif ($method === 'mobile_banking') {
            $allowed = ['bkash','nagad','rocket','upay'];
            if ($mobile_provider === '' || !in_array($mobile_provider, $allowed, true)) {
                $msg = "❌ Select a valid mobile banking provider";
            } elseif ($mobile_number === '' || !preg_match('/^01[0-9]{9}$/', $mobile_number)) {
                $msg = "❌ Invalid mobile number";
            } elseif ($trx_id === '') {
                $msg = "❌ Transaction ID (TrxID) required";
            }
        } else {
            $msg = "❌ Invalid payment method";
        }

        // distance + insert only if no msg yet
        if ($msg === "") {
            if ($from < $to) {
                $res2 = $conn->query("SELECT SUM(distance) AS dist FROM stations WHERE id >= $from AND id < $to");
            } else {
                $res2 = $conn->query("SELECT SUM(distance) AS dist FROM stations WHERE id >= $to AND id < $from");
            }

            $dist_row = $res2 ? $res2->fetch_assoc() : null;
            $distance_km = (float)($dist_row['dist'] ?? 0);

            if ($distance_km <= 0) {
                $msg = "❌ Invalid station selection";
            } else {
                $rate_per_km = 5;
                $fare = $distance_km * $rate_per_km;

                // store only what is needed as payment_method text (simple)
                // OPTIONAL: card last4/provider/trx_id store করতে চাইলে tickets টেবিলে column add করতে হবে
                $payment_method_db = ($method === 'card')
                    ? 'card'
                    : ('mobile_banking:' . $mobile_provider);

                $stmt = $conn->prepare("INSERT INTO tickets
                    (user_id, from_station, to_station, distance, fare, payment_method, payment_status, travel_date)
                    VALUES (?, ?, ?, ?, ?, ?, 'paid', ?)");

                $stmt->bind_param("iiiddss", $user_id, $from, $to, $distance_km, $fare, $payment_method_db, $date);

                if ($stmt->execute()) {
                    $msg = "✅ Ticket booked successfully. Distance: {$distance_km} km, Fare: {$fare} TK";
                } else {
                    $msg = "❌ Failed to book ticket";
                }
                $stmt->close();
            }
        }
    }
}

// stations dropdown
$stations = [];
$res = $conn->query("SELECT * FROM stations ORDER BY id ASC");
while ($res && ($row = $res->fetch_assoc())) {
    $stations[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fare Calculator & Ticket Booking</title>
  <link rel="stylesheet" href="css/fare.css">
  <style>
    .pay-box{padding:10px;border:1px solid #ddd;border-radius:8px;margin-top:8px}
  </style>
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
        <option value="<?php echo (int)$st['id']; ?>">
          <?php echo htmlspecialchars($st['station_name']); ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>To Station:</label><br>
    <select name="to_station" required>
      <option value="">Select</option>
      <?php foreach ($stations as $st): ?>
        <option value="<?php echo (int)$st['id']; ?>">
          <?php echo htmlspecialchars($st['station_name']); ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Travel Date:</label><br>
    <input type="date" name="travel_date" required><br><br>

    <label>Payment Method:</label><br>
    <select id="payment_method" name="payment_method" required>
      <option value="card">Card</option>
      <option value="mobile_banking">Mobile Banking</option>
    </select><br><br>

    <!-- Card -->
    <div id="card_box" class="pay-box">
      <label>Card Number:</label><br>
      <input type="text" name="card_number" placeholder="1234 5678 9012 3456"><br>
    </div>

    <!-- Mobile Banking -->
    <div id="mb_box" class="pay-box" style="display:none;">
      <label>Select Mobile Banking:</label><br>
      <select name="mobile_provider">
        <option value="">Select</option>
        <option value="bkash">bKash</option>
        <option value="nagad">Nagad</option>
        <option value="rocket">Rocket</option>
        <option value="upay">Upay</option>
      </select><br><br>

      <label>Mobile Number:</label><br>
      <input type="text" name="mobile_number" placeholder="01XXXXXXXXX"><br><br>

      <label>Transaction ID (TrxID):</label><br>
      <input type="text" name="trx_id" placeholder="e.g. A7B9C12"><br>
    </div>

    <br>
    <button type="submit" name="book">Book Ticket</button>
  </form>

  <script>
    const pm = document.getElementById('payment_method');
    const cardBox = document.getElementById('card_box');
    const mbBox = document.getElementById('mb_box');

    function togglePaymentFields(){
      if(pm.value === 'card'){
        cardBox.style.display = 'block';
        mbBox.style.display = 'none';
      }else{
        cardBox.style.display = 'none';
        mbBox.style.display = 'block';
      }
    }
    pm.addEventListener('change', togglePaymentFields);
    togglePaymentFields();
  </script>
</body>
</html>




