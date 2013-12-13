<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Party_model extends CI_model
{
	private $CI;

	function __construct()
	{
		// call model constructor
		parent::__construct();

		// load CI to use some helper functions
		$CI =& get_instance();
	}


	function create_party($uid, $name = 'The best party ever', $locale = 'sv')
	{
		// load user model for user check
		$this->load->model('User_model');
		$this->load->helper('common');

		// check if user exists & locale is set, exit function if not
		if(!$this->user_model->user_exist($uid) && strlen($locale) !== 2)
			return false;

		// save data into array
		$data = array(
					'uid' => $uid,
					'name' => $name,
					'locale' => $locale,
					'hash' => strgen(5, true, false, true) // generate a 5 char long hash, ALPHAnumeric
				);

		// insert data!
		if($this->db->insert('parties', $data))
			// did it work? return the created id.
			return $this->db->insert_id();

		// if we got this far, something went wrong
		return false;
	}
	/**
	 * return meta info about party
	 */
	function get_party_from_id($partyid)
	{
		// select all columns from parties where partyid=$partyid
		$this->db->select('*');
		$this->db->where('partyid', $partyid);
		$this->db->limit(1);

		// run query!
		$query = $this->db->get('parties');

		// query worked?
		if($query)
		{
			// return an array with the first row from the results
			$result = $query->result_array();
			return $result[0];
		}

		// if we got this far, something went wrong
		return false;
	}

	/**
	 * [get_current_track_at_party description]
	 * @param  [type] $partyid
	 * @return [type]
	 */
	function get_party_from_hash($partyhash)
	{
		// select all columns from parties where partyid=$partyid
		$this->db->select('*');
		$this->db->where('hash', $partyhash);

		// run query!
		$query = $this->db->get('parties');
		// query worked?
		if($query)
		{
			// return an array with the first row from the results
			$result = $query->result_array();
			if(sizeof($result) > 0)
			{
				return $result[0];
			}
		}

		// if we got this far, something went wrong
		return false;
	}

	function get_party_id_from_hash($hash)
	{
		$this->db->select('partyid');
		$this->db->where('hash', $hash);
		$query = $this->db->get('parties');

		if($query && $query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0]['partyid'];
		}

		return false;
	}

	function get_current_track_at_party($partyid)
	{
		$this->db->select('*');
		$this->db->where('partyid', $partyid);
		$this->db->order_by('playid','desc');
		$this->db->limit(1);

		$query = $this->db->get('nowplaying');

		if($query->num_rows() == 1)
		{
			$result = $query->result_array();
			return $result[0];
		}
		return false;
	}

	function get_party_queue($partyid)
	{
		if(!$this->party_exists($partyid))
			return false;

		$this->db->select('quesong.*, COUNT(voteid) AS vote_count');
		$this->db->from('quesong');
		$this->db->join('quevote', 'quesong.songid = quevote.songid');
		$this->db->where('partyid', $partyid);
		$this->db->group_by('songid');
		$this->db->order_by('vote_count desc, quesong.time');

		$result = $this->db->get();

		return $result->result_array();
	}

	function get_party_queue_from_hash($hash)
	{
		$partyid = $this->get_party_id_from_hash($hash);

		// partyid = false if no party was found
		if(!$partyid)
			return $partyid;

		return $this->get_party_queue($partyid);
	}

	function party_exists($partyid)
	{
		$this->db->select('partyid');
		$this->db->where('partyid', $partyid);
		$this->db->limit(1);
		$query = $this->db->get('parties');

		// in php, everything !== 0 is true
		if($query)
			return $query->num_rows();

		// if we got this far, something went wrong
		return false;
	}

	/**
	 * add song to que
	 * @param int 		$uid 		the user id
	 * @param int 		$partyid 	the party id to add the song to
	 * @param string 	$trackuri 	the spotify track uri
	 *
	 * @return  int the inserted que id
	 */
	function add_song($uid, $partyid, $trackuri)
	{
		// does the song allready exist in this party? if so, add vote and move on.
		if($this->song_exists($partyid, $trackuri))
			return $this->add_vote($this->get_song_id_from_uri($trackuri, $partyid), $uid);

		$data = array(
					'uid' => $uid,
					'partyid' => $partyid,
					'uri' => $trackuri
				);

		if($this->db->insert('quesong', $data))
			return array('songid' => $this->db->insert_id(), 'voted' => $this->add_vote($this->db->insert_id(), $uid));

		return false;
	}
	
	/**
	 * add vote to quevote table
	 * @param 	int 	$songid the songid that is voted on
	 * @param 	int 	$uid 	the user who voted
	 *
	 * @return  the new vote inserted id
	 */
	function add_vote($songid, $uid)
	{
		//If user already have voted on song
		if(!$this->vote_exists($uid, $songid))
		{
			$data = array(
						'uid' => $uid,
						'songid' => $songid
					);

			// if inserting works, return the new vote id
			if($this->db->insert('quevote', $data))
				return array('voteid' => $this->db->insert_id());

			return false;
		}
		return array('voteid' => 'vote allready exists');
	}

	function song_exists($partyid, $song)
	{
		// is it the song id or the song uri we get?
		if(is_numeric($song))
			$this->db->where('songid', $song);
		else
			$this->db->where('uri', $song);

		// add the party criteria
		$this->db->where('partyid', $partyid);

		// run query
		$query = $this->db->get('quesong');

		// did the query work? return num rows
		if($query) return $query->num_rows();

		// query faulty, return false
		return false;
	}

	function get_song_id_from_uri($uri, $partyid)
	{
		// add criterias, uri = $uri, only one result
		$this->db->select('songid');
		$this->db->where('uri', $uri);
		$this->db->where('partyid', $partyid);
		$this->db->limit(1);

		// run query!
		$query = $this->db->get('quesong');

		if($query )
		{
			$result = $query->result_array();
			return $result[0]['songid'];
		}

		// query faulty
		return false;
	}

	function vote_exists($uid, $songid)
	{
		$this->db->where('uid', $uid);
		$this->db->where('songid', $songid);
		$this->db->limit(1);

		$query = $this->db->get('quevote');

		if($query) return $query->num_rows();

		return false;
	}

	/**
	 * get all parties from a specific user
	 * @param  int $uid the user id
	 * @return the parties, array
	 */
	function get_all_parties($uid)
	{
		$this->db->where('uid', $uid);
		$this->db->order_by('time', 'desc');
		$query = $this->db->get('parties');

		// only return true if we have something to show
		if($query && $query->num_rows() > 0)
			return $query->result_array();

		// return false
		return false;
	}

	/**
	 * check if it exists a party with a given hash
	 * @param  string $hash the hash to search for
	 * @return bool
	 */
	function hash_exists($hash)
	{
		$this->db->where('hash', $hash);
		$this->db->limit(1);
		$query = $this->db->get('parties');

		if($query) return $query->num_rows();

		return false;
	}

	/**
	 * get parties the user contributed to, ie voted and added to
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	function contrib_parties($uid)
	{
		$this->db->distinct();
		$this->db->select('quesong.partyid, parties.name, parties.hash');
		$this->db->from('quevote');
		$this->db->join('quesong', 'quesong.uid = quevote.uid', 'left');
		$this->db->join('parties', 'parties.partyid = quesong.partyid', 'left');
		$this->db->where('quevote.uid', $uid);
		$this->db->or_where('quesong.uid', $uid);
		$this->db->order_by('quevote.time');

		$query = $this->db->get();

		if($query && $query->num_rows() > 0)
			return $query->result_array();

		return false;
	}
}
