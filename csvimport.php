<?php

/**
 * Convert a comma separated file into an associated array.
 * The first row should contain the array keys.
 * 
 * Example:
 * 
 * @param string $filename Path to the CSV file
 * @param string $delimiter The separator used in the file
 * @return array
 * @link http://gist.github.com/385876
 * @author Jay Williams <http://myd3.com/>
 * @copyright Copyright (c) 2010, Jay Williams
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}


if (!$_FILES["file"]["error"] > 0 && $_FILES["file"]["type"] == 'text/csv') {
	
	$devices = csv_to_array($_FILES["file"]["tmp_name"]);
		
	if (isset($devices)) {
		include('ardgen.php');
	}

} else {
	
	echo '<html>
	<body>
		<h1>CSV to ARD Import List</h1>
		<p>Download an example/template CSV <a href="examplecsv.zip">here</a>.
				
		<ul>
			<li>Only the name and networkAddress (IP address) are required fields, hardwareAddress (MAC address) is optional.</li>
		</ul>
		
		<p>Please select a CSV file you wish to convert.</p>
		<form action="" method="post" enctype="multipart/form-data">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Convert">
		</form>
	</body>
</html>';

}

?>