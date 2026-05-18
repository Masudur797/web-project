<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$msg = "";

// Block/Unblock action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $new_status = $_POST['new_status'] ?? '';

    if ($user_id > 0 && in_array($new_status, ['active','blocked'], true)) {
        // Only passenger can be blocked (safety)
        $stmt = $conn->prepare("UPDATE users SET status=? WHERE id=? AND role='passenger'");
        $stmt->bind_param("si", $new_status, $user_id);
        $stmt->execute();
        $stmt->close();
        $msg = "✅ User status updated!";
    } else {
        $msg = "❌ Invalid request";
    }
}

// Fetch passengers
$res = $conn->query("SELECT id, name, email, role, status, created_at FROM users WHERE role='passenger' ORDER BY id DESC");
$passengers = [];
if ($res) {
    while ($row = $res->fetch_assoc()) $passengers[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<style>
body{font-family:Arial;background:#f4f4f9;margin:0}
.wrap{max-width:1100px;margin:20px auto;padding:15px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
th{background:#004aad;color:#fff}
.badge{padding:4px 10px;border-radius:10px;color:#fff;font-size:12px}
.active{background:#10b981}
.blocked{background:#ef4444}
.btn{padding:7px 12px;border:0;border-radius:6px;cursor:pointer}
.btn-block{background:#ef4444;color:#fff}
.btn-unblock{background:#10b981;color:#fff}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:10px}
a{color:#004aad;text-decoration:none}
.msg{padding:10px;background:#fff;border-left:4px solid #10b981;margin:10px 0}
</style>
</head>
<body>
<div class="wrap">
  <div class="topbar">
    <h2>Manage Users (Passengers)</h2>
    <div>
      <a href="dashboard.php">← Dashboard</a> | <a href="../logout.php">Logout</a>
    </div>
  </div>

  <?php if($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($passengers)): ?>
        <tr><td colspan="6">No passengers found.</td></tr>
      <?php else: foreach($passengers as $p): ?>
        <tr>
          <td><?= (int)$p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['email']) ?></td>
          <td>
            <?php $st = $p['status'] ?? 'active'; ?>
            <span class="badge <?= $st === 'blocked' ? 'blocked' : 'active' ?>">
              <?= htmlspecialchars($st) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($p['created_at'] ?? '') ?></td>
          <td>
            <form method="post" style="margin:0">
              <input type="hidden" name="toggle_status" value="1">
              <input type="hidden" name="user_id" value="<?= (int)$p['id'] ?>">
              <?php if(($p['status'] ?? 'active') === 'blocked'): ?>
                <input type="hidden" name="new_status" value="active">
                <button class="btn btn-unblock" type="submit">Unblock</button>
              <?php else: ?>
                <input type="hidden" name="new_status" value="blocked">
                <button class="btn btn-block" type="submit">Block</button>
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
