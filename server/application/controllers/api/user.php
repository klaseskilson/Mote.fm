<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class party extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//Do your magic here

		// all data here should be json formatted
		header("Content-type: application/json");

		// load party model
		$this->load->model('user_model');
	}

	public function signin()
	{
		$this->load->library('PasswordHash', array(8, false));

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
