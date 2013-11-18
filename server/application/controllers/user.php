<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("user_model");

		// load password hash library
		$this->load->library('PasswordHash', array(8, false));
	}
	
	public function index()
	{
		$this->getUsers();
	}

	public function signUp()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		if (!($this->user_model->create_user($email, $name, $password)))
		{
			echo "Something went wrong. Kiss och bajs.";
		}
		else
		{
			echo "Well done my kuk. Anal. penis";

		}
	}

	public function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if (!($this->user_model->validate($email, $password)))
		{
			echo "Wrong username or password.";
		}
		else
		{
			echo "Schysst jobbat grabben. Men du är ändå rätt dålig.";
		}

	}

	public function reset()
	{
		$email = $this->input->post('email');
		$id = $this->user_model->get_id($email);

		if ($this->user_model->user_exist($id))
		{
			$hash = $this->user_model->reset($email);
			$this->load->library('email');
			$this->email->from('noreply@taketkvg.se', 'Einis');
			$this->email->to($email);
			$this->email->subject('Password reset');
			$this->email->message('Fuck you. hathor.se/user/forgotPassword/'.$email.'/'.$hash);
		}
	}
	public function forgotPassword($email, $hash)
	{
		$newPassword = $this->input->post('newPassword');
		$confirm = $this->input->post('confirm');
		$id = $this->user_model->get_id($email);

		if(!($this->user_model->update_password($id, $newPassword, $confirm)))
		{
			echo "Something went wrong.";
		}
		else
		{
			echo "Your pasword has been reset.";
		}

	}
	/*
	public function getUsers()
	{
		$allUsers = $this->user_model->get_all();
		echo $allUsers;
	}

	public function thisId()
	{
		$email = 'ballesson@gmail.com';
		$myId = $this->user_model->get_id($email);
		echo $myId;
		
	}

	public function checkUser()
	{
		$id = 2;

		$balle = $this->user_model->user_exist($id);
		echo $balle;
	}*/




	
}
