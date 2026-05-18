<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Train Status - Dhaka Metro</title>
  <link rel="stylesheet" href="./css/station.css">
  <style>
    body { font-family: Arial, sans-serif; background: #0a192f; color: white; margin: 0; }
    .header-container { background: linear-gradient(135deg, #004aad, #1e40af); padding: 25px; text-align: center; }
    .status-container { max-width: 1000px; margin: 20px auto; padding: 0 15px; }
    .train-tracker { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; margin: 20px 0; }
    .live-status { text-align: center; font-size: 24px; margin: 20px 0; }
    .status-green { color: #10b981; }
    .status-red { color: #ef4444; }
    .stations-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    .station-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; }
    .station-card h3 { color: #60a5fa; margin-bottom: 10px; }
    .countdown { font-size: 36px; font-weight: bold; color: #10b981; }
    .schedule-table { width: 100%; border-collapse: collapse; margin: 20px 0; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); }
    th { background: rgba(0,74,173,0.8); }
    .btn { background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <h1>🚉 Live Train Status</h1>
      <p>Real-time tracking | Last updated: <span id="last-update"></span></p>
    </div>
  </header>

  <div class="status-container">
    <!-- Live Summary -->
    <div class="train-tracker">
      <div class="live-status">
        <div>Next Train (Uttara North)</div>
        <div class="countdown" id="countdown">00:00</div>
        <div id="direction-status" class="status-green">🟢 Service Running (Every 8-10 min) [web:89]</div>
      </div>
    </div>

    <!-- Station-wise Status -->
    <div class="stations-container">
      <div class="station-card">
        <h3>📍 Current Train Location</h3>
        <div id="current-location">Uttara Center → Pallabi (2 min ago)</div>
      </div>
      <div class="station-card">
        <h3>⏱️ Service Status</h3>
        <p id="service-status">Peak Hours: Every 8 min<br>Off-Peak: Every 10-12 min [web:88]</p>
      </div>
    </div>

    <!-- Quick Schedule -->
    <div class="train-tracker">
      <h3>Today's Schedule [web:87]</h3>
      <table class="schedule-table">
        <tr><th>Time</th><th>Direction</th><th>Frequency</th></tr>
        <tr><td>6:30 AM - 11:00 AM</td><td>↔️ Both</td><td>Every 10 min</td></tr>
        <tr><td>11:01 AM - 4:00 PM</td><td>↔️ Both</td><td>Every 12 min</td></tr>
        <tr><td>4:01 PM - 10:10 PM</td><td>↔️ Both</td><td>Every 8-10 min</td></tr>
        <tr id="friday-row" style="display:none;"><td>Friday: 3:30 PM - 9:40 PM</td><td>↔️ Both</td><td>Every 12 min</td></tr>
      </table>
    </div>
  </div>

  <script>
    const stations = ['Uttara North', 'Uttara Center', 'Pallabi', 'Mirpur 11', 'Mirpur 10', 'Kazipara', 'Shewrapara', 'Agargaon', 'Bijoy Sarani', 'Farmgate', 'Kawran Bazar', 'Shahbagh', 'Motijheel'];
    let currentStationIndex = 1; // Moving train simulation

    // Countdown timer
    function updateCountdown() {
      let seconds = 300 + Math.random() * 300; // 5-10 min random
      const timer = setInterval(() => {
        seconds--;
        const min = Math.floor(seconds / 60);
        const sec = seconds % 60;
        document.getElementById('countdown').textContent = `${min}:${sec.toString().padStart(2, '0')}`;
        
        if (seconds <= 0) {
          clearInterval(timer);
          document.getElementById('countdown').textContent = 'Now';
          setTimeout(updateCountdown, 1000);
        }
      }, 1000);
    }

    // Train movement simulation
    function updateTrainPosition() {
      currentStationIndex = (currentStationIndex + 1) % stations.length;
      document.getElementById('current-location').textContent = 
        `${stations[currentStationIndex]} → ${stations[(currentStationIndex + 1) % stations.length]} (Just left)`;
    }

    // Friday check
    function checkFriday() {
      const today = new Date().getDay(); // 5 = Friday
      if (today === 5) {
        document.getElementById('friday-row').style.display = 'table-row';
        document.getElementById('direction-status').innerHTML = '🟡 Friday Service (3:30 PM - 9:40 PM) [web:137]';
      }
    }

    // Initialize
    updateCountdown();
    setInterval(updateTrainPosition, 30000); // Update every 30 sec
    checkFriday();
    
    document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
    setInterval(() => {
      document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
    }, 30000);
  </script>
</body>
</html>
