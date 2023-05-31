<!DOCTYPE html>
<html>
<head>
  <title>Geocodificación con OpenLayers</title>
  <link rel="stylesheet" href="https://openlayers.org/en/v6.5.0/css/ol.css" type="text/css">
  <style>
    #map {
      width: 100%;
      height: 400px;
    }
  </style>
  <script src="https://openlayers.org/en/v6.5.0/build/ol.js"></script>
</head>
<body>
  <div id="map"></div>

  <?php
  // Dirección a geocodificar
  $address = "1600 Amphitheatre Parkway, Mountain View, CA";

  // API key de Google Maps
  $apiKey = "AIzaSyAVNS1lqK1cBs0raTgMXDpg_Rxp6C20ZQ4";

  // URL de la API de geocodificación de Google Maps
  $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

  // Realizar la solicitud HTTP a la API de geocodificación
  $response = file_get_contents($url);
  $data = json_decode($response, true);

  // Obtener las coordenadas de longitud y latitud
  $longitude = $data['results'][0]['geometry']['location']['lng'];
  $latitude = $data['results'][0]['geometry']['location']['lat'];
  ?>

  <script type="text/javascript">
    var longitude = <?php echo $longitude; ?>;
    var latitude = <?php echo $latitude; ?>;

    // Crear el mapa
    var map = new ol.Map({
      target: 'map',
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        })
      ],
      view: new ol.View({
        center: ol.proj.fromLonLat([longitude, latitude]),
        zoom: 15
      })
    });

    // Añadir un marcador en las coordenadas
    var marker = new ol.Feature({
      geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude]))
    });

    var iconStyle = new ol.style.Style({
      image: new ol.style.Icon({
        anchor: [0.5, 1],
        src: 'https://openlayers.org/en/v6.5.0/examples/data/icon.png'
      })
    });

    marker.setStyle(iconStyle);

    var vectorSource = new ol.source.Vector({
      features: [marker]
    });

    var vectorLayer = new ol.layer.Vector({
      source: vectorSource
    });

    map.addLayer(vectorLayer);
  </script>
</body>
</html>
