<?php

// Saxon Mailey 2015
// saxon at scm dot id dot au

session_start();

require 'db_mewsmisc.php';

?>
<!DOCTYPE HTML>
<html>
<body>
<?php

$validation = 1;

if(isset($_POST['chlorine']))   { $chlorine   = $_POST['chlorine']; }    else { $chlorine = ''; $validation = 0; }
if(isset($_POST['ph']))         { $ph         = $_POST['ph']; }          else { $ph = ''; $validation = 0; }
if(isset($_POST['stabiliser'])) { $stabiliser = $_POST['stabiliser']; }  else { $stabiliser = ''; $validation = 0; }
if(isset($_POST['alkalinity'])) { $alkalinity = $_POST['alkalinity']; }  else { $alkalinity = ''; $validation = 0; }
if(isset($_POST['salt']))       { $salt       = $_POST['salt']; }        else { $salt = 0; }

if(isset($_POST['latitude']))   { $latitude   = $_POST['latitude']; }    else { $latitude = 0; }
if(isset($_POST['longitude']))  { $longitude  = $_POST['longitude']; }   else { $longitude = 0; }
if($latitude == '') { $latitude = 0; }
if($longitude == '') { $longitude = 0; }

if ($validation == 1) {
	# DO DB UPDATE
	$IP=$_SERVER['REMOTE_ADDR'];
	$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
	mysql_select_db($DBNAME,$connection);
	$SQL = 'INSERT INTO pooltest (ip,latitude,longitude,chlorine,ph,stabiliser,alkalinity,salt) values (' . "'$IP',$latitude,$longitude,$chlorine,$ph,$stabiliser,$alkalinity,$salt)";
	echo '<!-- SQL: ' . $SQL . '-->' . "\n";
	$result = mysql_query($SQL,$connection) or die('cannot execute SQL');
	if ($result == 1) {
		echo "Record Updated\n<p>\n";
		echo "<table>\n";
		echo "<tr><td>Chlorine:</td><td>$chlorine</td></tr>\n";
		echo "<tr><td>pH:</td><td>$ph</td></tr>\n";
		echo "<tr><td>Stabiliser:</td><td>$stabiliser</td></tr>\n";
		echo "<tr><td>Alkalinity:</td><td>$alkalinity</td></tr>\n";
		echo "<tr><td>Salt:</td><td>$salt</td></tr>\n";
		echo "</table>\n";
		
	};

} else {
	echo '<!-- ' . "\n";
	echo "Chlorine: $chlorine\n";
	echo "pH: $ph\n";
	echo "Stabiliser: $stabiliser\n";
	echo "Alkalinity: $alkalinity\n";
	echo "Salt: $salt\n";
	echo '-->' . "\n";
?>
<form method=post>
<table>
	<tr>
		<td>Chlorine</td>
		<td><input type=text name=chlorine list=chlorine>
			<datalist id=chlorine>
<?php
for ($x = 0.8; $x <= 3.0; $x=$x+0.2) { echo "\t\t\t\t<option>" . number_format($x,1) . "</option>\n"; } 
?>
			</datalist>
		</td>
	</tr>
	<tr>
		<td>pH</td>
		<td><input type=text name=ph list=ph>
			<datalist id=ph>
<?php
for ($x = 6.2; $x <= 8.6; $x=$x+0.2) { echo "\t\t\t\t<option>" . number_format($x,1) . "</option>\n"; }
?>
			</datalist>
		</td>
	</tr>
	<tr>
		<td>Stabiliser</td>
		<td><input type=text name=stabiliser list=stabiliser>
			<datalist id=stabiliser>
<?php
for ($x = 10; $x <= 100; $x=$x+10) { echo "\t\t\t\t<option>$x</option>\n"; }
?>
			</datalist>
		</td>
	</tr>
	<tr>
		<td>Total Alkalinity</td>
		<td><input type=text name=alkalinity list=alkalinity>
			<datalist id=alkalinity>
<?php
for ($x = 80; $x <= 200; $x=$x+20) { echo "\t\t\t\t<option>$x</option>\n"; } 
?>
			</datalist>
		</td>
	</tr>
	<tr>
		<td>Salt</td>
		<td><input type=text name=salt list=salt>
			<datalist id=salt>
<?php
for ($x = 2000; $x <= 8000; $x=$x+100) { echo "\t\t\t\t<option>$x</option>\n"; } 
?>
			</datalist>
		</td>
	</tr>

</table>
<input type="submit" value="Submit">
<P>
<input type=hidden name=latitude id=latitude><br>
<input type=hidden name=longitude id=longitude>
</form>
<p id="mytext"></p>

<script type="text/javascript">
var mytext = document.getElementById('mytext');

window.onload = getLocation()

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, showError);
	} else { 
		mytext.innerHTML = "Geolocation is not supported by this browser.";
	}
}

function showPosition(position) {
	//mytext.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;	
	//var latitude=encodeURIComponent(position.coords.latitude);
	//var longitude=encodeURIComponent(position.coords.longitude);
	//var mylat=document.getElementById('latitude');
	//var mylong=document.getElementById('longitude');
	//mylat.value=encodeURIComponent(position.coords.latitude);
	//mylong.value=encodeURIComponent(position.coords.latitude);
	document.getElementById('latitude').value  = encodeURIComponent(position.coords.latitude);
	document.getElementById('longitude').value = encodeURIComponent(position.coords.longitude);
}

function showError(error) {
	switch(error.code) {
		case error.PERMISSION_DENIED:
			mytext.innerHTML = "User denied the request for Geolocation."
			break;
		case error.POSITION_UNAVAILABLE:
			mytext.innerHTML = "Location information is unavailable."
			break;
		case error.TIMEOUT:
			mytext.innerHTML = "The request to get user location timed out."
			break;
		case error.UNKNOWN_ERROR:
			mytext.innerHTML = "An unknown error occurred."
			break;
	}
}

</script>

<?php
}
?>
</body>
</html>

