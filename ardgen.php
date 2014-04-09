<?php

/*** Start of example use ***/

// Set PHP time zone for date() function
date_default_timezone_set('Australia/Adelaide');

// Example filename
$filename = 'ARD List ' . date("d-m-y");

// Example device array
$devices = array(array('hostname'=>'Mac-A','ip'=>'192.168.1.10'),array('hostname'=>'Mac-B','ip'=>'192.168.1.11'),array('hostname'=>'Mac-C','ip'=>'192.168.1.12'));

// Call the function
generateARDList($filename, $devices);

/*** End of example use ***/


/* generateARDList requires:
 * A string used for the filename of the ARD list.
 *
 * An array of devices, with each device containing a hostname and an IP address.
 *
 * NOTE: Once ARD connects to a Mac the Mac's actual hostname is always displayed.
 */
 
function generateARDList($filename, $devices) {
			
	// ARD file header
	$header = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
			  '<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">' . "\n" .
			  '<plist version="1.0">' . "\n" .
			  '<dict>' . "\n" .
			  	'<key>items</key>' . "\n" .
			  	'<array>' . "\n";
	
	$body = '';
						
	// Device entires
	foreach($devices as $device) {
		
		$name = $device['hostname'];
		$ip = $device['ip'];
		
		if (!($name == '' || $ip == '')) {
		
			$body .= '<dict>' . "\n" .
						'<key>hostname</key>' . '<string>' . $name . '.local.</string>' . "\n" .
						'<key>name</key><string>' . $name . '</string>' . "\n" .
						'<key>networkAddress</key><string>' . $ip . '</string>' . "\n" .
						'<key>networkPort</key><integer>3283</integer>' . "\n" .
						'<key>preferHostname</key><false/>' . "\n" .
						'<key>vncPort</key><integer>5900</integer>' . "\n" .
					 '</dict>' . "\n";
		
		}
	
	}
	
	// ARD file footer
	$footer = 	'</array>' . "\n" .
			  	'<key>listName</key><string>' . $filename . '</string>' . "\n" .
			  	'<key>uuid</key><string>7E6A9DD6-122F-4C84-96AA-DB4FDAF66B3D</string>' . "\n" .
			  '</dict>' . "\n" .
			  '</plist>';

	$data = $header . $body . $footer;
	
	// Set download filename and trigger browser download
	header('Content-Type: application/plist');
	header('Content-Disposition: attachment; filename="' . $filename . '.plist"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	echo $data;
	
}
	
?>
