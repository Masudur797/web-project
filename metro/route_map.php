<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🗺️ MRT Line 6 Route Map</title>
  <link rel="stylesheet" href="./css/station.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
    .header-container { background: linear-gradient(135deg, #004aad, #007bff); color: white; padding: 20px; text-align: center; }
    .map-container { height: 60vh; width: 100%; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.15); margin: 20px 0; }
    .controls { max-width: 1000px; margin: 20px auto; padding: 0 15px; display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; }
    .btn { background: #004aad; color: white; border: none; padding: 12px 24px; border-radius: 25px; cursor: pointer; font-weight: bold; }
    .btn:hover { background: #003a8c; }
    .btn.active { background: #10b981; }
    .info-panel { background: white; max-width: 1000px; margin: 20px auto; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .stations-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
    .station-card { padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #004aad; cursor: pointer; transition: all 0.3s; }
    .station-card:hover { background: #e2e8f0; transform: translateX(5px); }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <h1>🗺️ MRT Line 6 Route Map</h1>
      <p>13 Stations | 20.1 KM | Uttara North ↔ Motijheel</p>
    </div>
  </header>

  <div class="controls">
    <button class="btn active" onclick="showMap('full')">🗺️ Full Route</button>
    <button class="btn" onclick="showMap('north')">🟢 Uttara Area</button>
    <button class="btn" onclick="showMap('central')">🟡 Central Area</button>
    <button class="btn" onclick="showMap('south')">🔴 Motijheel</button>
  </div>

  <iframe class="map-container" id="map-frame" 
    src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d3651.23456789!2d90.399999!3d23.810000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1234567890!5m2!1sen!2sbd" 
    allowfullscreen="" loading="lazy">
  </iframe>

  <div class="info-panel">
    <h3 id="route-info">📍 Click any station for details</h3>
    <div class="stations-container" id="stations-list">
      <!-- Dynamic stations will load here -->
    </div>
  </div>

  <script>
    const stations = [
      {id:0, name:'Uttara North', lat:23.873, lng:90.398, dist:0, desc:'Northern Terminal'},
      {id:1, name:'Uttara Center', lat:23.866, lng:90.396, dist:2, desc:'Sector 12'},
      {id:2, name:'Pallabi', lat:23.859, lng:90.394, dist:4, desc:'Residential'},
      {id:3, name:'Mirpur 11', lat:23.852, lng:90.392, dist:5, desc:'Education hub'},
      {id:4, name:'Mirpur 10', lat:23.845, lng:90.390, dist:6, desc:'Mixed area'},
      {id:5, name:'Kazipara', lat:23.838, lng:90.388, dist:7.5, desc:'Residential'},
      {id:6, name:'Shewrapara', lat:23.831, lng:90.386, dist:9, desc:'Stadium'},
      {id:7, name:'Agargaon', lat:23.824, lng:90.384, dist:11, desc:'Govt offices'},
      {id:8, name:'Bijoy Sarani', lat:23.817, lng:90.382, dist:12.5, desc:'Shahbagh'},
      {id:9, name:'Farmgate', lat:23.810, lng:90.380, dist:13.5, desc:'Bus terminal'},
      {id:10,name:'Kawran Bazar', lat:23.803, lng:90.378, dist:14.5, desc:'Market'},
      {id:11,name:'Shahbagh', lat:23.796, lng:90.376, dist:15.5, desc:'Cultural'},
      {id:12,name:'Motijheel', lat:23.789, lng:90.374, dist:20.1, desc:'Financial hub'}
    ];

    function showMap(type) {
      // Update map URL based on selection
      const maps = {
        'full': 'https://www.google.com/maps/embed?pb=!1m28!...full route...',
        'north': 'https://www.google.com/maps/embed?pb=!1m28!...uttara...',
        'central': 'https://www.google.com/maps/embed?pb=!1m28!...central...',
        'south': 'https://www.google.com/maps/embed?pb=!1m28!...motijheel...'
      };
      
      document.getElementById('map-frame').src = maps[type] || maps['full'];
      document.querySelectorAll('.btn').forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
    }

    function loadStations() {
      let html = '';
      stations.forEach(station => {
        html += `
          <div class="station-card" onclick="selectStation(${station.id})">
            <h4>${station.name}</h4>
            <p>${station.desc} | ${station.dist} km</p>
          </div>
        `;
      });
      document.getElementById('stations-list').innerHTML = html;
    }

    function selectStation(id) {
      const station = stations[id];
      document.getElementById('route-info').innerHTML = `
        📍 ${station.name}<br>
        Distance from Uttara: ${station.dist} km<br>
        ${station.desc}
      `;
      // Zoom to station (Google Maps URL)
      window.open(`https://maps.google.com?q=${station.lat},${station.lng}`);
    }

    // Initialize
    loadStations();
  </script>
</body>
</html>
