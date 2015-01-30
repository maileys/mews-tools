<?php

// Saxon Mailey 2014
// saxon at scm dor id dot au

date_default_timezone_set("Australia/Perth");

$DEFURL='/helpdesk/index.php?a=add';
$DEFTITLE='/helpdesk/index.php?a=add';

require 'db_mewsmisc.php';

$connection = mysql_connect($DBHOST,$DBUSER,$DBPASS);
mysql_select_db($DBNAME,$connection);


if(isset($_GET['id'])){
	$qr_id = preg_replace("/[^A-Za-z0-9]/", '', $_GET['id']);
	$qr_id = escapeshellcmd($qr_id);

	$SQL='SELECT title, url FROM qr WHERE id=' . $qr_id;
	#echo $SQL;
	$result = mysql_query($SQL,$connection) or die('cannot execute SQL SELECT');

	$row = mysql_fetch_row($result);
        $pos=1;
        foreach($row as $key=>$value){
                switch($pos) {
                        case 1:
                                $title=$value;
                                break;
                        case 2:
                                $url=$value;
                                break;
                        default:
                }
                $pos++;
	}
	if($url=='') { $url = $DEFURL; }
} else {
	if($qr_id=='') { $qr_id = 0; }
	$title = $DEFTITLE;
	$url = $DEFURL;
}


echo '<!DOCTYPE HTML>' . "\n";
echo '<html lang="en-US">' . "\n";
echo '<head>' . "\n";
echo "\t" . '<meta charset="UTF-8">' . "\n";
echo "\t" . '<meta name="HandheldFriendly" content="true" />' . "\n";
echo "\t" . '<meta http-equiv="refresh" content="1;url=' . $url . '">' . "\n";
echo "\t" . '<script type="text/javascript">' . "\n";
echo "\t\t" . 'window.location.href = "' . $url . '"' . "\n";
echo "\t" . '</script>' . "\n";
echo "\t" . '<title>' . $title . '</title>' . "\n";
echo '</head>' . "\n";
echo '<body>' . "\n";
echo "\t" . '<P> &nbsp; <P>If you are not redirected automatically, follow the <a href=' . $url . '>' . $url . '</a>' . "\n";
echo '</body>' . "\n";
echo '</html>' . "\n";

if(isset($_SERVER['REMOTE_ADDR']))     { $ip    = $_SERVER['REMOTE_ADDR']; }     else { $ip = ''; }
if(isset($_SERVER['HTTP_REFERER']))    { $ref   = $_SERVER['HTTP_REFERER']; }    else { $ref = ''; }
if(isset($_SERVER['HTTP_USER_AGENT'])) { $agent = $_SERVER['HTTP_USER_AGENT']; } else { $agent = ''; }

$SQL = 'INSERT INTO qrlog (qrid, ip, referer, agent) VALUES (' . $qr_id . ",'" . $ip . "','" . $ref . "','" . $agent . "')";
echo $SQL;
mysql_query($SQL,$connection) or die('cannot execute SQL UPDATE');

