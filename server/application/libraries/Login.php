<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * THIS SYSTEM USES THE LOGIN LIBRARY FROM medieteknik.nu, though somewhat modified
 * found here:
 * https://github.com/medieteknik/Medieteknik.nu/blob/master/application/libraries/Login.php
 */


class Login
{
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
	}

	function is_logged_in() {
		return $this->CI->session->userdata('is_logged_in');
	}

	function is_admin() {
		if($this->is_logged_in() && $this->CI->session->userdata('privil') <= 4) {
			return true;
		}
		return false;
	}

	public function has_privilege($privileges) {
		if(!isset($privileges)) {
			return false;
		}

		if($this->is_logged_in()) {
			$id = $this->CI->session->userdata('id');
			$this->CI->load->model('User_model');

			return $this->CI->User_model->has_privilege($id, $privileges);
		}
		return false;
	}

	public function validate($name = '', $pwd = '') {
		$this->CI->load->model('User_model');
		$result = $this->CI->User_model->validate($name, $pwd);

		if($result) // if the user's credentials validated...
		{
			$data = array(
				'id' => $result->uid,
				'email' => $result->email,
				'name' => $result->name,
				'is_logged_in' => true
			);
			$this->CI->session->set_userdata($data);
			return true;
		}

		// incorrect username or password
		return false;
	}

	public function get_id() {
		return $this->CI->session->userdata('id');
	}

	public function get_info($what)
	{
		// inloggad?
		if(!$this->is_logged_in())
			return false;

		// ladda usermodel
		$this->CI->load->model('User_model');

		return $this->CI->User_model->get_info($this->get_id(), $what);
	}

	public function get_all_info()
	{
		// inloggad?
		if(!$this->is_logged_in())
			return false;

		// ladda usermodel
		$this->CI->load->model('User_model');

		return $this->CI->User_model->get_all_info($this->get_id());
	}

	public function logout() {
		$data = array(
			'id' => 0,
			'liuid' => "",
			'is_logged_in' => false,
			'privil' => false,
		);
		$this->CI->session->set_userdata($data);
		$this->CI->session->sess_destroy();
	}

	/**
	 * refresh info stored in session from database
	 */
	public function refresh()
	{
		$this->CI->load->model('User_model');
		$result = $this->CI->user_model->get_all_info($this->get_id());

		if($result) // if the database responend with something
		{
			$data = array(
				'email' => $result['email'],
				'name' => $result['name']
			);
			$this->CI->session->set_userdata($data);
			return true;
		}

		// incorrect username or password
		return false;
	}
}
