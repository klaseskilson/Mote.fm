<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Spotify model, made for handeling spotifycache table
 */

class Spotify_model extends CI_model
{
	private $CI;

	function __construct()
	{
		// call model constructor
		parent::__construct();

		// load CI to use some helper functions
		$CI =& get_instance();
	}

	function cache_exist($uri)
	{
		$this->db->select('cacheid');
		$this->db->where('uri', $uri);
		$this->db->limit(1);
		$query = $this->db->get('spotifycache');

		if($query && $query->num_rows() > 0)
		{
			// save result to array
			$result = $query->result_array();
			// return cacheid, might as well, could be useful
			return $result[0]['cacheid'];
		}

		// nothing found
		return false;
	}

	function insert_cache($uri, $songname, $artistname, $albumname, $image)
	{
		$alreadyexisting = $this->cache_exist($uri);
		if($alreadyexisting)
			return $alreadyexisting;

		$data = array(
					'uri' => $uri,
					'songname' => $songname,
					'artistname' => $artistname,
					'albumname' => $albumname,
					'image' => $image
				);

		if($this->db->insert('spotifycache', $data))
			return $this->db->insert_id();
	}

	function get_cache($uri)
	{
		$this->db->get('*');
		$this->db->where('uri', $uri);
		$query = $this->db->get('spotifycache');

		if($query && $query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0];
		}

		// nothing found
		return false;
	}
}
