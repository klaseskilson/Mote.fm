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
		// prepare data to send to views
		$data = array();

		// what main view should we use?
		// depends on user status. logged in or not?
		if($this->login->is_logged_in())
		{
			$view = 'dashboard'; // show the logged in user the dashboard
		}
		else
		{
			$data['title'] = 'Democratize the play queue';
			$view = 'landing'; // show the potential new user the cool awesome landing page
		}

		$this->load->view('templates/header', $data);
		$this->load->view($view, $data);
		$this->load->view('templates/footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
