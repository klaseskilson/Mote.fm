<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class SpotifyLoginUser extends CI_Controller
{
	function index()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$this->load->model('user_model');
		if($this->user_model->validate($email, $password))
		{
			echo "sucess";
		}
		else
		{
			echo "fail";	
		}
		
	}
}
?>