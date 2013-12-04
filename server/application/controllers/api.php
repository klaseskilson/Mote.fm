<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class api extends CI_Controller {


	public function index()
	{
		echo 'derp';
	}

	public function json()
	{
		$data = array('status' => 'ok');

		echo json_encode($data);
	}

	public function plain()
	{
		echo 'ok';
	}

	public function get_playing_song()
	{
		//load helper and model
		$this->load->helper('external_spotify');
		$this->load->model('party_model');

		//FIXME: skall detta hämtas via post istället?
		//get session data 
		$partyID = $this->session->userdata('partyID'); 
		//What track do we think spotify is playing?
		$expectedTrack = $this->session->userdata('trackURI');
		
		$data = array();

		if(!$partyID || !$expectedTrack)
		{
			$data['status'] = 'error';
			$data['respons'] = 'Unable to get partyid or expecte track for party';
		}
		else
		{
			//What track is spotify playing?
			$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];			
			if (!$track) 
			{
				$data['status'] = 'error';
				$data['respons'] = 'Unable to get current track at party';
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
				$this->session->set_userdata('trackURI', $track);
				
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
		// load party model
		$this->load->model('party_model');

		// prepare data for output
		$data = array();

		$name = $this->input->post('name');
		$uid = $this->input->post('uid');
		$locale = $this->input->post('locale');

		// test what was sent using post
		if(!$name || !$uid || !$locale) // somethings is missing!
		{
			// set fail data!
			$data['status'] = 'error';
			$data['respons'] = 'Missing post data. Not all needed fields were sent.';
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
		}

		// write array json encoded
		echo json_encode($data);
	}
	/**
	 * Post playing song in spotify at the party
	 */
	public function post_playing_song()
	{
		// load party model	
		$this->load->model('party_model');

		$partyid = $this->input->post('partyid');
		$trackuri = $this->input->post('trackuri');
		
		$data = array();
		if(!$partyid || !$trackuri)
		{
			$data['status'] = 'error';
			$data['respons'] = 'Missing post data. Not all needed fields were sent.';
		}
		elseif(!$this->Party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['respons'] = 'Party was not found';	
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
		$this->load->model('Party_model');

		$partyid = $this->input->post('partyid');
		$data = array();

		if(!$partyid)
		{
			$data['status'] = 'error';
			$data['respons'] = 'Missing post data. Not all needed fields were sent.';
		}
		elseif(!$this->Party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['respons'] = 'Party not found.';
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

	public function login_from_spotify()
	{
		$this->load->library('PasswordHash', array(8, false));
		$this->load->model('user_model');

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$data = array();
		if(!$email || !$password)
		{
			$data['status'] = 'error';
			$data['respons'] = 'Missing post data. Not all needed fields were sent.';
		}
		elseif(!$this->user_model->email_exists($email))
		{
			$data['status'] = 'error';
			$data['respons'] = 'No account found for ' . $email . ' Please register before using Hathor.';	
		}
		elseif($this->user_model->validate($email, $password))
		{
			$data['status'] = 'success';
			$uid = $this->user_model->get_id($email);
			$data['result'] = $this->user_model->get_all_info($uid);
		}
		else
		{
			$data['status'] = 'error';
			$data['respons'] = 'Incorrect password';			
		}

		echo json_encode($data);
	}
}
