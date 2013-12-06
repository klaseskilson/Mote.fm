<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party extends CI_controller
{
	private $data = array();

	function __construct()
	{
		parent::__construct();

		// make sure that the user is logged in. Redirect to login page if not.
		if(!$this->login->is_logged_in() && $this->uri->segment(2) == 'profile')
			redirect('/user/signin?redir='.urlencode(uri_string()));

		$this->load->model('party_model');
	}

	function index()
	{
		$this->all();
	}

	function all()
	{
		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$data['parties'] = $this->party_model->get_all_parties($this->login->get_id());

		$this->load->view('templates/header', $data);
		$this->load->view('party/all', $data);
		$this->load->view('templates/footer', $data);

	}

	function view($hash = '')
	{
		if($this->party_model->hash_exists($hash))
		{
			log_message('error', 'Party hash '.$hash.' returned 404.');
			show_404();
		}

		// load the extra js needed for our spotify search
		$data['extra_js'] = array('spotify_search.js');

		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$this->load->view('templates/header', $data);
		$this->load->view('party/party', $data);
		$this->load->view('templates/footer', $data);
	}
}
