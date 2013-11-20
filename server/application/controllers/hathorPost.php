<?php
/**
	Author: Erik Larsson
	Info: Controller for Ajaj requests from Hathor to find what song currently is playing.
*/

	class HathorPost extends CI_Controller{

		public function index()
		{
			//get session data
			$partyID = $this->session->userdata('partyID'); 
			$expectedTrack = $this->session->userdata('trackURI');

			//load helper and model
			$this->load->helper('external_spotify');
			$this->load->model('party_model');

			//What track is spotify playing?
			$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
			//What track do we thing spotify is playing?
			$expectedTrack = $this->session->userdata('trackURI');
			
			//continue querying database while both spotify and us say the same track is playing
			while($track == $expectedTrack)
			{
				$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
				sleep(1);
			}

			//store new track in session data and return the answer
			$this->session->set_userdata('trackURI', $track);
			$status = get_track_name($track);
			echo $status;
		}
	}
?>