<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		// logged in?
		if($this->login->is_logged_in())
			//$this->dashboard();
			redirect(base_url().'party');
		else
			$this->landing();
	}

	public function landing($message = '')
	{
		// prepare data to send to views
		$data = array();

		$data['title'] = 'Democratize the play queue';
		$data['bodystyle'] = 'fancypane';

		// $this->load->view('templates/header', $data);
		$this->load->view('landing', $data);
		$this->load->view('templates/footer', $data);
	}

	public function start()
	{
		$this->landing();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
