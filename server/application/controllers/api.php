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
}
