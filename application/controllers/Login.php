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
		if ($this->session->userdata('logged_in')) {
			redirect('');
		}
		$this->load->view('login_view');
	}

	public function validate()
	{
		//validate login
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$result = $this->m_login->validate($username, $password);
		if ($result) {
			//if login success
			//set session
			$session_data = array(
				'username' => $username,
				// 'name' => $result['name'],
				'logged_in' => true
			);
			$this->session->set_userdata($session_data);
			// redirect('');
			echo '<script>  window.location.href = "' . base_url() . '"; </script>';
		} else {
			//if login failed
			$this->session->set_flashdata('error', 'Invalid Username or Password');
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
