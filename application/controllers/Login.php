<?php
// create login controller
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_login');
		$this->load->helper('url_helper');
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->session->userdata('is_logged_in')) {
			redirect('');
		}
		$this->load->view('login_view');
	}

	public function validate()
	{
		//validate login
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		// $result = $this->m_login->validate($username, $password);
		// if ($result) {
		// 	//if login success
		// 	//set session
		// 	$session_data = array(
		// 		'username' => $username,
		// 		// 'name' => $result['name'],
		// 		'logged_in' => true
		// 	);
		// 	$this->session->set_userdata($session_data);
		// 	// redirect('');
		// 	echo '<script>  window.location.href = "' . base_url() . '"; </script>';
		// } else {
		// 	//if login failed
		// 	$this->session->set_flashdata('error', 'Invalid Username or Password');
		// 	redirect('login');
		// }
		$is_valid = $this->m_login->validate($username, $password);

		if ($is_valid)/*If valid username and password set */ {
			$get_id = $this->m_login->get_id($username, $password);

			foreach ($get_id as $val) {
				$name = $val->name;
				$password = $val->password;
				$level = $val->level;
				$id_user = $val->id_user;
				$id_department = $val->id_department;
				$id_section = $val->id_section;

				$data = array(
					'name' => $name,
					'password' => $password,
					'level' => $level,
					'is_logged_in' => true,
					'id_user' => $id_user,
					'id_department' => $id_department,
					'id_section' => $id_section
				);

				$this->session->set_userdata($data);
				redirect(base_url(''));
			}
		} else // incorrect username or password
		{
			$this->session->set_flashdata('msg1', 'Username or Password Incorrect!');
			redirect('login');
		}
	}

	public function logout()
	{
		// echo "Asdada";
		//clear all session
		// var_dump($this->session->userdata());

		$this->session->sess_destroy();
		// var_dump($this->session->userdata());

		redirect('login');
	}
}
