<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Delete passenger
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // শুধু passenger ডিলিট; admin না
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'passenger'");
    header("Location: manage_passengers.php");
    exit;
}

// Fetch passengers
$res = $conn->query("SELECT id, name, email, created_at FROM users WHERE role='passenger' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Passengers</title>
  <link rel="stylesheet" href="admin_table.css">
</head>
<body>
  <h2>Passengers</h2>
  <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Joined</th>
      <th>Action</th>
    </tr>
    <?php while ($u = $res->fetch_assoc()) { ?>
      <tr>
        <td><?php echo htmlspecialchars($u['id']); ?></td>
        <td><?php echo htmlspecialchars($u['name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo htmlspecialchars($u['created_at']); ?></td>
        <td>
          <a href="manage_passengers.php?delete=<?php echo $u['id']; ?>"
             onclick="return confirm('Delete this passenger?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
