<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Party_model extends CI_model
{
	function __construct()
	{
		// call model constructor
		parent::__construct();
	}


	function create_party($uid, $name = 'The best party ever', $locale = 'sv')
	{
		// load user model for user check
		$this->load->model('User_model');

		// check if user exists & locale is set, exit function if not
		if(!$this->user_model->user_exists($uid) && strlen($locale) !== 2)
			return false;

		// save data into array
		$data = array(
					'uid' => $uid,
					'name' => $name,
					'locale' => $locale,
					'hash' => hashgen(5)
				);

		// insert data!
		if($this->db->insert('parties', $data))
			// did it work? return the created id.
			return $this->db->insert_id();

		// if we got this far, something went wrong
		return false;
	}

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

	function get_current_track_at_party($partyid)
	{
		$this->db->select('*');
		$this->db->where('partyid', $partyid);
		$this->db->order_by('playid','desc');
		$this->db->limit(1);

		$query = $this->db->get('nowplaying');

		if($query)
		{
			$result = $query->result_array();
			return $result[0];
		}

		return false; 
	}
}
