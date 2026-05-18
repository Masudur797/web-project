<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Passenger Safety - Dhaka Metro</title>
  <link rel="stylesheet" href="./css/station.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
    .header-container { background: #28a745; color: white; padding: 20px; text-align: center; }
    .stations-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 20px 0; max-width: 1000px; margin: 20px auto; padding: 0 15px; }
    .station-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-left: 4px solid #28a745; }
    .station-card h2 { color: #28a745; margin-bottom: 10px; font-size: 18px; }
    .btn { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; width: 100%; margin: 5px 0; }
    .btn:hover { background: #218838; }
    .emergency { background: #dc3545; color: white; padding: 15px; border-radius: 8px; margin: 20px auto; max-width: 1000px; text-align: center; }
    .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 30px auto; max-width: 1000px; padding: 0 15px; }
    .features li { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); list-style: none; text-align: center; }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <h1>🛡️ Passenger Safety</h1>
      <p>Your Safety is Our Priority - 24/7 Protection</p>
    </div>
  </header>

  <main>
    <!-- Emergency Contacts -->
    <div class="emergency">
      <h3>🚨 Emergency Contacts</h3>
      <p><strong>Hotline:</strong> 09613-333666 | <strong>MRT Police:</strong> 16430</p>
      <p>Station Control Rooms: <a href="#" onclick="showContacts()" style="color:#fff;">View All →</a></p>
    </div>

    <div class="stations-container">
      <div class="station-card">
        <h2>👩‍🚀 Women Reserved Coach</h2>
        <p>First coach exclusively for women। 2 female MRT police guard করে [web:113][web:117]</p>
        <p>Special women-only gate & platform sections</p>
      </div>

      <div class="station-card">
        <h2>📹 CCTV Surveillance</h2>
        <p>Every train + station এ 24/7 CCTV monitoring [web:111][web:120]</p>
        <p>Platform screen doors (PSD) for fall protection</p>
      </div>

      <div class="station-card">
        <h2>🚓 2 MRT Police per Train</h2>
        <p>Each train এ 2 জন dedicated MRT police officer [web:116]</p>
        <p>Lost items, crowd control, conflict resolution</p>
      </div>

      <div class="station-card">
        <h2>🚨 Emergency Buttons</h2>
        <p>Every coach এ emergency button। Direct OCC connect [web:110]</p>
        <p>Evacuation drill regularly practiced</p>
      </div>
    </div>

    <div class="features">
      <ul>
        <li>✅ No sharp objects allowed [web:108]</li>
        <li>✅ Metal detectors at entry [web:115]</li>
        <li>✅ Fire safety systems</li>
        <li>✅ Medical emergency response</li>
        <li>✅ Lost & found service</li>
        <li>✅ 24/7 station security</li>
      </ul>
    </div>
  </main>

  <script>
    function showContacts() {
      const contacts = [
        "Uttara North: 01332817051",
        "Uttara Center: 01332817052", 
        "Pallabi: 01332817054",
        "Motijheel: 01332817064"
      ];
      alert("📞 Station Control Rooms:\n\n" + contacts.join("\n"));
    }
  </script>
</body>
</html>
