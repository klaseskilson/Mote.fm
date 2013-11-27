<?php

class GetUser extends CI_Controller {

	public function index()
	{
		$uid = $this->input->post('userId');

		$this->load->model('user_model');
		if($this->user_model->user_exist($uid) != false)
		{
			echo $this->user_model->get_info($uid, 'name');	
		}
		else
		{
			echo $uid;
		}
	}
}
 ?>