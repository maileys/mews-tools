<?php

// Saxon Mailey 2015
// saxon at scm dot id dot au

session_start();

require 'db_mewsmisc.php';

if (isset($_GET['geolocation'])) {
	$latitude         = $_GET['latitude'];
	$longitude        = $_GET['longitude'];
	$accuracy         = $_GET['accuracy'];
	$altitude         = $_GET['altitude'];
	$altitudeaccuracy = $_GET['altitudeaccuracy'];
	$heading          = $_GET['heading'];
	$speed            = $_GET['speed'];
	$_SESSION['geolatitude']  = $latitude;
	$_SESSION['geolongitude'] = $longitude;
	$_SESSION['geoaccuracy'] = $accuracy;
	header("latitude: ${latitude}");
	header("longitude: ${longitude}");
	$_SESSION['geolocation']  = 'set';

	# DO DB UPDATE
	$IP=$_SERVER['REMOTE_ADDR'];
	$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
	mysql_select_db($DBNAME,$connection);
	$fieldlist = 'ip,latitude,longitude,accuracy';
	$valuelist = "'$IP',$latitude,$longitude,$accuracy";
	if (is_numeric($altitude)) { 
		$fieldlist = $fieldlist . ',altitude';
		$valuelist = $valuelist . ",$altitude";
	}
	if (is_numeric($altitudeaccuracy)) { 
		$fieldlist = $fieldlist . ',altitudeaccuracy';
		$valuelist = $valuelist . ",$altitudeaccuracy";
	}
	if (is_numeric($heading)) { 
		$fieldlist = $fieldlist . ',heading';
		$valuelist = $valuelist . ",$heading";
	}
	if (is_numeric($speed)) { 
		$fieldlist = $fieldlist . ',speed';
		$valuelist = $valuelist . ",$speed";
	}
	$SQL = 'INSERT INTO location (' . $fieldlist . ') values (' . $valuelist . ')';
	$result = mysql_query($SQL,$connection) or die("cannot execute SQL: $SQL");

} else {

?>
<!DOCTYPE html>


<html>
<head>
	<meta name="HandheldFriendly" content="true" />
</head>
<body>
<?php

	if (isset($_SESSION['geolocation'])) {
		echo 'Cached Location Data:' . "<br>\n";
		echo 'Latitude: '  . $_SESSION['geolatitude']  . "<br>\n";
		echo 'Longitude: ' . $_SESSION['geolongitude'] . "<br>\n";
		echo 'Accuracy: ' . $_SESSION['geoaccuracy'] . "<br>\n";
		echo '<A HREF="http://google.com/maps/place/' . $_SESSION['geolatitude'] . ',' . $_SESSION['geolongitude'] . '">Google Maps</A>' . "\n";
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
	var latitude=encodeURIComponent(position.coords.latitude);
	var longitude=encodeURIComponent(position.coords.longitude);
	var accuracy=encodeURIComponent(position.coords.accuracy);
	var altitude=encodeURIComponent(position.coords.altitude);
	var altitudeaccuracy=encodeURIComponent(position.coords.altitudeaccuracy);
	var heading=encodeURIComponent(position.coords.heading);
	var speed=encodeURIComponent(position.coords.speed);
	mygetrequest.open("GET", "?geolocation=true&latitude="+latitude+"&longitude="+longitude+"&accuracy="+accuracy+"&altitude="+altitude+"&altitudeaccuracy="+altitudeaccuracy+"&heading="+heading+"&speed="+speed, true)
	mygetrequest.send(null)
	x.innerHTML = "Detected Location Data:<br>\n" 
		+ 'Latitude:'  + position.coords.latitude + "<br>\n"
		+ 'Longitude:' + position.coords.latitude + "<br>\n"
		+ 'Accuracy:'  + position.coords.accuracy + "<br>\n"
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

