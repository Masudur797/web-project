<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Ticketing - Dhaka Metro</title>
  <link rel="stylesheet" href="./css/station.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
    .header-container { background: #004aad; color: white; padding: 20px; text-align: center; }
    .stations-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 20px 0; max-width: 1000px; margin: 20px auto; padding: 0 15px; }
    .station-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-left: 4px solid #004aad; }
    .station-card h2 { color: #004aad; margin-bottom: 10px; font-size: 18px; }
    .btn { background: #004aad; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; width: 100%; margin: 5px 0; }
    .btn:hover { background: #003a8c; }
    #qr-result img { max-width: 150px; margin-top: 10px; }
    .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 30px auto; max-width: 1000px; padding: 0 15px; }
    .features li { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <h1>🛡️ Smart Ticketing System</h1>
      <p>Universal Ticketing System (UTS) - Card Tap & QR Payment</p>
    </div>
  </header>

  <main>
    <div class="stations-container">
      <div class="station-card">
        <h2>💳 Card Tap (UTS)</h2>
        <p id="uts-status">🔄 Loading...</p>
        <p>Debit/Credit card দিয়ে gate এ tap করো। Ticketless travel!</p>
        <button class="btn" onclick="checkBalance()">Check MRT Pass Balance</button>
      </div>

      <div class="station-card">
        <h2>📱 Mobile Banking QR</h2>
        <p>bKash, Rocket দিয়ে QR code generate করো। Entry/Exit এ scan।</p>
        <input type="number" id="fare-amount" placeholder="Enter fare (৳)" style="width:100%;padding:8px;margin:5px 0;">
        <button class="btn" onclick="generateQR()">Generate QR Code</button>
        <div id="qr-result"></div>
      </div>

      <div class="station-card">
        <h2>🪪 MRT Pass</h2>
        <p>Rechargeable smart card। 10% discount পাও [web:104]</p>
        <a href="https://play.google.com/store/apps/details?id=net.adhikary.mrtbuddy" target="_blank" class="btn">📱 MRT Buddy App</a>
      </div>

      <div class="station-card">
        <h2>🎫 Ticket Vending Machine</h2>
        <p>Station এ self-service machine থেকে cash/card দিয়ে কিনো [web:1]</p>
      </div>
    </div>

    <div class="features">
      <ul>
        <li>✅ No more ticket queues</li>
        <li>✅ Contactless payment</li>
        <li>✅ Mobile QR tickets</li>
        <li>✅ Pass balance tracking</li>
        <li>✅ 10% Pass discount</li>
        <li>✅ Live status update</li>
      </ul>
    </div>
  </main>

  <script>
    // UTS Live Status
    setInterval(() => {
      document.getElementById('uts-status').innerHTML = 
        new Date().getHours() >= 9 && new Date().getHours() <= 21 ? '🟢 Live Now' : '🔄 Coming Soon (2026)';
    }, 2000);

    // MRT Pass Balance Check
    function checkBalance() {
      const balance = Math.floor(Math.random() * 500) + 50;
      const status = balance < 20 ? '❌ Low Balance - Recharge!' : '✅ Good Balance';
      alert(`🪪 MRT Pass Balance: ৳${balance}\n${status}`);
    }

    // QR Code Generator
    function generateQR() {
      const amount = document.getElementById('fare-amount').value;
      if(amount && amount > 0) {
        document.getElementById('qr-result').innerHTML = 
          `✅ QR Generated for ৳${amount}<br>
           <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=bKash:MetroRail:${amount}" alt="QR Code">`;
      } else {
        alert('Please enter valid fare amount');
      }
    }
  </script>
</body>
</html>

