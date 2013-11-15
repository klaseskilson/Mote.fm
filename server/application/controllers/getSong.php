<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetSong extends CI_Controller 
{
	public function index()
	{
		$data = array();
		
		$model = $this->load->model('party_model');
		$data['title'] = 'Democratize the play queue';
		$data['ajax'] = true;

		$view = "getsong";
		$this->load->helper('external_spotify');
		$track = get_track_name("spotify:track:0Rynk2V7LyLgBUjTMxvbEJ");
		$data['track'] = $track;
		$this->load->view('templates/header', $data);
		$this->load->view($view);
		$this->load->view('templates/footer', $data);
	}

}

/* End of file getSong.php */
/* Location: ./application/controllers/getSong.php */
?>