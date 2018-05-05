# MP3 ID3 Meta Data (ID3v2.3) Extraction - PHP (OOP)
Class for reading and extracting ID3 meta data from MP3 files. It also extracts all album art/pictures. A demo of the class can be viewed here: http://www.nswardh.com/demo/mp3/

# Returned data
The object returns an array containing whatever data that was found. Ex: http://www.nswardh.com/demo/mp3/array.php

# How to use
require 'mp3data.php';			// Load class.
$mp3  = new Mp3Tag();			// Instantiate.
$data = $mp3->Get('some_audio.mp3');	// Get array data.

print_r($data);				// Data.

# Extracted album art/pictures
The extracted binary string(s) are base64_encoded. To display the images you simply do:

foreach ($data['tag']['picture'] as $image) {

	echo '<img src="data:' . $image['mime'] . ';charset=utf-8;base64,' . $image['data'] . '" />';

}
