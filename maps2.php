<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Geolocation and Google Maps API</title>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyB7vf8cvzpf-35mbJgX1z89WDcuLfq8M48&callback=initMap"></script>
    <script>
      function writeAddressName(latLng) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
          "location": latLng
        },
        function(results, status) {
          if (status == google.maps.GeocoderStatus.OK)
            document.getElementById("address").innerHTML = results[0].formatted_address;
          else
            document.getElementById("error").innerHTML += "Unable to retrieve your address" + "<br />";
        });
      }

      function geolocationSuccess(position) {
        var userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        // Write the formatted address
		document.cookie = "fbdata = " + position.coords.latitude + "," + position.coords.longitude;
            console.log(document.cookie);
			
        writeAddressName(userLatLng);

        var myOptions = {
          zoom : 50,
          center : userLatLng,
          mapTypeId : google.maps.MapTypeId.ROADMAP
        };
        // Draw the map
        var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
        // Place the marker
        new google.maps.Marker({
          map: mapObject,
          position: userLatLng
        });
        // Draw a circle around the user position to have an idea of the current localization accuracy
        var circle = new google.maps.Circle({
          center: userLatLng,
          radius: position.coords.accuracy,
          map: mapObject,
          fillColor: '#0000FF',
          fillOpacity: 0.5,
          strokeColor: '#0000FF',
          strokeOpacity: 0.5
        });
        mapObject.fitBounds(circle.getBounds());
      }

      function geolocationError(positionError) {
        document.getElementById("error").innerHTML += "Error: " + positionError.message + "<br />";
      }

      function geolocateUser() {
        // If the browser supports the Geolocation API
        if (navigator.geolocation)
        {
          var positionOptions = {
            enableHighAccuracy: true,
            timeout: 10 * 1000 // 10 seconds
          };
          navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationError, positionOptions);
        }
        else
          document.getElementById("error").innerHTML += "Your browser doesn't support the Geolocation API";
      }

      window.onload = geolocateUser;
    </script>
	<?php 
    if(isset($_COOKIE['fbdata'])) { 
        echo "welcome ".$_COOKIE['fbdata'];
    }
?>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    </style>
  </head>
  <body>
    <h1>Basic example</h1>
    <div id="map"></div>
    <p><b>Address</b>: <span id="address"></span></p>
    <p id="error"></p>
  </body>
</html>