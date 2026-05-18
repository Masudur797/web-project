<?php
session_start();
require_once "config.php";

$msg = "";
$msgClass = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;

    if ($name === '' || $email === '' || $message === '') {
        $msg = "❌ All fields are required";
        $msgClass = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email address";
        $msgClass = "error";
    } else {
        $sql = "INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $name, $email, $message);

        if ($stmt->execute()) {
            $msg = "✅ Message sent successfully";
            $msgClass = "success";
        } else {
            $msg = "❌ DB error: " . $conn->error;
            $msgClass = "error";
        }
        $stmt->close();
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Metro Station</title>
  <link rel="stylesheet" href="css/index.css">
</head>

<body class="open-sans-normal">

<header>
  <nav>
    <div class="logo">
      <img src="pic/logo.png" alt="Metro Logo">
    </div>

    <ul>
      <li><a href="index.php">Home</a></li>

      <li><a href="station.html">Stations</a></li>
      
      <li><a href="fare.php">Fare</a></li>

      <li><a href="notice.php">Notice</a></li>

      <li><a href="customer_support.html">Support</a></li>
      <li>
        <a href="login.php">
          <button class="button" type="button">Sign in</button>
        </a>
      </li>
    </ul>
  </nav>
</header>

<div class="banner">
  <div class="banner_content">
    <h2 class="scl-name">METRO RAIL SYSTEM</h2>
    <p class="banner-motto">
      A modern, fast and reliable metro rail service ensuring safe and affordable
      transportation for millions of passengers every day.
    </p>

    <a href="login.php">
      <button class="button" type="button">Book Ticket</button>
    </a>
  </div>

  <img class="banner-pic" src="pic/logo1.png" alt="Metro Banner">
</div>

<main>
  <section class="history">
    <div class="history_detail">
      <h2 class="history_header">ABOUT METRO RAIL</h2>
      <p class="history_para">
        Metro Rail is designed to reduce traffic congestion and provide fast,
        comfortable and eco-friendly transportation. It connects major locations
        of the city with modern stations and smart ticketing systems.
      </p>
      <a href="station.html">
        <button class="button" type="button">View Stations</button>
      </a>
    </div>

    <img class="history_img" src="pic/map1.png" alt="Metro Map">
  </section>

  <section class="lessons">
    <img class="learn-img" src="pic/train.png" alt="Metro Services">

    <div class="type-of-lessons">

      <div class="learn-box">
  <img class="learning-img" src="images/map.png" alt="">
  <a href="route_map.php" class="learn"> Route Map</a>
</div>


        <div class="learn-box">
      <img class="learning-img" src="images/ticket.png" alt="">
      <a href="smart_ticketing.php" class="learn">Smart Ticketing</a>
      </div>

      </div>

      <div class="learn-row">
        <div class="learn-box">
          <img class="learning-img" src="images/train.png" alt="">
          <a href="live_train_status.php" class="learn">Live Train Status</a>
        </div>
        <div class="learn-box">
          <img class="learning-img" src="images/security.png" alt="">
          <a href="passenger_safety.php" class="learn">Passenger Safety</a>
          
        </div>
      </div>

      <div class="learn-row">
        <div class="learn-box">
          <img class="learning-img" src="images/fare.png" alt="">
          <a href="fare_calculator.php" class="learn">Fare Calculator</a>
        </div>

        

      <p class="lessons-para">
        Our metro system provides smooth travel experience with modern
        infrastructure, real-time information and automated ticketing.
      </p>

    </div>
  </section>
</main>

<footer class="footer">
  <div class="Connect">
    <h2 class="foot-title">Metro Rail Authority</h2>
    <p>
      Managing stations, trains, ticketing and passenger safety with
      advanced technology and skilled professionals.
    </p>
  </div>

  <div class="Message-area">
    <h2 class="foot-title">Contact Us</h2>

    <?php if ($msg !== ""): ?>
      <p class="<?php echo htmlspecialchars($msgClass); ?>" style="font-weight:bold; text-align:center;">
        <?php echo htmlspecialchars($msg); ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="">
      <input class="input" type="text" name="name" placeholder="Your Name">
      <br>
      <input class="input" type="email" name="email" placeholder="Your Email">
      <br>
      <textarea class="input" name="message" placeholder="Your Message"></textarea>
      <br>
      <button class="button" type="submit" name="send">Send</button>
    </form>
  </div>
</footer>

</body>
</html>
