<!DOCTYPE html>
<html>
	<head>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
        <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css" type="text/css"/>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Trechão - Seleção de Roteiro</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet"  />
        <link rel="stylesheet"  />
        <style>
            body{
                font-family: 'Roboto', sans-serif;
                text-align-last: center;
                margin: 0; 
                padding: 0;
            }
            hr.space {
                background: #fff none repeat scroll 0 0;
                color: #fff;
                visibility: hidden
            }
            hr {
                background: #e5e7ed none repeat scroll 0 0;
                border: medium none;
                clear: both;
                color: #ddd;
                float: none;
                height: 1px;
                margin: 0 0 1.45em;
                width: 100%;
            }
            .splash{
                background: #2870fb;
                height: 100vh;
            }
            .splash .row{
                height: 100vh;
            }
            .splash .row a{
                color: #fff;
                font-weight: bold;
                font-size: 20px;
            }
            .splash .row a:hover{
                text-decoration: none;
            }
            .selecao-roteiro .container-fluid{
                padding: 0;
            }
            .selecao-roteiro .mapa{
                height: 200px;
                background: #eee;
                text-align: center;
            }
            .selecao-roteiro h2{
                font-size: 20px;
                text-align-last: center;
            }
            .selecao-roteiro .form{
                 padding: 20px;
            }
            .selecao-roteiro .btn-primary{
                background: #007bff;
                border-radius: 30px;
                text-transform: uppercase;
                padding: 10px 20px;
                color: #fff;
                border-color: #007bff;
            }
            .map { 
                position: absolute; 
                top: 0; 
                bottom: 0; 
                width: 100%; 
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
                 border: 1px solid rgba(0,0,0,.125);
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
		<div class="selecao-roteiro">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 mapa" id="map">
                        
					</div>

					<hr class="space" />

					<div class="col-md-12 text-center">
						<h2>VAI PRA ONDE AMIGO <br> CAMINHONEIRO?</h2>
						<hr class="space" />
						<div class="form">
							<form action="">
								<div id="geocoder" class="geocoder"></div>
								<hr class="space" />
								{{-- <button  class="btn btn-primary" id="calcular">Calcular Rota</button> --}}
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="col-md-12 trajeto-roteiro">
           <div id="roteiro"></div>
        </div>
	</body>
</html>

<script>
const stepsHour = [{
            name: 'Almoço',
            time: [12, 13],
            parado: 60,
            lastDay: null
        }, {
            name: 'Café da noite',
            time: [19, 20],
            parado: 60,
            lastDay: null
        }, {
            name: 'Dormir',
            time: [0, 8],
            parado: 480,
            lastDay: null
        }]


    mapboxgl.accessToken = 'pk.eyJ1IjoibmF0aGllbGVtcyIsImEiOiJja2Jma2V4ZGgwd2RyMnltdTVscjNuam5hIn0.UAMNxEFtF-9lW6p3xxUZrA';
    var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-96, 37.8], // starting position
        zoom: 3 // starting zoom
    });

    var geolocate = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true
    });

    // Add geolocate control to the map.
    map.addControl(
        geolocate
    );

    map.on('load', function()
    {
        geolocate.trigger();
    });

    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
    });

    var currentMarkers  = [];

    geocoder.on('result', function(ev) {
        
        var start = [parseFloat(ev.result.center[0]), parseFloat(ev.result.center[1])];
        var finish = [parseFloat(geolocate._lastKnownPosition.coords.longitude), parseFloat(geolocate._lastKnownPosition.coords.latitude)];
    
        var url = "https://api.mapbox.com/directions/v5/mapbox/driving/" + start+";"+ finish  +"?geometries=geojson&steps=true&overview=full&access_token=" + mapboxgl.accessToken;

        var paradas = [];
        var req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.onload = function() {
            
            var json = JSON.parse(req.response);
            var data = json.routes[0];
            var route = data.geometry.coordinates;
            var duration = Math.floor(data.duration / 60);

            var coordinates = data.legs[0].steps

            var partida = new Date(Date.now())

            var inTime = (date) => {
                hour = date.getHours()
                var finded = stepsHour.find(a => {
                    var min = Math.min.apply(Math, a.time),
                    max = Math.max.apply(Math, a.time);
                    return hour >= min && hour <= max && (a.lastDay == null || a.lastDay < date.getDay());
                })

                return finded;
            }

            while(coordinates.length > 0)
            {
                var cordinate = coordinates.pop()

                partida.setTime(partida.getTime() + (cordinate.duration * 1000));

                var step = inTime(partida)

                if (step)
                {
                    paradas.push({name: step.name, hour: partida.getHours(), minute: partida.getMinutes(), local: cordinate.name})
                    step.lastDay = partida.getDay();
                    currentMarkers.push(new mapboxgl.Marker().setLngLat(cordinate.geometry.coordinates[0]).addTo(map));
                }

            }

            var steps = paradas.map(a => 
                 "<div class='card'>"+
                     "<div class='card-body'>"+
                         "<div class='media'>"+
                             "<span class='tempo-roteiro'>"+
                                 a.hour+ ":"+ a.minute+
                             "</span>"+
                             "<div class='media-body ml-3'>"+
                                 "<p class='mb-0'>"+
                                     "<strong>" + a.name + "</strong><br/>"+
                                      a.local+
                                 "</p>"+
                             "</div>"+
                        "</div>"+
                    "</div>"+
                "</div>"
            ).join();

            $('#roteiro').html(steps);

            var geojson = {
        type: 'Feature',
        properties: {},
        geometry: {
            type: 'LineString',
            coordinates: route
        }
        };

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
        }
        req.send();
    });

    document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
</script>

{{-- <div class="card">
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
</div> --}}