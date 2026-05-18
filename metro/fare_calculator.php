<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fare Calculator - Dhaka Metro</title>
  <link rel="stylesheet" href="./css/station.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
    .header-container { background: linear-gradient(135deg, #004aad, #007bff); color: white; padding: 25px; text-align: center; }
    .calc-container { max-width: 800px; margin: 30px auto; padding: 0 20px; background: white; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .calc-section { padding: 25px; text-align: center; }
    select, input { width: 100%; padding: 12px; margin: 10px 0; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; }
    .btn { background: #28a745; color: white; border: none; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-size: 18px; width: 100%; margin: 10px 0; }
    .btn:hover { background: #218838; }
    .fare-result { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 20px; border-radius: 12px; margin: 20px 0; font-size: 24px; font-weight: bold; }
    .fare-table { margin: 20px 0; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #eee; }
    th { background: #004aad; color: white; }
    .pass-discount { background: #ffc107; color: #212529; font-weight: bold; }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <h1>💰 Fare Calculator</h1>
      <p>Base Fare: ৳20 | Per KM: ৳5 | Max: ৳100 | Pass: 10% Discount [web:4][web:124]</p>
    </div>
  </header>

  <div class="calc-container">
    <div class="calc-section">
      <h2>🚌 Calculate Your Fare</h2>
      <select id="from-station">
        <option value="">Select Starting Station</option>
        <option value="0">Uttara North</option>
        <option value="1">Uttara Center</option>
        <option value="2">Pallabi</option>
        <option value="3">Mirpur 11</option>
        <option value="4">Mirpur 10</option>
        <option value="5">Kazipara</option>
        <option value="6">Shewrapara</option>
        <option value="7">Agargaon</option>
        <option value="8">Bijoy Sarani</option>
        <option value="9">Farmgate</option>
        <option value="10">Kawran Bazar</option>
        <option value="11">Shahbagh</option>
        <option value="12">Motijheel</option>
      </select>

      <select id="to-station">
        <option value="">Select Destination</option>
        <option value="0">Uttara North</option>
        <option value="1">Uttara Center</option>
        <option value="2">Pallabi</option>
        <option value="3">Mirpur 11</option>
        <option value="4">Mirpur 10</option>
        <option value="5">Kazipara</option>
        <option value="6">Shewrapara</option>
        <option value="7">Agargaon</option>
        <option value="8">Bijoy Sarani</option>
        <option value="9">Farmgate</option>
        <option value="10">Kawran Bazar</option>
        <option value="11">Shahbagh</option>
        <option value="12">Motijheel</option>
      </select>

      <button class="btn" onclick="calculateFare()">Calculate Fare</button>
      <div id="fare-result" class="fare-result" style="display:none;"></div>
    </div>

    <div class="calc-section">
      <h3>Fare Chart (Uttara North → Motijheel) [web:124]</h3>
      <div class="fare-table">
        <table>
          <tr><th>Station</th><th>Regular Fare</th><th>MRT Pass (10% Off)</th></tr>
          <tr><td>Uttara North</td><td>৳20</td><td class="pass-discount">৳18</td></tr>
          <tr><td>Uttara Center</td><td>৳20</td><td class="pass-discount">৳18</td></tr>
          <tr><td>Pallabi</td><td>৳30</td><td class="pass-discount">৳27</td></tr>
          <tr><td>Mirpur 11</td><td>৳30</td><td class="pass-discount">৳27</td></tr>
          <tr><td>Mirpur 10</td><td>৳40</td><td class="pass-discount">৳36</td></tr>
          <tr><td>Kazipara</td><td>৳40</td><td class="pass-discount">৳36</td></tr>
          <tr><td>Shewrapara</td><td>৳50</td><td class="pass-discount">৳45</td></tr>
          <tr><td>Agargaon</td><td>৳60</td><td class="pass-discount">৳54</td></tr>
          <tr><td>Bijoy Sarani</td><td>৳70</td><td class="pass-discount">৳63</td></tr>
          <tr><td>Farmgate</td><td>৳80</td><td class="pass-discount">৳72</td></tr>
          <tr><td>Kawran Bazar</td><td>৳80</td><td class="pass-discount">৳72</td></tr>
          <tr><td>Shahbagh</td><td>৳90</td><td class="pass-discount">৳81</td></tr>
          <tr><td>Motijheel</td><td>৳100</td><td class="pass-discount">৳90</td></tr>
        </table>
      </div>
    </div>
  </div>

  <script>
    function calculateFare() {
      const from = parseInt(document.getElementById('from-station').value);
      const to = parseInt(document.getElementById('to-station').value);
      
      if (!from || !to || from === to) {
        alert('Please select different From & To stations');
        return;
      }
      
      const distance = Math.abs(from - to);
      let fare = Math.min(20 + distance * 5, 100); // Base 20 + 5/km, max 100
      const passFare = Math.round(fare * 0.9);
      
      document.getElementById('fare-result').style.display = 'block';
      document.getElementById('fare-result').innerHTML = `
        <div>🚌 ${distance} Stations | ${distance * 1.7} km</div>
        <div>Regular: <strong>৳${fare}</strong></div>
        <div>MRT Pass: <strong class="pass-discount">৳${passFare}</strong> (10% OFF)</div>
        <div style="font-size:14px;margin-top:10px;">* Valid for 60 minutes [web:4]</div>
      `;
    }
  </script>
</body>
</html>
