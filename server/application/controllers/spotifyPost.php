<?php
/**
	Author: Erik Larsson
	Info: Controller for Ajaj requests from Spotify to update what song its currently playing.
*/

	class SpotifyPost extends CI_Controller{
		public function index()
		{		
				$partyID = $this->input->post('partyID');
				$trackURI = $this->input->post('trackURI');

				$data = array('partyid' => $partyID, 'trackuri' => $trackURI);

				$query = $this->db->insert('nowplaying', $data);
				
		}
	}
?>