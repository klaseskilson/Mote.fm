<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
	Author: Erik Larsson
	Info: Controller for party registrations from Spotify.
*/
class RegisterParty extends CI_Controller {

	public function index()
	{
		//get post data from spotify
		$uid = $this->input->post('uid');
		$partyName = $this->input->post('partyname');
		$locale = $this->input->post('locale');
		
		//Load model, create party, return party hash
		$this->load->model('party_model');
		$insertID = $this->party_model->create_party($uid, $partyName, $locale);
		echo $this->party_model->get_party_from_id($insertID)['hash'];
	}
}

/* End of file registerParty.php */
/* Location: ./application/controllers/registerParty.php */
?>