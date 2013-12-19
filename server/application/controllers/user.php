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
		// get all user data!
		$data = array();
		$data['user'] = $this->login->get_all_info();
		// set nice page title
		$data['title'] = 'Edit profile';

		$input = array(
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'oldpwd' => $this->input->post('password'),
					'new_password' => $this->input->post('new_password'),
					'new_confirm' => $this->input->post('new_confirm')
				 );

		// save user id
		$id = $this->login->get_id();

		// is form sent?
		if($input['oldpwd'])
		{
			// tell view we have tried to do something
			$data['sent'] = true;

			// is the confirmation pwd correct?
			if($this->user_model->validate($data['user']['email'], $input['oldpwd']))
			{
				// prepare array to use to send informatino to view about what has been changed
				$data['changes'] = array();

				// should we update the password?
				if(strlen($input['new_password']) > 0)
				{
					if($this->user_model->update_password($id, $input['new_password'], $input['new_confirm']))
						$data['changes']['newpwd'] = true;
					else
						$data['errors']['newpwd'] = true;
				}

				// should we update the email?
				if($input['email'] !== $data['user']['email'])
				{

					if(valid_email($input['email']) && !$this->user_model->email_exists($input['email']))
					{
						// update email
						$emailhash = $this->user_model->update_email($id, $input['email']);

						// se if it worked
						if($emailhash)
							$data['changes']['email'] = true;
						else
							$data['errors']['email'] = true;
					}
					else
						$data['errors']['email'] = true;
				}
				// should we update the name?
				if($input['name'] !== $data['user']['name'])
				{
					if(strlen($input['name']) > 1 && $this->user_model->update($id, array('name' => $input['name'])))
						$data['changes']['name'] = true;
					else
						$data['errors']['name'] = true;
				}

				// prepare sending email!
				if(count($data['changes']) > 0)
				{
					// update session storage
					$this->login->refresh();
					$data['newuser'] = $this->login->get_all_info();

					// time to send a email to the user confirmig the changes!
					// check what has been changed and add something about this to the email
					$message = '';

					if(isset($data['changes']['newpwd']) && $data['changes']['newpwd'])
					{
						$message .= '
								<p>
									Your password has been changed. If this wasn\'t done by you,
									head over to <a href="'.base_url().'user/reset">this page</a>
									to reclaim control over you account.
								</p>';
					}
					if(isset($data['changes']['email']) && $data['changes']['email'])
					{
						$message .= '
								<p>
									The email adress linked to you account has been changed. Therefore,
									you\'ll need to confirm your new email by clicking a link sent
									to that email, '.$input['email'].'.
								</p>';
					}
					if(isset($data['changes']['name']) && $data['changes']['name'])
					{
						$message .= '
								<p>
									The name related to you account has been changed.
								</p>';
					}

					// set message and stuff, using the format_mail from common_helper
					$message = '<p>
									Hi '.$data['newuser']['name'].',
								</p>
								<p>
									There has been some changes made to you account. Please review
									them below and make sure that everything is in order.
								</p>
								'.$message.'
								<p>
									If you didn\'t make these changes and you don\'t know who did,
									contact us immediately.
								</p>
								';
					$sendthis = format_mail('Account changes', $message);

					$this->email->from($this->config->item('noreply_mail'), $this->config->item('noreply_name'));
					$this->email->to($data['user']['email']);

					$this->email->subject($this->config->item('mail_title').'Changes were made to your Mote.fm account');
					$this->email->message($sendthis);

					// AWAY!
					$this->email->send();

					// debug
					//echo $this->email->print_debugger();

					// now, we'll check if we need to send the activation mail to the user once again
					if(isset($data['changes']['email']) && $data['changes']['email'])
					{
						// clear email class before sending new email
						$this->email->clear();

						// prepare message
						$message = '<p>
									Hi '.$data['newuser']['name'].',
								</p>
								<p>
									The email adress related to you account was just changed.
									It used to be '.$data['user']['email'].', and was changed to
									'.$data['newuser']['email'].'. We need you to confirm this
									address.
								</p>
								<p>
									All you need to do is click this button and follow the instructions:
									<a href="'.base_url().'user/activate/'.urlencode($data['newuser']['email']).'/'.$emailhash.'"
										class="button">Activate account!</a>
								</p>
								<p>
									<small>
										No button? Copy this link into you adress bar and hit enter:
										'.base_url().'user/activate/'.urlencode($data['newuser']['email']).'/'.$emailhash.'
									</small>
								</p>
								<p>
									If you didn\'t make these changes and you don\'t know who did,
									contact us immediately.
								</p>';
						$sendthis = format_mail('Confirm new email', $message);

						// to/from prop
						$this->email->from($this->config->item('noreply_mail'), $this->config->item('noreply_name'));
						$this->email->to($data['newuser']['email']);

						// set subject and message
						$this->email->subject($this->config->item('mail_title').'Confirm your new email');
						$this->email->message($sendthis);

						// AWAY!
						$this->email->send();
						// debug
						// echo $this->email->print_debugger();
					}

					// we no longer need old info from database, change it
					$data['user'] = $data['newuser'];
				}
			}
			else
			{
				$data['errors']['oldpwd'] = true;
			}
		}

		$data['input'] = $input;

		$this->load->view('templates/header', $data);
		$this->load->view('profile', $data);
		$this->load->view('templates/footer', $data);
	}

	public function signUp($method = 'web')
	{
		$data = array();

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if($name)
		{
			// validate email using CI magic && try signup
			if (valid_email($email) && $this->user_model->create_user($email, $name, $password))
			{
				$this->login->validate($email, $password);

				$hash = $this->user_model->createHash($email);

				//send activation email
				// prepare message
				$message = '<p>
							Hello!
						</p>
						<p>
							You just created a Mote.fm account. Awesome, you\'re one step closer
							to host the best, democratized party the world has ever seen. You can
							use your account for three days, but after that we need you to activate
							you account. Deal?
						</p>
						<p>
							All you need to do to activate you account is to click this button and follow the instructions:
							<a href="'.base_url().'user/activate/'.urlencode($email).'/'.$hash.'"
								class="button">Activate account!</a>
						</p>
						<p>
							<small>
								No button? Copy this link into you adress bar and hit enter:
								'.base_url().'user/activate/'.urlencode($email).'/'.$hash.'
							</small>
						</p>
						<p>
							If you didn\'t create this account, and don\'t know who did – please contact us!
						</p>';
				$sendthis = format_mail('Welcome!', $message);

				// to/from prop
				$this->email->from($this->config->item('noreply_mail'), $this->config->item('noreply_name'));
				$this->email->to($email);

				// set subject and message
				$this->email->subject($this->config->item('mail_title').'Welcome! Activate your account.');
				$this->email->message($sendthis);

				// AWAY!
				// $this->email->send();
				// debug
				// echo $this->email->print_debugger();

				// how do we want the response?
				if($method == 'web') // WEB!
				{
					echo "All right.";
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
		} // if (name)
		else
		{
			$this->load->view('templates/header', $data);
			$this->load->view('signup', $data);
			$this->load->view('templates/footer', $data);
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

			$logintry = $this->login->validate($email, $password);

			// kolla inloggning mot login lib
			if(is_array($logintry))
			{
				// does the user need to activate it's account?
				$data['activate'] = $logintry;
			}
			elseif($this->login->validate($email, $password))
			{
				redirect(urldecode($data['redir']), 'location');
				die();
			}

			// ajaj, fel!
			$data['email'] = $email;

			$this->load->view('templates/header', $data);
			$this->load->view('login', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
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
								<a href="'.base_url().'user/forgotpassword/'.urlencode($email).'/'.$hash.'" class="button">RESET PASSWORD!</a>
							</p>
							<p>
								<small>
									No button? Copy this link into you adress bar and hit enter:
									'.base_url().'user/forgotpassword/'.urlencode($email).'/'.$hash.'
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
				$data['email'] = $user['email'];
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
		// prepare data array
		$data = array();
		$data['email'] = urldecode($email);
		$data['hash'] = $hash;
		// get user id from user model using the validate_hash function
		$id = $this->user_model->validate_hash(urldecode($email), $hash);

		$newPassword = $this->input->post('password');
		$confirm = $this->input->post('confirm');

		if($id)
		{
			if($newPassword && $newPassword == $confirm)
			{
				$data['success'] = $this->user_model->update_password($id, $newPassword, $confirm);
			}
			else
			{
				$data['success'] = false;
			}
		}
		else
		{
			$data['user'] = false;
		}

		$this->load->view('templates/header', $data);
		$this->load->view('forgotpassword', $data);
		$this->load->view('templates/footer', $data);
	}

	public function resend()
	{
		// prepare data to send to view
		$data = array();

		$data['title'] = 'Resend activation link';

		// get email input from post
		$email = $this->input->post('email');

		if($email)
		{
			// get id from email
			$id = $this->user_model->get_id($email);

			// does the user exist?
			if ($id)
			{
				// get user info from id
				$user = $this->user_model->get_all_info($id);

				// get new hash for user
				$hash = $this->user_model->refresh_hash($email);

				$this->email->from($this->config->item('noreply_mail'), $this->config->item('noreply_name'));
				$this->email->to($email);

				// set message and stuff, using the format_mail from common_helper
				$message = '<p>
								Hi '.$user['name'].',
							</p>
							<p>
								We heard you needed a new activation link for your account. Here you go!
							</p>
							<p>
								All you need to do is click this button and follow the instructions:
								<a href="'.base_url().'user/activate/'.urlencode($email).'/'.$hash.'" class="button">Activate account!</a>
							</p>
							<p>
								<small>
									No button? Copy this link into you adress bar and hit enter:
									'.base_url().'user/activate/'.urlencode($email).'/'.$hash.'
								</small>
							</p>
							';
				$sendthis = format_mail('Activate your account', $message);

				$this->email->subject($this->config->item('mail_title').'Activate you Mote.fm account');
				$this->email->message($sendthis);

				// AWAY!
				$this->email->send();

				// debug
				// echo $this->email->print_debugger();

				$data['success'] = true;
				$data['email'] = $user['email'];
			}
			else
			{
				$data['success'] = false;
				$data['email'] = $email;
			}
		}

		$this->load->view('templates/header', $data);
		$this->load->view('resend', $data);
		$this->load->view('templates/footer', $data);
	}


	public function activate($email, $hashkey)
	{
		$data = array();
		$data['title'] = 'Activate account';

		$data['success'] = $this->user_model->activate(urldecode($email), $hashkey);

		$this->load->view('templates/header', $data);
		$this->load->view('activate', $data);
		$this->load->view('templates/footer', $data);
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
