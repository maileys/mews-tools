<?php

// Saxon Mailey 2005
// Australian Information Technologies
// support@auit.com.au

date_default_timezone_set("Australia/Perth");


?>
<html>
<head>
<title>The Mews - User Last Visit Times</title>
<link rel="stylesheet" href="/tools/default.css" type="text/css" />
</head>
<body>
<P>

<?php

require 'db_mews.php';

if(isset($_GET['all'])){
    $displayall = 1;
} else {
    $displayall = 0;
}

print "<FONT SIZE=\"-1\">" . date("d/m/Y H:i:s") . "</FONT><BR>\n";

$SQL='SELECT username, FROM_UNIXTIME(user_lastvisit), FROM_UNIXTIME(user_lastvisit_custom) FROM phpbb_users  WHERE user_lastvisit_custom > 0 ORDER BY user_lastvisit DESC';
if ($displayall == 1) {
    $SQL='SELECT username, FROM_UNIXTIME(user_lastvisit), FROM_UNIXTIME(user_lastvisit_custom) FROM phpbb_users ORDER BY user_lastvisit DESC';
}

/* connect to the db */
$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
mysql_select_db($DBNAME,$connection);

/* get data */
$result = mysql_query($SQL,$connection) or die('cannot execute SQL');

echo '<table cellpadding="0" cellspacing="0" class="data-table">';
echo '<tr><th>Username</th><th>Last Visit</th><th>Last Visit (custom)</th></tr>';
while($row = mysql_fetch_row($result)) {
	echo '<tr>';
	$pos=1;
	foreach($row as $key=>$value){
		if($pos == 1){
			echo '<TD><A HREF=userlog.php?user=',$value,'>',$value,'</A></TD>';
		} else {
			echo '<td>',$value,'</td>';
		}
		$pos++;
	}
	echo "</tr>\n";
}
echo '</table><br />';
	
?>
</body>
</html>


