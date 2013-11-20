<?php
	/*
	Helper for external querying against spotify api
	simple helpful stuff
	*/

	function get_track_name($trackURI)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ws.spotify.com/lookup/1/?uri=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($data);

		return "Artist: " . $xml->artist->name . " Track: " . $xml->name;
	}

?>