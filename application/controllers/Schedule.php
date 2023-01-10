<?php
class Schedule extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('m_schedules');
		$this->load->helper('url_helper');
	}
	public function index()
	{
		// if user not loggin redirect to login page
		// if (!$this->session->userdata('logged_in')) {
		// 	redirect('login');
		// }
		// det schedules data
		// $data['schedules'] = $this->m_schedules->get_schedules();
		$schedules = $this->m_schedules->get_schedules_with_status();
		$data['schedules'] = [];
		$data['rawSchedules'] = $schedules;
		foreach ($schedules as $key => $value) {
			$data['schedules'][] = [
				'title' => $value['name'],
				'start' => $value['date'],
				'backgroundColor' => $value['status'] == 'done' ? 'green' : ($value['status'] == 'working' ? 'yellow' : 'red'),
				'borderColor' => $value['status'] == 'done' ? 'green' : ($value['status'] == 'working' ? 'yellow' : 'red'),
			];
		}
		$this->load->view('templates/header');
		$this->load->view('templates/nav');
		$this->load->view('index', $data);
		$this->load->view('templates/footer', $data);
		// $this->load->view('index');
		// echo 'Hello World!';
	}
	public function add()
	{
		// if user not loggin redirect to login page
		// if (!$this->session->userdata('logged_in')) {
		// 	redirect('login');
		// }
		// check if is POST request
		// then insert data to database
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			//get json data the parse to array and save with add_multiple_schedules
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			$result = $this->m_schedules->add_multiple_schedules($data);

			return $result;
		}


		// get schedules data
		$schedules = $this->m_schedules->get_schedules_with_status();
		$data['schedules'] = [];
		$data['rawSchedules'] = $schedules;
		foreach ($schedules as $key => $value) {
			$data['schedules'][] = [
				'title' => $value['name'],
				'start' => $value['date'],
				'backgroundColor' => $value['status'] == 'done' ? 'green' : ($value['status'] == 'working' ? 'yellow' : 'red'),
				'borderColor' => $value['status'] == 'done' ? 'green' : ($value['status'] == 'working' ? 'yellow' : 'red'),
			];
		}
		// $data['schedules'] = [[
		// 	'title' => 'All Day Event',
		// 	'start' => '2023-01-01'
		// ]];
		$checksheet = $this->m_schedules->get_checksheet();
		$data['checksheet'] =  [];
		foreach ($checksheet as $key => $value) {
			$data['checksheet'][] = [
				'id' => $value['id_checksheet'],
				'name' => $value['title'],
			];
		}
		// $data['checksheet'] = array(
		// 	array('id' => 1, 'name' => 'Checksheet 1'),
		// 	array('id' => 2, 'name' => 'Checksheet 2'),
		// 	array('id' => 3, 'name' => 'Checksheet 3'),
		// );
		$this->load->view('templates/header');
		$this->load->view('templates/nav');
		$this->load->view('add', $data);
		$this->load->view('templates/footer', $data);
		// $this->load->view('index');
		// echo 'Hello World!';
	}
}
