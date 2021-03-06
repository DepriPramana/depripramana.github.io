$(function () {
	var xmlhttp, myObj, x, txt = "";
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
		myObj = JSON.parse(this.responseText);
		var ttlKonfirmasi = myObj.konfirmasi.total;
		var tbhKonfirmasi = myObj.konfirmasi.tambahan;
		var ttlPerawatan = myObj.perawatan.total;
		var tbhPerawatan = myObj.perawatan.tambahan;
		var ttlMeninggal = myObj.meninggal.total;
		var tbhMeninggal = myObj.meninggal.tambahan;
		var ttlSembuh = myObj.sembuh.total;
		var tbhSembuh = myObj.sembuh.tambahan;
		
		document.getElementById("ttlKonfirmasi").innerHTML = numeral(ttlKonfirmasi).format('0,0');
		document.getElementById("tbhKonfirmasi").innerHTML = numeral(tbhKonfirmasi).format('0,0');
		document.getElementById("ttlPerawatan").innerHTML = numeral(ttlPerawatan).format('0,0');
		document.getElementById("tbhPerawatan").innerHTML = numeral(tbhPerawatan).format('0,0');
		document.getElementById("ttlMeninggal").innerHTML = numeral(ttlMeninggal).format('0,0');
		document.getElementById("tbhMeninggal").innerHTML = numeral(tbhMeninggal).format('0,0');
		document.getElementById("ttlSembuh").innerHTML = numeral(ttlSembuh).format('0,0');
		document.getElementById("tbhSembuh").innerHTML = numeral(tbhSembuh).format('0,0');
	  }
	};
	xmlhttp.open("GET", "https://persnickety-armor.000webhostapp.com/statistic.php", true);
	xmlhttp.send();
			
    var markers;
    $(document).ready(function(){
      markers = new GMaps({
        div: '#gmap_markers'
      });
	  
	  function writeAddressName(lat,lon) {
        var geocoder   = new google.maps.Geocoder();
		var userLatLng = new google.maps.LatLng(lat, lon);
        geocoder.geocode({
          "location": userLatLng
        },
        function(results, status) {
		if (status == google.maps.GeocoderStatus.OK){
            document.getElementById("address").innerHTML = results[0].formatted_address;	
		}
          
        });
      }
	  
	  function addData(lat, lon, acc, color, htmlData){
		  markers.drawCircle({
			lat: lat,
			lng: lon,
			radius: acc,
			fillColor: color,
          fillOpacity: 0.8,
          strokeColor: '#0000FF',
          strokeOpacity: 0.5
			});
		  markers.addMarker({
			lat: lat,
			lng: lon,
			title: 'Marker with InfoWindow',
			infoWindow: {
				content: htmlData
			}
			});
	  }
	  function GetData(lat,lon){
		var xmlhttp, myObj = "";
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		  if (this.readyState == 4 && this.status == 200) {
			//myObj = JSON.parse(this.responseText);	
			myObj = this.responseText;
			  $(document).ready(function(){
			  $.getJSON( "https://persnickety-armor.000webhostapp.com/data.php?lat="+lat+"&lon="+lon, function( data ) {
				  $('#dataTable').DataTable({
					 data : data,
					  deferRender: true,
					 columns: [
					   { "data": null,
						   render: function (data, type, row, meta) {
									 return meta.row + meta.settings._iDisplayStart + 1;
									}  
						},
					  { data: 'kode_kecamatan' },
					  { data: 'daerah' },
					  { data: 'jumlah_penduduk' },
					  { data: 'total' },
					  { data: 'density' },
					  { data: 'levelKerawanan' },
					  
					]
				});
				});
			});
			displayData(myObj);
		  }
		};
		xmlhttp.open("GET", "https://persnickety-armor.000webhostapp.com/data.php?lat="+lat+"&lon="+lon, true);
		xmlhttp.send();
		
	  }
	  
	  
	  function displayData(objData){
		var x= "";	
		var color ="";
		objData=JSON.parse(objData);
		for (x in objData) {
			if(objData[x].levelKerawanan > 1){color = '#dc3545';}else{color= '#FFA500'}			
			 addData(objData[x].loc.lat, objData[x].loc.lon, 400, color, '<p>Ini lokasi serangan covid</p><p>Total: '+objData[x].total+' ,'+objData[x].daerah+'</p><p>LevelKerawanan: '+objData[x].levelKerawanan+'</p>');
			}
	  }
      GMaps.geolocate({
        success: function(position){
          markers.setCenter(position.coords.latitude, position.coords.longitude);
		  
			// Write the formatted address				
		  writeAddressName(position.coords.latitude, position.coords.longitude);
		  GetData(position.coords.latitude, position.coords.longitude);
		  addData(position.coords.latitude, position.coords.longitude, 0,'#FFA500', '<p>Ini lokasi anda</p>');
		  //console.log(position.coords.accuracy);
        },
        error: function(error){
          alert('Geolocation failed: '+error.message);
        },
        not_supported: function(){
          alert("Your browser does not support geolocation");
        },
        always: function(){
          //alert("Done!");
        }
      });
    });
  
	
	

    
});
