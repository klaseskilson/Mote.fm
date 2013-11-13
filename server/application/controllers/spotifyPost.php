<?php
/**
	Author: Erik Larsson
	Info: Controller for Ajaj requests from Spotify to update what song its currently playing.
*/

	class SpotifyPost extends CI_Controller{
		public function index()
		{		
				$partyID = $_POST['partyID'];
				$trackURI = $_POST['trackURI'];

				$data = array('playingid' => ,'festid' => $partyID, 'trackuri' => $trackURI);

				$str = $this->db->insert_string('nowplaying', $data);
				$query = $this->db->query($str);
				echo $query;
		}
	}
?>