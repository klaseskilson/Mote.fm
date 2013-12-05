<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class party extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//Do your magic here

		// all data here should be json formatted
		header("Content-type: application/json");

		// load party model
		$this->load->model('party_model');
	}

	/**
	 * Returns the currently played song in spotify from database
	 */
	public function get_playing_song()
	{
		//load helper and model
		$this->load->helper('external_spotify');
		$this->load->model('party_model');

		//FIXME: skall detta hämtas via post istället?
		//get session data 
		$partyID = $this->session->userdata('partyid'); 
		//What track do we think spotify is playing?
		$expectedTrack = $this->session->userdata('trackuri');
		
		$data = array();

		if(!$partyID || !$expectedTrack)
		{
			$data['status'] = 'error';
			$data['response'] = 'Unable to get partyid or expected track for party';
		}
		else
		{
			//What track is spotify playing?
			$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];			
			if (!$track) 
			{
				$data['status'] = 'error';
				$data['response'] = 'Unable to get current track at party';
			}
			else
			{
				//continue querying database while both spotify and us say the same track is playing
				$startTime = time();
				$currentTime = time();
				while(($currentTime - $startTime) < 280 && $track == $expectedTrack)
				{
					$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
					$currentTime = time();
					sleep(1);
				}

				//store new track in session data and return the answer
				$this->session->set_userdata('trackuri', $track);
				
				$status = array();
				$status['track'] = $track;
				$status['trackname'] = get_track_name($track);
				$status['artistname'] = get_artist_name($track);
				$status['albumart']	= get_album_art($track);

				$data['status'] = 'success';
				$data['result'] = $status;
			}
		}

		echo json_encode($data);
	}

	/**
	 * create party
	 */
	public function create_party()
	{
		// prepare data for output
		$data = array();

		//get postdata
		$name = $this->input->post('name');
		$uid = $this->input->post('uid');
		$locale = $this->input->post('locale');

		// test what was sent using post
		if(!$name || !$uid || !$locale) // somethings is missing!
		{
			// set fail data!
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		else // everyting is as it should
		{
			// create party
			$partyid = $this->party_model->create_party($uid, $name, $locale);
			// success?
			if($partyid)
			{
				// set success data
				$data['status'] = 'success';
				$data['result'] = $this->party_model->get_party_from_id($partyid);
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Could not create party';
			}
		}
		// write array json encoded
		echo json_encode($data);
	}

	/**
	 * Post playing song in spotify at the party
	 */
	public function spotify_song()
	{
		//prepare data
		$data = array();

		//get Post data
		$partyid = $this->input->post('partyid');
		$trackuri = $this->input->post('trackuri');
		
		if(!$partyid || !$trackuri)
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		elseif(!$this->party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['response'] = 'Party was not found';	
		}
		else
		{
			$data = array('partyid' => $partyid, 'trackuri' => $trackuri);
			$query = $this->db->insert('nowplaying', $data);

			$data['status'] = 'success';
			$data['result'] = $query;
		}
		
		echo json_encode($data);
	}
	
	/**
	 * get the playlist for the party
	 */
	public function get_party_list()
	{
		$partyid = $this->input->post('partyid');
		$data = array();

		if(!$partyid)
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		elseif(!$this->Party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['response'] = 'Party not found';
		}
		else
		{
			$first = $this->Party_model->get_party_que($partyid);

			while(true)
			{
				$second = $this->Party_model->get_party_que($partyid);

				if($second == $first)
					sleep(3);
				else
					break;
			}
			$data['status'] = 'success';
			$data['result'] = $second;
		}

		// write array json encoded
		echo json_encode($data);
	}

	public function add_song()
	{
		// prepare data array
		$data = array();

		// get spotify song uri sent via post
		$uri = $this->input->post("uri");

		if($uri)
		{
		}
		else
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing song post data';
		}
		echo json_encode($data);
	}
}