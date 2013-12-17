<?php
	/*
	Helper for external querying against spotify api
	simple helpful stuff
	*/

	/**
	 * Get artist name from spotify api TODO: only returns one artist
	 * @param  string $trackURI Spotify uri
	 * @return string artist name
	 */
	function get_artist_name($trackURI)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ws.spotify.com/lookup/1/.json?uri=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($data);
		$artists = $json->track->artists[0]->name;
		if($artists)
		{
			return $artists;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get thumbnail for album related to track
	 * @param  string $trackURI Spotify uri
	 * @return string url to thumbnail           
	 */
	function get_album_art($trackURI)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://embed.spotify.com/oembed/?url=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, getcwd(). "\spotifyembedd.crt");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    		'Content-Type: application/json',
    		'Accept: application/json'
		));
		$data = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		$json = json_decode($data, true);
		$url = $json['thumbnail_url'];
		if($url)
		{
			return $url;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get name of track 
	 * @param  string $trackURI Spotify uri
	 * @return string name of song
	 */
	function get_track_name($trackURI)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ws.spotify.com/lookup/1/.json?uri=".$trackURI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($data);
		return $json->track->name;
	}

?>