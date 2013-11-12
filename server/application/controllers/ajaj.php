<?php

class Ajaj extends CI_Controller{

	public function index()
	{
		$data['title'] = 'Democratize the play queue';
		$view = 'ajajtest'; // show the potential new user the cool awesome landing page

		$this->load->view('templates/header', $data);
		$this->load->view($view, $data);
		$this->load->view('templates/footer', $data)		
	}
}
?>