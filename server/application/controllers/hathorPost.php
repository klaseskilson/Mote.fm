<?php
/**
	Author: Erik Larsson
	Info: Controller for Ajaj requests from Hathor to find what song currently is playing.
*/

	class HathorPost extends CI_Controller{

		public function index()
		{
			
			$partyID = $this->session->userdata('partyID'); 
			$expectedTrack = $this->session->userdata('trackURI');

			$this->load->helper('external_spotify');
			$this->load->model('party_model');
			$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
			$expectedTrack = $this->session->userdata('trackURI');
			while($track == $expectedTrack)
			{
				$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
				sleep(5);
			}
			$this->session->set_userdata('trackURI', $track);
			$status = "Hathor says: ". get_track_name($expectedTrack) . " <br /> Spotify says: " . get_track_name($track);
			echo $status;
		}
	}
?>