<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class User_model extends CI_model
{
	function __construct()
	{
		// call model constructor
		parent::__construct();

		// load CI to use some helper functions
		$CI =& get_instance();
	}


	/**
	 * check if login credentials are correct
	 */
	function validate($email, $pwd)
	{
		$this->db->select("uid, password, email");
		$this->db->where('email', $email);
		// password query
		$pwq = $this->db->get("users");
		$pwr = $pwq->result(); // password result

		if($this->passwordhash->CheckPassword($pwd, $pwr[0]->password))
		{
			return $pwr[0];
		}
		return false;
	}

	/**
	 * get certain user info, such as name or other
	 */
	function get_info($uid, $what)
	{
		$this->db->select($what);
		$this->db->where('uid', $uid);
		$query = $this->db->get('users');

		$result = $query->result();

		if($query->num_rows() == 1)
		{
			return $result[0]->$what;
		}

		return false;
	}

	/**
	 * create a new user
	 */
	function create_user($email, $name, $password)
	{
		if(!empty($email) && !empty($name) && strlen($password) > 6
			&& !$this->email_exists($email))
		{
			$data = array(
						'name'		=> $name,
						'password'	=> $this->passwordhash->HashPassword($password),
						'email'		=> $email,
						'hashkey'	=> strgen(20)
					);
			return $this->db->insert('users', $data);
		}
		return false;
	}

	/**
	 * set user privil
	 */
	function change_privil($uid, $privil)
	{
		// highest privil is 4
		$privil = $privil > 4 ? 4 : $privil;

		$this->db->select("uid");
		$this->db->where("uid", $uid);
		$query = $this->db->get("admin");

		// create privil entry in db
		if($query->num_rows() == 0)
		{
			return $this->db->insert("admin", array("uid" => $uid, "privil" => $privil));
		}
		else // update existing
		{
			return $this->db->update("admin", array("privil" => $privil), array("uid" => $uid));
		}

		return false;
	}

	function edit_user($id, $email, $fname, $sname, $password, $privil)
	{
		if(!empty($email) || empty($fname) || empty($fname))
			return false;

		$data = array(
					'email' => $email,
					'fname' => $fname,
					'sname' => $sname
				);

		if(strlen($password) >= 6)
			$data['password'] = $this->passwordhash->HashPassword($password);

		return $this->change_privil($id, $privil) && $this->db->update('users', $data, array("uid" => $id));
	}

	/**
	 * check if a email exist in the database
	 */
	function email_exists($email)
	{
		$this->db->where('email', $email);
		$this->db->limit('1');
		$query = $this->db->get('users');

		return $query->num_rows();
	}

	/**
	 * get id from email
	 */
	function get_id($email)
	{
		$this->db->select("uid");
		$this->db->where('email', $email);
		$this->db->limit('1');
		$query = $this->db->get('users');
		//echo $query->num_rows();
		if($query && $query->num_rows() > 0)
		{

			$result = $query->result();

			var_dump($result);
			return $result[0]->uid;
		}

		return false;
	}

	function user_exist($id)
	{
		$this->db->select('uid');
		$this->db->where('uid', $id);
		$query = $this->db->get('users');

		if($query) return $query->num_rows();


		return false;
	}

	/**
	 * update password, given uid and new password
	 */
	function update_password($uid, $password, $confirm)
	{
		if(($password == $confirm) && strlen($password) > 6)
		{
			$password = array('password' => $this->passwordhash->HashPassword($password));

			return $this->db->update('users', $password, array('uid' => $uid));
		}
		return false;
	}

	function get_all()
	{
		$this->db->select("users.*, admin.privil");
		$this->db->from("users");
		$this->db->join("admin", "users.uid = admin.uid", "left");
		$this->db->group_by("users.uid");
		$this->db->order_by("users.uid");

		$result = $this->db->get();

		if($result->num_rows() > 0)
			return $result->result_array();

		return false;
	}


	function get_name($uid)
	{
		$this->db->select("fname, sname");
		$this->db->where('uid', $uid);
		$query = $this->db->get("users");
		$result = $query->result();

		if($query)
			return $result[0]->fname.' '.$result[0]->sname;

		return false;
	}

	/**
	 * get all info about a user
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_user($id)
	{
		$this->db->select("users.*, admin.privil");
		$this->db->from("users");
		$this->db->join("admin", "users.uid = admin.uid", "left");
		$this->db->group_by("users.uid");
		$this->db->where('users.uid', $id);

		$query = $this->db->get();
		$result = $query->result_array();

		if($query) return $result[0];

		return false;
	}

	function reset($email)
	{
		$data = array('hashkey' => strgen(20));
		if($this->db->update('users', $data, array('email' => $email))
		{
			return $data['hashkey'];
		}
		return false;


	}
}
