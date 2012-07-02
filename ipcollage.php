<?php
//DATABASE STUFF, USE YOUR OWN, SCHEMA BELOW
$server = "";
$user = "";
$pass = "";

$db = mysql_connect($server, $user, $pass);

if (!$db) {
    echo "oh noes, something's wrong with the database!";
    //for now, later pop a graphic
    exit;
}

mysql_select_db("");

//on first load, insert the ip
if ($_GET['time'] == 1) {
    insertIP();
}

//return visits since last known shape
if ($_GET['time']) {
    
    $timestamp = intval($_GET['time']);
    if (!is_int($timestamp)) {
        echo "wtf " .$timestamp;
        $timestamp = 0;
    }
    //$timestamp = gmdate("m-d-Y H:i:s", $timestamp);
    
    $visits = array();
    $visits['visits'] = array();
    
    $query = "SELECT `ip`, `timestamp` from `ipcollage` WHERE `timestamp` >= $timestamp ORDER BY `timestamp` ASC";
    
    $result = mysql_query($query);
    $num_results = mysql_num_rows($result);
    $returnarray = array();
    while ($row = mysql_fetch_array($result)) {
        $visit = array();
        $visit['ip'] =  $row["ip"];
        $visit['timestamp'] = strtotime($row["timestamp"]);
        if (strtotime($row['timestamp']) > $timestamp) {
            $visits["visits"][]= $visit;
        }
        
    }
    echo json_encode($visits);
}


function insertIP()
{
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "insert into ipcollage (ip) values ('$ip')";
    $result = mysql_query($query);
    if (!$result) {
        return 0;
    } 
    
}

/* here's the database schema
CREATE TABLE IF NOT EXISTS `ipcollage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5400 ;
*/



?>