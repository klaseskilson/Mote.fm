<?php
/**
	Author: Erik Larsson
	Info: Controller for Ajaj requests from Hathor to find what song currently is playing.
*/

	class HathorPost extends CI_Controller{

		public function index()
		{		


			$partyID = $this->input->post('partyID');
			$trackURI = $this->input->post('trackuri');

			$this->load->model('party_model');
			echo $this->party_model->get_current_track_at_party($partyID)['trackuri'];
		}
	}
?>