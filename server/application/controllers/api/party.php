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
	 * create party
	 */
	public function create_party()
	{

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
			$data['response'] = 'Missing post data. Not all needed fields were sent.';
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
				$data['response'] = 'Could not create party.';
			}
		}

		// write array json encoded
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

		// output the json from data
		echo json_encode($data);
	}
}
