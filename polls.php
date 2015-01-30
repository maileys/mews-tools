<?php

// Saxon Mailey 2015
// saxon at scm dot id dot au

date_default_timezone_set("Australia/Perth");

?>
<html>
<head>
<title>The Mews - Poll Results</title>
<meta name="HandheldFriendly" content="true" />
<link rel="stylesheet" href="/tools/default.css" type="text/css" />
</head>
<P>

<?php

require 'db_mews.php';

$SQL='SELECT t.poll_start, t.topic_id, t.topic_title, t.poll_title, u.username, o.poll_option_text, p.timestamp FROM phpbb_topics t, phpbb_poll_votes p, phpbb_users u, phpbb_poll_options o WHERE p.topic_id=t.topic_id AND p.vote_user_id=u.user_id AND p.poll_option_id=o.poll_option_id AND t.topic_id=o.topic_id ORDER BY t.topic_id DESC, p.timestamp DESC';

/* connect to the db */
$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
mysql_select_db($DBNAME,$connection);

/* get data */
$result = mysql_query($SQL,$connection) or die('cannot execute SQL');

echo '<table cellpadding="0" cellspacing="0" class="data-table">';
echo '<tr><th>Date</th><th>Topic Name</th><th>Poll Title</th><th>User</th><th>Vote</th><th>Vote Date</th></tr>';
while($row = mysql_fetch_row($result)) {
	echo '<tr>';
	$pos=1;
	foreach($row as $key=>$value){
		switch($pos) {
			case 1:
				echo '<td>',gmdate("Y-m-d H:i:s", $value),'</td>';
			case 2:
				$url='http://themewswestperth.com.au/bb/viewtopic.php?t=' . $value;
				break;
			case 3:
				echo '<td><a href="',$url,'">',$value,'</a></td>';
				break;
			default:
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


