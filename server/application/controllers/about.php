<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->view();
	}

	public function view()
	{
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$this->load->view('templates/header', $data);
		$this->load->view('about', $data);
		$this->load->view('templates/footer', $data);
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */
