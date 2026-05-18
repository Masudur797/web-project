<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$res = $conn->query("SELECT id, user_id, name, email, message, is_read, created_at
                     FROM contact_messages
                     ORDER BY is_read ASC, created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Messages</title>
  <style>
    body{font-family:Arial;background:#f4f4f9;margin:0}
    .wrap{max-width:1100px;margin:20px auto;padding:15px}
    table{width:100%;border-collapse:collapse;background:#fff}
    th,td{border:1px solid #e5e7eb;padding:10px;vertical-align:top}
    th{background:#004aad;color:#fff;text-align:left}
    .badge{display:inline-block;padding:4px 8px;border-radius:8px;font-size:12px}
    .unread{background:#fee2e2;color:#991b1b}
    .read{background:#dcfce7;color:#166534}
    .btn{display:inline-block;padding:6px 10px;border-radius:8px;text-decoration:none;color:#fff;background:#004aad}
    .btn-del{background:#ef4444}
  </style>
</head>
<body>
<div class="wrap">
  <h2>All Passenger Messages</h2>
  <p><a class="btn" href="dashboard.php">Back to Dashboard</a></p>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Status</th>
        <th>Time</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($res && $res->num_rows > 0): ?>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><?= htmlspecialchars((string)$row['user_id']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td>
            <?php if ((int)$row['is_read'] === 0): ?>
              <span class="badge unread">Unread</span>
            <?php else: ?>
              <span class="badge read">Read</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
          <td>
            <?php if ((int)$row['is_read'] === 0): ?>
              <a class="btn" href="mark_read.php?id=<?= (int)$row['id'] ?>">Mark read</a>
            <?php endif; ?>
            <a class="btn btn-del" onclick="return confirm('Delete this message?')"
               href="delete_message.php?id=<?= (int)$row['id'] ?>">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="8">No messages found.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
