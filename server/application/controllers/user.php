<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// make sure that the user is logged in. Redirect to login page if not.
		if(!$this->login->is_logged_in() && $this->uri->segment(2) == 'profile')
		{
			redirect('/user/signin?redir='.urlencode(uri_string()));
		}

		$this->load->model("user_model");

		// load password hash library
		$this->load->library('PasswordHash', array(8, false));
		// load email helper and library for validating, sending & recieving mail
		$this->load->library('email');
		$this->load->helper('email');
	}

	public function index()
	{
		if($this->login->is_logged_in())
			$this->profile();
		else
			$this->signin();
	}

	public function profile()
	{
		$data = array();
		$data['user'] = $this->login->get_all_info();
		$data['title'] = 'Edit profile';

		$this->load->view('templates/header', $data);
		$this->load->view('profile', $data);
		$this->load->view('templates/footer', $data);
	}

	public function signUp($method = 'web')
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		// validate email using CI magic && try signup
		if (valid_email($email) && $this->user_model->create_user($email, $name, $password))
		{
			$this->login->validate($email, $password);

			//send activation email
			$hash = $this->user_model->createHash($email);
			$this->email->from('noreply@taketkvg.se', 'The Hathor crew');
			$this->email->to($email);
			$this->email->subject('Activate your Hathor account');
			$this->email->message('Hey '.$name.'! Follow this link to activate your Hathor account. '
			.base_url().'user/activate/'.urlencode($email).'/'.$hash);
			// $this->email->send();

			// how do we want the response?
			if($method == 'web') // WEB!
			{
				echo "Well done my kuk.";
				echo $this->email->print_debugger();
			}
			elseif($method == 'json') // return with machine encoded json
			{
				$response = array(
								'status' => 'success',
								'maildebug' => $this->email->print_debugger()
						   );
				echo json_encode($response);
			}
		}
		else // something wrong!
		{
			// how do we want the response?
			if($method == 'web')
			{
				echo "ajaj";
			}
			elseif($method == 'json')
			{
				// return response using json! prepare data
				$response = array(
								'status' => 'error',
								'errors' => array(
												'name'	 => (strlen($name) > 2),
												'email'	 => valid_email($email) && !$this->user_model->email_exists($email),
												'password' => !(strlen($password) <= 6)
											)
						    );
				echo json_encode($response);
			}
		}
	}

	public function signout()
	{
		$this->login->logout();
		redirect('/home/start', 'location');
	}

	public function signin()
	{
		// prepare data array for view
		$data = array();

		// redir get query sent?
		$data['redir'] = isset($_GET['redir']) ? urlencode($_GET['redir']) : base_url();

		// have the user submited anything?
		if($this->input->post('submit'))
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			// kolla inloggning mot login lib
			if($this->login->validate($email, $password))
			{
				redirect(urldecode($data['redir']), 'location');
			}
			else
			{
				// ajaj, fel!
				$data['title'] = 'Sign in!';
				$data['email'] = $email;

				$this->load->view('templates/header', $data);
				$this->load->view('login', $data);
				$this->load->view('templates/footer', $data);
			}
		}
		else
		{
			$data['title'] = 'Sign in!';

			$this->load->view('templates/header', $data);
			$this->load->view('login', $data);
			$this->load->view('templates/footer', $data);
		}

	}
	public function reset()
	{
		// prepare data to send to view
		$data = array();

		$data['title'] = 'Reset password!';

		// get email input from post
		$email = $this->input->post('email');

		if($email)
		{
			// get id from email
			$id = $this->user_model->get_id($email);
			// get user info from id
			$user = $this->user_model->get_all_info($id);

			// did get_all_info return anything? proceed.
			if ($id)
			{
				$hash = $this->user_model->reset($email);

				$this->email->from($this->config->item('noreply_mail'), $this->config->item('noreply_name'));
				$this->email->to($email);

				// set message and stuff, using the format_mail from common_helper
				$message = '<p>
								Hi '.$user['name'].',
							</p>
							<p>
								We heard you forgot your password, and therefore we prepared this awesome link for you, so that
								you can reset it and access you account again. Nice, right?
							</p>
							<p>
								All you need to do is click this button and follow the instructions:
								<a href="'.base_url().'/'.urlencode($email).'/'.$hash.'" class="button">RESET PASSWORD!</a>
							</p>
							<p>
								<small>
									No button? Copy this link into you adress bar and hit enter: '.base_url().'/'.urlencode($email).'/'.$hash.'
								</small>
							</p>
							';
				$sendthis = format_mail('Reset password', $message);

				$this->email->subject($this->config->item('mail_title').'Reset password');
				$this->email->message($sendthis);

				// AWAY!
				$this->email->send();

				// debug
				//echo $this->email->print_debugger();

				$data['success'] = true;
				$data['email'] = $user['user'];
			}
			else
			{
				$data['success'] = false;
				$data['email'] = $email;

			}
		}

		$this->load->view('templates/header', $data);
		$this->load->view('reset', $data);
		$this->load->view('templates/footer', $data);
	}
	public function forgotpassword($email, $hash)
	{
		$newPassword = $this->input->post('newPassword');
		$confirm = $this->input->post('confirm');
		$id = $this->user_model->get_id($email);

		if(!($this->user_model->update_password($id, $newPassword, $confirm)))
		{
			echo "Something went wrong.";
		}
		else
		{
			echo "Your password has been reset.";
		}
	}

	public function activate($email, $hashkey)
	{
		if (!($this->user_model->activate(urldecode($email), $hashkey)))
		{
			echo "Error";
		}
		else
		{
			echo "User activated";
		}
	}

	public function changePassword()
	{
		$email = $this->input->post('email');
		$id = $this->user_model->get_id($email);
		$oldPassword = $this->input->post('oldPassword');
		$newPassword = $this->input->post('newPassword');
		$confirmPassword = $this->input->post('confirmPassword');

		if(!($this->user_model->update_password($id, $newPassword, $confirmPassword)))
		{
			echo "Something went wrong.";
		}
		else
		{
			echo "Password has been changed.";
			$this->email->from('noreply@taketkvg.se', 'The Hathor crew');
			$this->email->to($email);
			$this->email->subject('Change of password');
			$this->email->message('Hey! Your Hathor password has been changed.');
			$this->email->send();
		}
	}
}
