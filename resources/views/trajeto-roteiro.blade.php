<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Trechão - Trajeto de Roteiro</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap.min.css" />
        <link rel="stylesheet" href="style.css" />
        <script src="https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css" rel="stylesheet" />
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
        <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css" type="text/css"/>
        <style>
            body{
                font-family: 'Roboto', sans-serif;
                text-align-last: center;
            }
            .trajeto-roteiro .container-fluid{
                padding: 0;
            }
            .trajeto-roteiro .title{
                background: #2870fb;
                padding: 10px 15px;
                weight: bold;
                font-size: 18px;
            }
            .trajeto-roteiro .title a{
                color: #fff;
            }
            .trajeto-roteiro .title a{
                text-decoration: none;
            }
            .trajeto-roteiro .mapa{
                height: 200px;
                background: #eee;
                text-align: center;
            }
            .trajeto-roteiro h2{
                color: #2870fb;
                font-size: 18px;
                font-weight: bold;
            }
            .trajeto-roteiro p{
                color: #999;
                font-size: 14px;
            }
            .trajeto-roteiro .roteiro-info{
                border-bottom: 1px solid #eee;
                padding: 20px 30px;
            }
            .trajeto-roteiro .card{
                 margin: 20px;
            }
            .trajeto-roteiro .tempo-roteiro{
                font-size: 40px;
                font-weight: bold;
                color: #2870fb;
            }
            .trajeto-roteiro .card p{
                font-size: 18px;
                line-height: 25px;
            }
        </style>
	</head>
	<body>
		<div class="trajeto-roteiro">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="title">
							<a href="#">
								<span><</span>
								TRAJETO: MS » BA
							</a>
						</div>
					</div>
					<div class="col-md-12 mapa" id="map">
					</div>
					<div class="col-md-12 roteiro-info">
						<h2 class="mb-0">5 dias e 2 horas (2.055 km)</h2>
						<p class="mb-0">Trajeto mais rápido - 150L de diesel</p>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="media">
									<span class="tempo-roteiro">
										3H
									</span>
									<div class="media-body ml-3">
										<p class="mb-0">
											<strong>DIRIGIR </strong><br />
											Pontinha do coxo BR - 201
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="media">
									<span class="tempo-roteiro">
										3H
									</span>
									<div class="media-body ml-3">
										<p class="mb-0">
											<strong>DIRIGIR </strong><br />
											Pontinha do coxo BR - 201
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="media">
									<span class="tempo-roteiro">
										1H
									</span>
									<div class="media-body ml-3">
										<p class="mb-0">
											<strong>DESCANSAR </strong><br />
											Chiparia Da Rota - BR 102
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="media">
									<span class="tempo-roteiro">
										2H
									</span>
									<div class="media-body ml-3">
										<p class="mb-0">
											<strong>DORMIR </strong><br />
											Posto Ipiranga BR 267
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="media">
									<span class="tempo-roteiro">
										15m
									</span>
									<div class="media-body ml-3">
										<p class="mb-0">
											<strong>FALAR C/ FAMILIARES </strong><br />
											Faça uma vídeo-chamada
										</p>
									</div>
								</div>
							</div>
						</div>

						<hr class="space" />

					</div>
				</div>
			</div>
		</div>

	</body>
</html>
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibmF0aGllbGVtcyIsImEiOiJja2Jma2V4ZGgwd2RyMnltdTVscjNuam5hIn0.UAMNxEFtF-9lW6p3xxUZrA';
    var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-96, 37.8], // starting position
        zoom: 3 // starting zoom
    });

    var url = "https://api.mapbox.com/directions/v5/mapbox/driving/" + window.start +";"+ window.finish  +"?geometries=geojson&steps=true&overview=full&access_token=" + mapboxgl.accessToken;

    var req = new XMLHttpRequest();
    req.open('GET', url, true);
    req.onload = function() {
        
        var json = JSON.parse(req.response);
        var data = json.routes[0];
        var route = data.geometry.coordinates;

        var geojson = {
        type: 'Feature',
        properties: {},
        geometry: {
            type: 'LineString',
            coordinates: route
        }
        };
        // if the route already exists on the map, reset it using setData
        // if (map.getSource('route')) {
        // map.getSource('route').setData(geojson);
        // } else { // otherwise, make a new request
        map.addLayer({
            id: 'route',
            type: 'line',
            source: {
            type: 'geojson',
            data: {
                type: 'Feature',
                properties: {},
                geometry: {
                    type: 'LineString',
                    coordinates: geojson
                }
            }
            },
            layout: {
            'line-join': 'round',
            'line-cap': 'round'
            },
            paint: {
            'line-color': '#3887be',
            'line-width': 5,
            'line-opacity': 0.75
            }
        });

        map.getSource('route').setData(geojson);
    // }
    // add turn instructions here at the end
  };
  req.send();
</script>