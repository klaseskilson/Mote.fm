<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party extends CI_controller
{
	private $data = array();

	function __construct()
	{
		parent::__construct();

		// make sure that the user is logged in. Redirect to login page if not.
		if(!$this->login->is_logged_in())
			redirect('/user/signin?redir='.urlencode(uri_string()));

		$this->load->helper('external_spotify');
		$this->load->model('party_model');
	}

	function index()
	{
		$this->all();
	}

	/**
	 * some sort of party dashboard
	 */
	function all()
	{
		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		// load party data
		$data['parties'] = $this->party_model->get_all_parties($this->login->get_id());
		$data['party_contrib'] = $this->party_model->contrib_parties($this->login->get_id());

		$this->load->view('templates/header', $data);
		$this->load->view('dashboard', $data);
		$this->load->view('templates/footer', $data);
	}

	function view($hash = '')
	{
		if(!$this->party_model->hash_exists($hash))
		{
			log_message('error', 'Party hash '.$hash.' returned 404.');
			show_404();
		}

		// load the extra js needed for our spotify search
		$data['extra_js'] = array('spotify_search.js');

		// save user in handy variable
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$data['party'] = $this->party_model->get_party_from_hash($hash);

		$queue =  $this->party_model->get_party_queue_from_hash($hash);

		for($i = 0; $i < sizeof($queue); $i++)
		{
			$queue[$i]['artistname'] = get_artist_name($queue[$i]['uri']);
			$queue[$i]['trackname'] = get_track_name($queue[$i]['uri']);
			$queue[$i]['albumart'] = get_album_art($queue[$i]['uri']);
			$queue[$i]['voters'] = $this->party_model->get_voters_from_song($queue[$i]['songid']);
		}
		$data['party_queue'] = $queue;

		$this->load->view('templates/header', $data);
		$this->load->view('party', $data);
		$this->load->view('templates/footer', $data);
	}
}
