<?php
$link = mysql_connect('localhost', 'demo', 'demo');
if (!$link) { die('Could not connect: ' . mysql_error());}
mysql_select_db('bbmaps', $link) or die ("Wrong MySQL Database");

// SETTINGS
$server = '{mail.gunungpantara.com/pop3/novalidate-cert}INBOX';
$username   = 'maps@gunungpantara.com';
$password   = 'guntara2013';
$export_dir = '../images/uploads/'; # final slash is required
// END SETTINGS

function getLatitude($string)
{
	$coordinate = strstr($string, 'lat=');
	$coordinate = strstr($coordinate, '&', true);
	$coordinate = substr($coordinate, 4);
	return $coordinate;
}

function getLongitude($string)
{
	$coordinate = strstr($string, 'lon=');
	$coordinate = strstr($coordinate, '&', true);
	$coordinate = substr($coordinate, 4);
	return $coordinate;
}

function getExtension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

function getSubjMaps($string)
{
	$hasil = strstr($string, 'lokasi');
	return $hasil;
}

function getSubjLaporan($string)
{
	$hasil = strstr($string, 'laporan');
	return $hasil;
}

//---------------- START BODY HERE ----------------\\

function getBody($uid, $imap) {
	$body = get_part($imap, $uid, "TEXT/HTML");
	// if HTML body is empty, try getting text body
	if ($body == "") { $body = get_part($imap, $uid, "TEXT/PLAIN"); }
	return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
	if (!$structure) { $structure = imap_fetchstructure($imap, $uid, FT_UID); }
	if ($structure) {
		if ($mimetype == get_mime_type($structure)) {
			if (!$partNumber) { $partNumber = 1; }
			$text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
			switch ($structure->encoding) {
				case 3: return imap_base64($text);
				case 4: return imap_qprint($text);
				default: return $text;
			}
		}

		// multipart 
		if ($structure->type == 1) {
			foreach ($structure->parts as $index => $subStruct) {
				$prefix = "";
				if ($partNumber) { $prefix = $partNumber . "."; }
				$data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
				if ($data) { return $data; }
			}
		}
	}
	return false;
}

function get_mime_type($structure) {
	$primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

	if ($structure->subtype) { return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype; }
	return "TEXT/PLAIN";
}

//---------------- END BODY HERE ----------------\\


function get_attachments($content, $part = null, $skip_parts = false) {
	static $results;
 
	// First round, emptying results
	if (is_null($part)) {
		$results = array();
	}
	else {
		// Removing first dot (.)
		if (substr($part, 0, 1) == '.') {
			$part = substr($part, 1);
		}
	}
 
	// Saving the current part
	$actualpart = $part;
	// Split on the "."
	$split = explode('.', $actualpart);
 
	// Skipping parts
	if (is_array($skip_parts)) {
		foreach ($skip_parts as $p) {
			// Removing a row off the array
			array_splice($split, $p, 1);
		}
		// Rebuilding part string
		$actualpart = implode('.', $split);
	}
 
	// Each time we get the RFC822 subtype, we skip this part.
	if (strtolower($content->subtype) == 'rfc822') {
		// Never used before, initializing
		if (!is_array($skip_parts)) {
			$skip_parts = array();
		}
		// Adding this part into the skip list
		array_push($skip_parts, count($split));
	}
 
	// Checking ifdparameters
	if (isset($content->ifdparameters) && $content->ifdparameters == 1 && isset($content->dparameters) && is_array($content->dparameters)) {
		foreach ($content->dparameters as $object) {
			if (isset($object->attribute) && preg_match('~filename~i', $object->attribute)) {
				$results[] = array(
				'type'  => (isset($content->subtype)) ? $content->subtype : '',
				'encoding'  => $content->encoding,
				'part'  => empty($actualpart) ? 1 : $actualpart,
				'filename'  => $object->value
				);
			}
		}
	}

	// Checking ifparameters
	else if (isset($content->ifparameters) && $content->ifparameters == 1 && isset($content->parameters) && is_array($content->parameters)) {
		foreach ($content->parameters as $object) {
			if (isset($object->attribute) && preg_match('~name~i', $object->attribute)) {
				$results[] = array(
				'type'  => (isset($content->subtype)) ? $content->subtype : '',
				'encoding'  => $content->encoding,
				'part'  => empty($actualpart) ? 1 : $actualpart,
				'filename'  => $object->value
				);
			}
		}
	}

	// Recursivity
	if (isset($content->parts) && count($content->parts) > 0) {
	// Other parts into content
		foreach ($content->parts as $key => $parts) {
			get_attachments($parts, ($part.'.'.($key + 1)), $skip_parts);
		}
	}
	return $results;
}

