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

		if(!$name || !$uid || !$locale)
		{
			$data['status'] = 'error';
			$data['respons'] = 'Missing post data. Not all needed fields were sent.';
		}
		else
		{
			$partyid = $this->party_model->create_party($uid, $name, $locale);
			if($partyid)
			{
				$data['status'] = 'ok';
				$data['result'] = $this->party_model->get_party_from_id($partyid);
			}
		}

		// write array json encoded
		echo json_encode($data);
	}
}
