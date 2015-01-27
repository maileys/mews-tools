<?php

session_name("themews");
session_start();

require 'db_mewsmisc.php';

if (isset($_GET['geolocation'])) {
	$latitude  = $_GET['latitude'];
	$longitude = $_GET['longitude'];
	$_SESSION['latitude']  = $latitude;
	$_SESSION['longitude'] = $longitude;
	header("latitude: ${latitude}");
	header("longitude: ${longitude}");
	$_SESSION['geolocation']  = 'set';

	# DO DB UPDATE
	$IP=$_SERVER['REMOTE_ADDR'];
	$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
	mysql_select_db($DBNAME,$connection);
	$SQL = 'INSERT INTO location (ip,latitude,longitude) values (' . "'$IP',$latitude,$longitude)";
	$result = mysql_query($SQL,$connection) or die('cannot execute SQL');

} else {

?>
<!DOCTYPE html>


<html>
<body>
<?php

	if (isset($_SESSION['geolocation'])) {
		echo 'Cached Location Data:' . "<br>\n";
		echo 'Latitude: '  . $_SESSION['latitude']  . "<br>\n";
		echo 'Longitude: ' . $_SESSION['longitude'] . "<br>\n";
		echo '<A HREF="http://google.com/maps/place/' . $_SESSION['latitude'] . ',' . $_SESSION['longitude'] . '">Google Maps</A>' . "\n";
	} else {


?>
<p id="demo"></p>
<script>
var x = document.getElementById("demo");

window.onload = getLocation()

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, showError);
	} else { 
		x.innerHTML = "Geolocation is not supported by this browser.";
	}
}

function showPosition(position) {
	x.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;	
	var latitude=encodeURIComponent(position.coords.latitude)
	var longitude=encodeURIComponent(position.coords.longitude)
	mygetrequest.open("GET", "?geolocation=true&latitude="+latitude+"&longitude="+longitude, true)
	mygetrequest.send(null)
	x.innerHTML = "Detected Location Data:<br>\n" 
		+ 'Latitude:'  + position.coords.latitude + "<br>\n"
		+ 'Longitude:' + position.coords.latitude + "<br>\n"
		+ '<A HREF="http://google.com/maps/place/' + position.coords.latitude + ',' +position.coords.longitude + "\">Google Maps</A>"; 
}

function showError(error) {
	switch(error.code) {
		case error.PERMISSION_DENIED:
			x.innerHTML = "User denied the request for Geolocation."
			break;
		case error.POSITION_UNAVAILABLE:
			x.innerHTML = "Location information is unavailable."
			break;
		case error.TIMEOUT:
			x.innerHTML = "The request to get user location timed out."
			break;
		case error.UNKNOWN_ERROR:
			x.innerHTML = "An unknown error occurred."
			break;
	}
}

function ajaxRequest(){
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
	if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
		for (var i=0; i<activexmodes.length; i++){
			try{
				return new ActiveXObject(activexmodes[i])
			}
			catch(e){
			//suppress error
			}
		}
	} else {
		if (window.XMLHttpRequest) { // if Mozilla, Safari etc
			return new XMLHttpRequest()
		} else {
			return false
		}
	}
}

var mygetrequest=new ajaxRequest()
mygetrequest.onreadystatechange=function(){
	if (mygetrequest.readyState==4){
		if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
			document.getElementById("result").innerHTML=mygetrequest.responseText
		} else {
			alert("An error has occured making the request")
		}
	}
}


</script>

</body>
</html>

<?php
	}
}

