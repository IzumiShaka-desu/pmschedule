<?php
// create login model
class M_login extends CI_Model
{
	public function __construct()
	{
		//if user has logged in then redirect to home


		$this->load->database();
	}

	// public function validate($username, $password)
	// {
	// 	//validate login
	// 	$this->db->where('username', $username);
	// 	$this->db->where('password', $password);
	// 	$query = $this->db->get('users');
	// 	if ($query->num_rows() == 1) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	/**
	 * Validate the login's data with the database
	 * @param string $username
	 * @param string $password
	 * @return void
	 */

	/*Check Login*/
	function validate($username, $password)
	{
		$this->db->where('password', $password);
		$this->db->where('username', $username);

		$query = $this->db->get('user');
		return $query->result();
	}

	/*Get Session values */

	function get_id($username, $password)
	{
		$this->db->where('password', $password);
		$this->db->where('username', $username);
		return $this->db->get('user')->result();
	}

	function getName($id)
	{
		return $this->db->get_where('user', array('id_user' => $id))->result_array();
	}
}
