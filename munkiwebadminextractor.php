<?php

# Pulls devices from the MunkiWebAdmin database
function getDevices() {

	class MyDB extends SQLite3 {
		function __construct() {
			$this->open('munkiwebadmin.db');
		}
	}
	
	$db = new MyDB();
	
	if (!$db) {
		echo $db->lastErrorMsg();
		exit(1);
	}
	
	$sql =<<<EOF
SELECT mac AS hardwareAddress, hostname AS name, remote_ip AS networkAddress FROM reports_machine;
EOF;
	
	$ret = $db->query($sql);
	
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
		$devices[] = array("hardwareAddress"=>$row['hardwareAddress'],"name"=>$row['name'],"networkAddress"=>$row['networkAddress']);
	}
	
	$db->close();
	
	return $devices;

}

$devices = getDevices();

if (isset($devices)) {
	include('ardgen.php');
}

?>