<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>PHP/MySQL & Google Maps Example</title>
	<style>
	body {
		margin:0;
	}
	</style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYfzhxw6fougyZGbAYZo_gocSKK6ialGw"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      proposed: {
        icon: 'img/markers/marker_red.png'
      },
      allocated: {
        icon: 'img/markers/marker_blue.png'
      },
	  planted: {
        icon: 'img/markers/marker_green.png'
      },
	  named: {
        icon: 'img/markers/marker_gold.png'
      }
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(-37.877748, 144.682556),
        zoom: 12,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("map_all_xml.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
		  var treename = markers[i].getAttribute("treename");
		  if (treename != ""){ name = treename + " ("+name+")"}
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
		  var comname = markers[i].getAttribute("comname");
		  var botname = markers[i].getAttribute("botname");
		  var origin = markers[i].getAttribute("origin");
		  var owner = markers[i].getAttribute("owner");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address + " <br/> " + comname + "<br/> <i>"+ botname;
		  if (origin != ""){ html += "("+ origin +")";}
		  html += "</i>";
		  if (owner == ""){html += "<br/> <a href='buy.php?tree="+ name +"' target='_blank'>Adopt this Tree!</a>";}
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>

  </script>

  </head>

  <body onload="load()">
    <div id="map" style="width: 1000px; height: 700px"></div>
  </body>

</html>