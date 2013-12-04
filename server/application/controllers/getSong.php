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
		
		$partyhash = $this->input->post('hash');

		$partyid = $this->party_model->get_party_from_hash($partyhash)

		//FIXME: get session user data
		if(!$this->session->userdata('track'))
		{
			$this->session->set_userdata('track',$this->party_model->get_current_track_at_party($this->session->userdata('partyID'))['trackuri']);
		}


		$data = array();
		$data['title'] = 'Get song demo';
		$data['user'] = $this->login->get_all_info();
		$data['Partyid'] = $this->session->userdata('partyID');
		
		//flag to say if we should import ajax stuff
		$data['ajax'] = true;
		
		$data['track'] = $this->session->userdata('track');
		$data['artistname'] = get_artist_name($data['track']);
		$data['trackname'] = get_track_name($this->session->userdata('track'));
		$data['trackdata']	= get_album_art($data['track']);
		
		$this->load->view('templates/header', $data);
		$this->load->view($view);
		$this->load->view('templates/footer', $data);
	}

}

/* End of file getSong.php */
/* Location: ./application/controllers/getSong.php */
?>