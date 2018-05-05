<?php

	// This program is free software; you can redistribute it and/or modify it under
	// the terms of the GNU General Public License as published by the Free Software
	// Foundation; either version 2 of the License, or any later version under the
	// following condition: Comment below must stay intact!

	/**********************************************************************************

								MP3 ID3 data extraction class

						 	  By Nick Swardh | www.nswardh.com

	**********************************************************************************/

class Mp3Tag {	


	// Class members
	private $handle;					// Used for the filestream.
	private $tag_count;					// Holds number of predefined ID3-tags.
	private $tag = array();				// Used for storing all Id3 tags.

	// Predifined array of ID3-tags.
	private $idv_tag = array(
		'AENC' => 'encryption', 		// Audio encryption
		'APIC' => 'picture',			// Attached picture
		'ASPI' => 'audio_seek',			// Audio seek point index
		'COMM' => 'comments',			// Comments
		'COMR' => 'com_frame',			// Commercial frame
		'ENCR' => 'enc_method',			// Encryption method registration
		'EQU2' => 'equalization',		// Equalisation (2)
		'ETCO' => 'timing_code',		// Event timing codes
		'GEOB' => 'gen_encap_object',	// General encapsulated object
		'GRID' => 'group_id',			// Group identification registration
		'LINK' => 'linked_info',		// Linked information
		'MCDI' => 'cd_id',				// Music CD identifier
		'MLLT' => 'mpeg_lookup_table',	// MPEG location lookup table
		'OWNE' => 'owner_frame',		// Ownership frame
		'PRIV' => 'private_frame',		// Private frame
		'PCNT' => 'play_counter',		// Play counter
		'POPM' => 'popularimeter',		// Popularimeter
		'POSS' => 'pos_sync_frame',		// Position synchronisation frame
		'RBUF' => 'recom_buffer_size',	// Recommended buffer size
		'RVA2' => 'rel_vol_adj',		// Relative volume adjustment (2)
		'RVRB' => 'reverb',				// Reverb
		'SEEK' => 'seek_frame',			// Seek frame
		'SIGN' => 'sign_frame',			// Signature frame
		'SYLT' => 'sync_lyric',			// Synchronised lyric/text
		'SYTC' => 'sync_tempo_code',	// Synchronised tempo codes
		'TALB' => 'album_title',		// Album/Movie/Show title
		'TBPM' => 'bpm',				// BPM (beats per minute)
		'TCOM' => 'composer',			// Composer
		'TCON' => 'content_type',		// Content type
		'TCOP' => 'copyright',			// Copyright message
		'TDEN' => 'enc_time',			// Encoding time
		'TDOR' => 'org_release',		// Original release time
		'TDRC' => 'rec_time',			// Recording time
		'TDRL' => 'rel_time',			// Release time
		'TDTG' => 'tag_time',			// Tagging time
		'TENC' => 'encoded_by',			// Encoded by
		'TEXT' => 'lyrics_by',			// Lyricist/Text writer
		'TFLT' => 'tile_type',			// File type
		'TIPL' => 'involved_people',	// Involved people list
		'TIT1' => 'cont_grp_desc',		// Content group description
		'TIT2' => 'song_title',			// Title/songname/content description
		'TIT3' => 'subtitle',			// Subtitle/Description refinement
		'TKEY' => 'init_key',			// Initial key
		'TLAN' => 'language',			// Language(s)
		'TLEN' => 'length',				// Length
		'TMCL' => 'credits',			// Musician credits list
		'TMED' => 'media_type',			// Media type
		'TMOO' => 'mood',				// Mood
		'TOAL' => 'org_title',			// Original album/movie/show title
		'TOFN' => 'org_filename',		// Original filename
		'TOLY' => 'org_lyric_writer',	// Original lyricist(s)/text writer(s)
		'TOPE' => 'org_artist',			// Original artist(s)/performer(s)
		'TOWN' => 'file_owner',			// File owner/licensee
		'TPE1' => 'artist',				// Lead performer(s)/Soloist(s)
		'TPE2' => 'band',				// Band/orchestra/accompaniment
		'TPE3' => 'performer',			// Conductor/performer refinement
		'TPE4' => 'remixed',			// Interpreted, remixed, or otherwise modified by
		'TPOS' => 'part_of_set',		// Part of a set
		'TPRO' => 'prod_notice',		// Produced notice
		'TPUB' => 'publisher',			// Publisher
		'TRCK' => 'track_nr',			// Track number/Position in set
		'TRSN' => 'net_radio',			// Internet radio station name
		'TRSO' => 'net_radio_owner',	// Internet radio station owner
		'TSOA' => 'album_order',		// Album sort order
		'TSOP' => 'performer_order',	// Performer sort order
		'TSOT' => 'title_order',		// Title sort order
		'TSRC' => 'isrc',				// ISRC (international standard recording code)
		'TSSE' => 'encode_setup',		// Software/Hardware and settings used for encoding
		'TSST' => 'set_subtitle',		// Set subtitle
		'TXXX' => 'user_defined_text',	// User defined text information frame
		'TYER' => 'year',				// Year
		'UFID' => 'unique_file_id',		// Unique file identifier
		'USER' => 'terms',				// Terms of use
		'USLT' => 'unsync_lyric',		// Unsynchronised lyric/text transcription
		'WCOM' => 'com_info',			// Commercial information
		'WCOP' => 'copyright_info',		// Copyright/Legal information
		'WOAF' => 'official_webpage',	// Official audio file webpage
		'WOAR' => 'official_artist',	// Official artist/performer webpage
		'WOAS' => 'official_source',	// Official audio source webpage
		'WORS' => 'official_radio',		// Official Internet radio station homepage
		'WPAY' => 'payment', 			// Payment
		'WPUB' => 'publiser_webpage',	// Publishers official webpage
		'WXXX' => 'user_url'			//User defined URL link frame
		);

