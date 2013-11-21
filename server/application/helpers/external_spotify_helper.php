<?php
	/*
	Helper for external querying against spotify api
	simple helpful stuff
	*/
	function get_artist_name($trackURI)
	{

	}

	function get_album_art($trackURI)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://embed.spotify.com/oembed/?url=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($data);

		return var_dump($xml);
	}

	function get_track_name($trackURI)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ws.spotify.com/lookup/1/?uri=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($data);
		return false;
		return $xml->name;
	}

?>