<?php

// This function is used to generate a random UUID.
// Source: http://stackoverflow.com/a/2040279
function generateUUID() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

// Set PHP time zone for future use of date() function.
date_default_timezone_set('Australia/Adelaide');

// Filename
$filename = 'ARD List ' . date("d-m-y");

/* generateARDList requires:
 * A string used for the filename of the ARD list.
 * An array of devices, with each device containing a name, a networkAddress (IP address) and optionally a hardwareAddress (mac address).
 *  Example: $devices = array(array('name'=>'Mac-A','networkAddress'=>'192.168.1.10'),array('name'=>'Mac-B','networkAddress'=>'192.168.1.11','hardwareAddress'=>'0c:4d:e9:c8:50:a0'));
 * NOTE: Once ARD connects to a Mac the Mac's actual hostname is always displayed.
 */
 
function generateARDList($filename, $devices) {
			
	// Plist header.
	$header = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>items</key>
	<array>' . "\n";
	
	$body = '';
						
	foreach($devices as $device) {
		// Check required attributes are set.
		if (isset($device['name']) && isset($device['networkAddress'])) {
			
			$body .= '		<dict>' . "\n";
			
			// If set include device mac address.
			if (isset($device['hardwareAddress'])) {
				$body .= '			<key>hardwareAddress</key>
			<string>' . $device['hardwareAddress'] . '</string>' . "\n";
			}
			
			$body .= '			<key>name</key>
			<string>' . $device['name'] . '</string>
			<key>networkAddress</key>
			<string>' . $device['networkAddress'] . '</string>
			<key>networkPort</key>
			<integer>3283</integer>
			<key>vncPort</key>
			<integer>5900</integer>
		</dict>' . "\n";
		
		}
	
	}
	
	// Plist footer.
	$footer = 	'	</array>
	<key>listName</key>
	<string>' . $filename . '</string>
	<key>uuid</key>
	<string>' . generateUUID() . '</string>
</dict>
</plist>';
	
	// Put it all together.
	$data = $header . $body . $footer;
	
	// Set filename and trigger browser download.
	header('Content-Type: application/plist');
	header('Content-Disposition: attachment; filename="' . $filename . '.plist"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	echo $data;
	
}

// Call the function.
generateARDList($filename, $devices);

?>
