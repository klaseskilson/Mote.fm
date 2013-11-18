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
	}



	
}
