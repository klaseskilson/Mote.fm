<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetSong extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		if(!$this->login->is_logged_in())
		{
			redirect('/user/signin?redir='.urlencode(uri_string()));
		}

		//load models and helper
		$this->load->model('party_model');
		$this->load->model('user_model');
		$this->load->helper('external_spotify');

	}

	public function index()
	{
		//set view
		$view = "getsong";
		
		$partyhash = $this->input->get('hash');

		$party = $this->party_model->get_party_from_hash($partyhash);
		$getTrack = $this->party_model->get_current_track_at_party($party['partyid']);
		$this->session->set_userdata('trackuri',$getTrack['trackuri']);	
		$this->session->set_userdata('partyid',$party['partyid']);	

		$data = array();
		$data['title'] = 'Get song demo';
		$data['user'] = $this->login->get_all_info();
		$data['ajax'] = true;
		$data['track'] = $this->session->userdata('trackuri');
		$data['artistname'] = get_artist_name($data['track']);
		$data['trackname'] = get_track_name($data['track']);
		$data['trackdata']	= get_album_art($data['track']);
		$data['partyname'] = $party['name'];
		
		$this->load->view('templates/header', $data);
		$this->load->view($view);
		$this->load->view('templates/footer', $data);
	}

}

/* End of file getSong.php */
/* Location: ./application/controllers/getSong.php */
?>