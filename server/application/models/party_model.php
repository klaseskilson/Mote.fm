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

	function get_party_que($partyid)
	{
		if(!$this->party_exists($partyid))
			return false;

		$this->db->select('quesong.*, COUNT(vote_id) AS vote_count');
		$this->db->from('quesong');
		$this->db->where('partyid', $partyid);
		$this->db->join('quevote', 'quesong.songid = quevote.songid', 'left');
		$this->db->group_by('quesong.songid');
		$this->db->order_by('vote_count desc, quesong.time');

		$result = $this->db->get();

		return $result->result_array();
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
		$data = array(
					'uid' => $uid,
					'partyid' => $partyid,
					'trackuri' => $trackuri
				);

		if($this->db->insert('quesong', $data))
			return $this->db->insert_id();

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
		$data = array(
					'uid' => $uid,
					'songid' => $songid
				);

		// if inserting works, return the new vote id
		if($this->db->insert('quevote', $data))
			return $this->db->insert_id();

		return false;
	}
}
