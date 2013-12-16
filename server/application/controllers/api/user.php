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

		// load password hash library
		$this->load->library('PasswordHash', array(8, false));
		// load email helper and library for validating, sending & recieving mail
		$this->load->library('email');
		$this->load->helper('email');
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

	public function signUp()
	{
		$data = array();

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if($name)
		{
			// validate email using CI magic && try signup
			if (valid_email($email) && $this->user_model->create_user($email, $name, $password))
			{
				$this->login->validate($email, $password);

				//send activation email
				$hash = $this->user_model->createHash($email);
				$this->email->from('noreply@taketkvg.se', 'The Hathor crew');
				$this->email->to($email);
				$this->email->subject('Activate your Hathor account');
				$this->email->message('Hey '.$name.'! Follow this link to activate your Hathor account. '
				.base_url().'user/activate/'.urlencode($email).'/'.$hash);
				// $this->email->send();

				// how do we want the response?
				$data['status'] = 'success';
				$data['result'] = array(
											'email' => $email
										);
			}
			else // something wrong!
			{
				// how do we want the response?
				// return response using json! prepare data
				$data = array(
								'status' => 'error',
								'errors' => array(
												'name'	 => (strlen($name) > 2),
												'email'	 => valid_email($email) && !$this->user_model->email_exists($email),
												'password' => !(strlen($password) <= 6)
											)
						    );
			}
			echo json_encode($data);
		}
	}
}