	// Predefined array of album art/picture types.
	private $pic_type = array(
		'00' => 'Other',    			// Other
		'01' => 'png_icon',      		// 32x32 pixels 'file icon' (PNG only)
		'02' => 'icon',      			// Other file icon
		'03' => 'front',      			// Cover (front)
		'04' => 'back',      			// Cover (back)
		'05' => 'leaflet',      		// Leaflet page
		'06' => 'media',      			// Media (e.g. lable side of CD)
		'07' => 'lead_artist',      	// Lead artist/lead performer/soloist
		'08' => 'artist',      			// Artist/performer
		'09' => 'conductor',      		// Conductor
		'0A' => 'band',      			// Band/Orchestra
		'0B' => 'composer',      		// Composer
		'0C' => 'text_writer',      	// Lyricist/text writer
		'0D' => 'location',      		// Recording Location
		'0E' => 'during_recording', 	// During recording
		'0F' => 'during_performance',	// During performance
		'10' => 'video_capture',    	// Movie/video screen capture
		'11' => 'bright',      			// A bright colored fish (OGG Vorbis, opensource audio format).
		'12' => 'illustration',     	// Illustration
		'13' => 'logotype',      		// Band/artist logotype
		'14' => 'studio_logotype'   	// Publisher/Studio logotype
		);




	// Constructor.
	function __construct() {

		// Count number of predefined ID3-tags upon object instantiation of the class.
		// Just to make things a bit more dynamic.
		$this->tag_count = count($this->idv_tag);

	}




	// Get the MP3 audio header
	public function Get($file) {
		
		// Open file in binary mode.
		$this->handle 	= fopen($file, 'rb');

		// Read and Unpack the first 10 bytes. Split into an array.
		$header	 		= unpack("a3signature/c1version/c1revision/c1flag/Nsize", fread($this->handle, 10));

		if ($header['signature'] == 'ID3') {

			$this->tag['header'] = $header;		// Store headers.
			$this->Read();						// Extract all ID3-tags.

		}

		// Close filestream.
        fclose($this->handle);

        // Return array.
        return $this->tag;

	}




