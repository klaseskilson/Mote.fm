<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party extends CI_controller
{
	private $data = array();

	function __construct()
	{
		parent::__construct();

		$this->load->model('party_model');
	}

	function index()
	{
		$this->view();
	}

	function all()
	{
		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$this->load->view('templates/header', $data);
		$this->load->view('party_main', $data);
		$this->load->view('templates/footer', $data);

	}

	function view($id = 0)
	{
		// load the extra js needed for our spotify search
		$data['extra_js'] = array('spotify_search.js');

		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$this->load->view('templates/header', $data);
		$this->load->view('party_main', $data);
		$this->load->view('templates/footer', $data);
	}
}
