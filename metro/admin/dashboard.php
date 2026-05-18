<?php
session_start();
require_once "../config.php"; // admin/ folder theke config.php 1 step up hole

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Total Passengers
$total_passengers = 0;
$q1 = $conn->query("SELECT COUNT(*) AS total_passengers FROM users WHERE role='passenger'");
if ($q1) {
    $total_passengers = (int)($q1->fetch_assoc()['total_passengers'] ?? 0);
}

// Total Tickets
$total_tickets = 0;
$q2 = $conn->query("SELECT COUNT(*) AS total_tickets FROM tickets");
if ($q2) {
    $total_tickets = (int)($q2->fetch_assoc()['total_tickets'] ?? 0);
}

// Total Revenue (only paid)
$total_revenue = 0;
$q3 = $conn->query("SELECT COALESCE(SUM(fare),0) AS total_revenue FROM tickets WHERE payment_status='paid'");
if ($q3) {
    $total_revenue = (float)($q3->fetch_assoc()['total_revenue'] ?? 0);





// Unread Messages Count
$unread_messages = 0;
$qm = $conn->query("SELECT COUNT(*) AS unread FROM contact_messages WHERE is_read=0");
if ($qm) {
    $unread_messages = (int)($qm->fetch_assoc()['unread'] ?? 0);
}





}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body{font-family:Arial;background:#f4f4f9;margin:0}
    .wrap{max-width:1100px;margin:20px auto;padding:15px}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:15px}
    .card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08);text-align:center}
    .card h3{margin:0 0 8px;color:#004aad;font-size:28px}

    /* NEW: buttons */
    .actions{margin-top:20px;display:flex;flex-wrap:wrap;gap:12px}
    .btn{
      display:inline-block;
      background:#004aad;
      color:#fff;
      padding:12px 18px;
      border-radius:10px;
      text-decoration:none;
      font-weight:bold;
      transition:.2s;
    }
    .btn:hover{background:#10b981}
    .btn-logout{background:#ef4444}
    .btn-logout:hover{background:#dc2626}
  </style>
</head>
<body>
<div class="wrap">
  <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?> (Admin)</h2>

  <div class="cards">
    <div class="card">
      <h3><?= $total_passengers ?></h3>
      <p>Total Passengers</p>
    </div>
    <div class="card">
      <h3><?= $total_tickets ?></h3>
      <p>Total Tickets</p>
    </div>
    <div class="card">
      <h3>৳<?= number_format($total_revenue, 2) ?></h3>
      <p>Total Revenue (Paid)</p>
    </div>
  </div>

  <!-- Admin buttons -->
  <div class="actions">
    <a class="btn" href="manage_users.php">Manage Users</a>
    <a class="btn" href="manage_tickets.php">Manage Tickets</a>
    <a class="btn" href="manage_messages.php">view messege</a>
    <a class="btn btn-logout" href="../logout.php">Logout</a>
  </div>

</div>
</body>
</html>
