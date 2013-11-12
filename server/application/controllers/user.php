<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("user_model");
	}
	
	public function index()
	{
		$this->bajs();
	}

	public function bajs($kebab="hejhej")
	{
		$this->user_model->create_user("einars92@gmail.com", "Einar", "Bajsson", "hejhejhej");

	}
	public function signUp()
	{
		$name = $this->input->post('Einis');

		if ($name == 'Einis')
		{
			echo "GULLEEEEEE";
		}
		else
		{
			echo "OGULLEEEE";
		}

	}
}
