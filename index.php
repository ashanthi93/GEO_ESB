<!DOCTYPE html>
<html>

	<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
		header("Access-Control-Allow-Headers: *");
	?>

	<head>
		
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="index.css" rel="stylesheet" type="text/css">
        
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        
        <style>
			body{
				background: url('weather.jpg') no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
			
			 #map {
				width: 400px;
				height: 400px;
			}
        </style>
        
	</head>
	
	<body>

		<div class="section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1 class="text-center" style="color: white; font-weight: bold; font-size: 60px;"> Weather Report Through Location</h1>
					</div>
				</div>
			</div>
		</div>

		<div class="section">	
			<div class="container">
				<div class="row">
					<div class="col-md-12">

								<div class="col-md-4 col-md-offset-1">
									<div id="map"></div>
										<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
										<script>
											  window.onload = function () {

												var mapOptions = {
												center: new google.maps.LatLng(8.059229627200192, 80.6121826171875),
												zoom: 7,
												mapTypeId: google.maps.MapTypeId.ROADMAP
												};
												
												var infoWindow = new google.maps.InfoWindow();
												var latlngbounds = new google.maps.LatLngBounds();
												var map = new google.maps.Map(document.getElementById('map'), mapOptions);
												google.maps.event.addListener(map, 'click', function (e) {

													var lat = e.latLng.lat();
													var lon = e.latLng.lng();

													document.getElementById('lat').value = lat;
													document.getElementById('lon').value = lon;
													
													document.getElementById('GEObtn').disabled = false;
													document.getElementById('lat').disabled = false;
													document.getElementById('lon').disabled = false;

												});
											  }
										</script>
								</div>

								<div class="col-md-5 col-md-offset-2">
									<p id="intro" style="color: white; font-weight: bold; font-size: 20px;"> Select a location and get the Weather Report </p>

									<br/>
									
									<form class="form-horizontal" role="form">
								
										<div class="form-group">
											<div class="col-sm-3">																		
												<label for="inputLat" class="control-label" style="color: white; font-weight: bold; font-size: 20px;">Latitude</label>
											</div>
											<div class="col-sm-4">
												<input type="text" id="lat" class="form-control input-lg" placeholder="Latitude" disabled>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-3">
												<label for="inputLon" class="control-label" style="color: white; font-weight: bold; font-size: 20px;">Longitude</label>
											</div>

											<div class="col-sm-4">
												<input type="text" class="form-control input-lg" id="lon" placeholder="Longitude" disabled>
											</div>
										</div>
								
										<br/>

										<div class="form-group">
											<div class="col-md-offset-3 col-md-5">
												<button id="GEObtn" type="button" onclick="getESBResponse()" class="btn btn-primary" style="color: white; font-weight: bold; font-size: 20px;" disabled>Get Weather Report</button>
											</div>
										</div>
										
									</form>						
								</div>
						
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Error -->
        <div class="modal" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="color: red;">                        
                        <h3 class="modal-title" id="myModalLabel" style="font-size: 30px; font-weight: bold;"> Error </h3>
                    </div>
                    <div class="modal-body">
                        <p id="errorMsg" style="font-weight: normal; font-size:20px;"> There is no city for the enetered latitude and longitude values </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-dismiss="modal"> OK </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Success -->
        <div class="modal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="color: green;">                        
                        <center> <h4 class="modal-title" id="myModalLabel" style="font-size: 30px; font-weight: bold;"> Weather Report </h4> </center>
                    </div>
                    <div class="modal-body">
                        <center> <p id="successMsg" style="font-weight: normal; font-size:20px;"> </p> </center>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-dismiss="modal"> OK </button>
                    </div>
                </div>
            </div>
        </div>

		<p id="demo"> </p>
		
		<script>
			
			function getESBResponse() {
			
				var lat = document.getElementById("lat").value;
				var lon = document.getElementById("lon").value;
				
				var baseUrl = "http://ashanthi-inspiron-3543:8280/services/GEOLocation?lat=" + lat + "&lon=" + lon + "";
				
				//alert(baseUrl);
				
				var xhr = new XMLHttpRequest();
				
				if ("withCredentials" in xhr) {
					
					xhr.onerror = function() {
						alert('Invalid URL or Cross-Origin Request Blocked.');
					}
					
					xhr.onload = function() {
						
						var responseData = JSON.parse(this.responseText);
						processESBResponse(responseData);
					};
					
					xhr.open('GET', baseUrl);
					xhr.send();
					
				} else {
					alert("CORS is not supported for this browser!");
				}

			}


			function processESBResponse(responseData){
				
				if (responseData.message == null){
					
					var locationDetails = responseData.sys;
					var weatherDetails = responseData.main;
					var windDetails = responseData.wind;
					var descriptionDetails = responseData.weather[0];
					
					document.getElementById("successMsg").innerHTML =
						
						"Country : " + locationDetails.country + "<br/>" +
						"City : " + responseData.name + "<br/>" +
						"Description : " + descriptionDetails.description + "<br/>" +
						"Temperature : " + weatherDetails.temp + "<br/>" +
						"Pressure : " + weatherDetails.pressure + "<br/>" +
						"Humidity : " + weatherDetails.humidity + "<br/>" +
						"Wind Speed : " + windDetails.speed + "<br/>" +
						"Wind Degree : " + windDetails.deg;
					
					$('#successModal').modal('show');
					
				} else{
					
					$('#errorModal').modal('show');
											
				}
			}
			
		</script>
	</body>
</html>
