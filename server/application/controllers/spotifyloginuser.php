<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class SpotifyLoginUser extends CI_Controller
{

	function __construct()
	{
		// call model constructor
		parent::__construct();

		// load CI to use some helper functions
		$CI =& get_instance();
	}

	function index()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$this->load->library('PasswordHash', array(8, false));
		$this->load->model('user_model');
		if($this->user_model->email_exists($email) && $this->user_model->validate($email, $password))
		{
			echo "success";
		}
		else
		{
			echo "fail";	
		}

		
	}
}
?>