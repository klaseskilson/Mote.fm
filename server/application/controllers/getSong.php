<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetSong extends CI_Controller 
{
	public function index()
	{
		//set view
		$view = "getsong";

		//load models and helper
		$model = $this->load->model('party_model');
		$this->load->helper('external_spotify');

		//FIXME: get session user data
		if(!$this->session->userdata('partyID'))
		{
			//FIXME: hardcoded partyID
			$this->session->set_userdata('partyID',123456);	
		}
		if(!$this->session->userdata('track'))
		{
			$this->session->set_userdata('track',$this->party_model->get_current_track_at_party($this->session->userdata('partyID'))['trackuri']);		
		}


		$data = array();
		$data['title'] = 'Democratize the play queue';
		//flag to say if we should import ajax stuff
		$data['ajax'] = true;
		$data['track'] = get_track_name($this->session->userdata('track'));

		
		$this->load->view('templates/header', $data);
		$this->load->view($view);
		$this->load->view('templates/footer', $data);
	}

}

/* End of file getSong.php */
/* Location: ./application/controllers/getSong.php */
?>