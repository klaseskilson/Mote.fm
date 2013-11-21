<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// logged in?
		if($this->login->is_logged_in())
			$this->dashboard();
		else
			$this->start();
	}

	public function start($message = '')
	{
		// prepare data to send to views
		$data = array();

		$data['title'] = 'Democratize the play queue';
		$data['bodystyle'] = 'fancypane';

		$this->load->view('templates/header', $data);
		$this->load->view('landing', $data);
		$this->load->view('templates/footer', $data);
	}

	public function dashboard($message = '')
	{
		$this->load->model('party_model');

		$data = array();
		$data['user'] = $this->login->get_all_info();
		$data['user']['names'] = explode(" ", $data['user']['name']);

		$data['title'] = 'Dashboard';
		$data['message'] = $message;

		$this->load->view('templates/header', $data);
		$this->load->view('dashboard', $data);
		$this->load->view('templates/footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
