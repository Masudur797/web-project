<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: passenger_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Passenger Dashboard</title>
  <link rel="stylesheet" href="css/passenger_dashboard.css">
</head>
<body>
  <div class="dash-wrapper">
    <header class="dash-header">
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
      <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="dash-main">
      <a class="dash-card" href="profile.php">
        <h3>My Profile</h3>
        <p>View and update your personal information.</p>
      </a>

      <a class="dash-card" href="fare.php">
        <h3>Fare &amp; Book Ticket</h3>
        <p>Calculate fare and book your metro ticket.</p>
      </a>

      <a class="dash-card" href="my_tickets.php">
        <h3>My Tickets</h3>
        <p>See your previous bookings and print tickets.</p>
      </a>
    </main>
  </div>
</body>
</html>