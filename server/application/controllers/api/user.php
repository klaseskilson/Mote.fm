<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//Do your magic here

		// all data here should be json formatted
		header("Content-type: application/json");

		// load user model
		$this->load->model('user_model');
	}
	
	/**
	 * Send a login request to server (Do we have a session server-side?)
	 */
	public function signin()
	{
		$this->load->library('PasswordHash', array(8, false));

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$data = array();

		if(!$email || !$password)
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data. Not all needed fields were sent.';
		}
		elseif(!$this->user_model->email_exists($email))
		{
			$data['status'] = 'error';
			$data['response'] = 'No account found for ' . $email . ' Please register before using Hathor.';	
		}
		elseif($this->login->validate($email, $password))
		{
			$data['status'] = 'success';
			$uid = $this->user_model->get_id($email);
			$data['result'] = $this->user_model->get_all_info($uid);
		}	
		else
		{
			$data['status'] = 'error';
			$data['response'] = 'Incorrect password';			
		}

		echo json_encode($data);
	}
}
