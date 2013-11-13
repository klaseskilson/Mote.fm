<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetSong extends CI_Controller {

	public function index()
	{
		$view = "getsong";
		$this->load->view($view);
	}

}

/* End of file getSong.php */
/* Location: ./application/controllers/getSong.php */
?>