	// Get ID3-tag
	private function Read() {

		// Search Id3 tags.
		for ($i = 0; $i < $this->tag_count; $i++) {

			// Read 10 bytes. First 4: tagname, next 4: size, last 2: flags.
			list('t' => $tag, 's' => $size, 'f' => $flag) = unpack('a4t/Ns/a2f', fread($this->handle, 10));

			// Does $tag exist?
			if (!$this->idv_tag[$tag])
				break;

			// Read tag-data based on $size (number of bytes).
			$data = $this->TagData($tag, fread($this->handle, $size));

			// If the returned $data is an array, add new sub-index.
			if (is_array($data))
				$this->tag['tag'][$this->idv_tag[$tag]][strtolower($data[0])] = $data[1];
			else
				$this->tag['tag'][$this->idv_tag[$tag]] = $data;

		}

	}




	// Process ID3 tag-data.
	private function TagData($tag, $data) {

		// If data contains album art/pictures, call Image() for extraction.
		if ($tag == 'APIC')
			return $this->Image($data);

		// Call Encode() to convert data to UTF-8.
		$data = $this->Encode($tag, $data);

		// Return $data.
		return ($tag == 'TXXX') ? explode("\x00", $data) : $data;

	}




	// Encode string to UTF-8.
	private function Encode($tag, $str) {

		// Get encoding type
		$enc = $this->GetEncType($str);

		// Remove 'bad' bytes.
		$str =  $this->CleanBytes($tag, $str);

		// Initialize an array with ordered encoding types.
		$iso = array('ISO-8859-1', 'UTF-16LE', 'UTF-16BE', 'UTF-8');

		// Encode string to UTF-8.
		return trim(iconv($iso[$enc], 'UTF-8', $str));

	}




	// Extract album art/pictures
	private function Image($image) {

		// Mime image type?
		$mime_type = substr(ltrim($image), 6, 3);

		// Set MIME-type and offset.
		if (strtolower($mime_type) == 'png') {

			$mime_type 	= 'png';	// MIME-type
			$offset		= 13;		// offset.

		} else {

			$mime_type 	= 'jpeg';	// MIME-type.
			$offset		= 14;		// offset.

		}

		// Get image data.
		$image_data		= substr($image, $offset);			// Image data (binary).
		$data['mime']	= 'image/' . $mime_type;			// MIME-type.
		$data['size']	= strlen($image_data);				// Image size.
		$data['data']	= base64_encode($image_data);		// Encode the binary string.

		// Get the hexadecimal valude of the album art type. Front picture, back, logo etc.
		$pic = substr($image, $offset-2, 1);

		// Return an array where the index is the name of the art-type and value the image-$data array. 
		return array($this->pic_type[bin2hex($pic)], $data);

	}




	// Get encoding type.
	private function GetEncType($str) {

		// Get the text-encoding description byte.
		// 0 = ISO-8859-1
		// 1 = UTF-16LE		little endian with BOM
		// 2 = UTF-16BE		big endian without BOM
		// 3 = UTF-8

		// Read the first byte and return its numeric encoding representation.
		return ord($str[0]);

	}




	// Remove the leading 'bad' bytes from string.
	private function CleanBytes($tag, $str) {

		// Get encoding type.
		$enc = $this->GetEncType($str);

		// 'COMM' tag (comment) needs to be treated differently
		// due to the leading 3-char language code.
		if ($tag == 'COMM') {

			switch ($enc) {

				case 0 :
				case 3 :
					$offset = 5;
					break;

				case 1 :
				case 2 :
					$offset = 10;
			}

		} else {

			switch ($enc) {

				case 0 :
				case 3 :
					$offset = 1;
					break;

				case 1 :
				case 2 :
					$offset = 3;
					break;
			}

		}

		// Remove bytes according to the $offset.
		return substr($str, $offset);

	}


	
	
}
