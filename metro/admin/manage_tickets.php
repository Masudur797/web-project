<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$msg = "";

// Update ticket status (active/cancelled)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_ticket'])) {
    $ticket_id = (int)($_POST['ticket_id'] ?? 0);
    $new_status = $_POST['ticket_status'] ?? '';

    if ($ticket_id > 0 && in_array($new_status, ['active','cancelled'], true)) {
        $stmt = $conn->prepare("UPDATE tickets SET ticket_status=? WHERE id=?");
        $stmt->bind_param("si", $new_status, $ticket_id);
        $stmt->execute();
        $stmt->close();
        $msg = "✅ Ticket updated!";
    } else {
        $msg = "❌ Invalid request";
    }
}

// Fetch ticket list
$sql = "
SELECT t.id, t.user_id, t.from_station, t.to_station, t.distance, t.fare,
       t.payment_method, t.payment_status, t.travel_date, t.created_at,
       t.ticket_status,
       u.name AS passenger_name, u.email AS passenger_email
FROM tickets t
LEFT JOIN users u ON u.id = t.user_id
ORDER BY t.id DESC
";
$res = $conn->query($sql);
$tickets = [];
if ($res) {
    while ($row = $res->fetch_assoc()) $tickets[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Tickets</title>
<style>
body{font-family:Arial;background:#f4f4f9;margin:0}
.wrap{max-width:1200px;margin:20px auto;padding:15px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;font-size:14px}
th{background:#004aad;color:#fff}
.badge{padding:4px 10px;border-radius:10px;color:#fff;font-size:12px}
.paid{background:#10b981}
.unpaid{background:#f59e0b}
.cancelled{background:#ef4444}
.active{background:#3b82f6}
.btn{padding:6px 10px;border:0;border-radius:6px;cursor:pointer}
.btn-cancel{background:#ef4444;color:#fff}
.btn-activate{background:#10b981;color:#fff}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:10px}
a{color:#004aad;text-decoration:none}
.msg{padding:10px;background:#fff;border-left:4px solid #10b981;margin:10px 0}
select{padding:6px}
</style>
</head>
<body>
<div class="wrap">
  <div class="topbar">
    <h2>Manage Tickets</h2>
    <div>
      <a href="dashboard.php">← Dashboard</a> | <a href="../logout.php">Logout</a>
    </div>
  </div>

  <?php if($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Passenger</th>
        <th>From</th>
        <th>To</th>
        <th>Distance</th>
        <th>Fare</th>
        <th>Payment</th>
        <th>Travel Date</th>
        <th>Ticket Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($tickets)): ?>
        <tr><td colspan="10">No tickets found.</td></tr>
      <?php else: foreach($tickets as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>
          <td>
            <?= htmlspecialchars($t['passenger_name'] ?? 'Unknown') ?><br>
            <small><?= htmlspecialchars($t['passenger_email'] ?? '') ?></small>
          </td>
          <td><?= htmlspecialchars($t['from_station']) ?></td>
          <td><?= htmlspecialchars($t['to_station']) ?></td>
          <td><?= htmlspecialchars($t['distance']) ?></td>
          <td>৳<?= number_format((float)$t['fare'], 2) ?></td>
          <td>
            <span class="badge <?= ($t['payment_status'] === 'paid') ? 'paid' : 'unpaid' ?>">
              <?= htmlspecialchars($t['payment_status']) ?>
            </span><br>
            <small><?= htmlspecialchars($t['payment_method']) ?></small>
          </td>
          <td><?= htmlspecialchars($t['travel_date'] ?? '') ?></td>
          <td>
            <?php $ts = $t['ticket_status'] ?? 'active'; ?>
            <span class="badge <?= $ts === 'cancelled' ? 'cancelled' : 'active' ?>">
              <?= htmlspecialchars($ts) ?>
            </span>
          </td>
          <td>
            <form method="post" style="margin:0;display:flex;gap:8px;align-items:center">
              <input type="hidden" name="update_ticket" value="1">
              <input type="hidden" name="ticket_id" value="<?= (int)$t['id'] ?>">

              <select name="ticket_status">
                <option value="active" <?= ($ts==='active')?'selected':''; ?>>active</option>
                <option value="cancelled" <?= ($ts==='cancelled')?'selected':''; ?>>cancelled</option>
              </select>

              <?php if($ts === 'cancelled'): ?>
                <button class="btn btn-activate" type="submit">Set Active</button>
              <?php else: ?>
                <button class="btn btn-cancel" type="submit">Cancel</button>
              <?php endif; ?>
            </form>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