//---------------- START PROCESS HERE ----------------\\

$imap = imap_open($server, $username, $password);
$message_count = imap_num_msg($imap);
//if ($message_count < 1) {
	for ($msgid = 1; $msgid <= $message_count; ++$msgid) {
		$header = imap_header($imap, $msgid); 
		$overview = imap_fetch_overview($imap,$msgid,0);
		$message = getBody($msgid, $imap);
		$message = str_replace("'", "\'", $message);
		$prettydate = date("Y-m-d H:m:s", $header->udate); 

		if (isset($header->from[0]->personal)) { 
			$personal = $header->from[0]->personal; 
		} else { 
			$personal = $header->from[0]->mailbox; 
		}

		$email = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

		$structure = imap_fetchstructure($imap, $msgid);
		$attachments = get_attachments($structure);
		$attach = array();
		foreach ($attachments as $k => $at) {
			$name = imap_mime_header_decode($at['filename']);//edited
			$filename = str_replace("'", "\'", $name[0]->text);
			$content = imap_fetchbody($imap, $msgid, $at['part']);
			if ($content !== false && strlen($content) > 0 && $content != '') {
				switch ($at['encoding']) {
					case '3':
					$content = base64_decode($content);
					break;
					case '4':
					$content = quoted_printable_decode($content);
					break;
				}
				$fp=fopen($export_dir.$filename, "w+"); fwrite($fp, $content); fclose($fp);
				$attach[] = $filename;
			}
		}

		echo "On $prettydate , $email said :</br>";
		echo 'Subject : '.$overview[0]->subject.'</br>';
		echo 'Message : '.$message.'</br>';
		//print_r($attach);

		$latitude = getLatitude($message);
		$longitude = getLongitude($message);
		$sql = "INSERT INTO `tbl_maps` (`time`, `email`, `subject`, `detail`, `latitude`, `longitude`) VALUES ('".$prettydate."', '".$email."', '".$overview[0]->subject."', '".$message."', '".$latitude."', '".$longitude."')";
		$inMaps = mysql_query($sql);//Yii::app()->db->createCommand($sql)->execute();
		echo $sql;
		
		if ($inMaps && $attach) {
			$lastId = mysql_query('SELECT id FROM `tbl_maps` ORDER BY id DESC LIMIT 1');
			foreach ($attach as $data) {
				$sqlImage = "INSERT INTO `tbl_images` (`path`, `maps_id`) VALUES ('".$data."', '".$lastId."')";
				$inImages = mysql_query($sqlImage);//Yii::app()->db->createCommand($sqlImage)->execute();
				$extFile = strtolower(getExtension($data));
				/*if (($extFile == 'gif') || ($extFile == 'jpg') || ($extFile == 'jpeg') || ($extFile == 'png')) {
					$filepath = $export_dir.$data;
					$thumbpath = $export_dir.'thumbs_'.$data;

					$thumb=Yii::app()->phpThumb->create($filepath);
					$thumb->resize(150,150);
					$thumb->save($thumbpath);
				}*/
				echo $sqlImage;
			}
		}
		echo "</br>----------------------------------------------------------------------</br>";
		imap_delete($imap,$msgid);
	} 
	imap_close($imap,CL_EXPUNGE);
//} else echo '<p>Email terbaru tidak ditemukan.</p>';


mysql_close($link);
?>
