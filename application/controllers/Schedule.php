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
		if (!$this->session->userdata('is_logged_in')) {
			redirect('login');
		}
		// det schedules data
		// $data['schedules'] = $this->m_schedules->get_schedules();
		$schedules = $this->m_schedules->get_schedules_with_status();
		$data['schedules'] = [];
		$data['rawSchedules'] = $schedules;
		foreach ($schedules as $key => $value) {
			$data['schedules'][] = [
				'id' => $value['id_schedule'],
				'title' => $value['description'],
				'start' => $value['date'],
				'status' => $value['status'],
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
	public function redirect($base64url)
	{
		$url = base64_decode($base64url);
		echo "<script>window.location.href='$url'</script>";
	}
	public function add()
	{

		// check if is POST request
		// then insert data to database
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			//get json data the parse to array and save with add_multiple_schedules
			$json = file_get_contents('php://input');
			$dates = [];
			$data = json_decode($json, true);
			//convert $data['date'] to object date
			foreach ($data as $key => $value) {
				$data[$key]['date'] = date('Y-m-d', strtotime($value['date']));
				$dates[] = $data[$key]['date'];
			}

			$result = $this->m_schedules->add_multiple_schedules($data);

			return $dates;
		}

		// if user not loggin redirect to login page
		if (!$this->session->userdata('is_logged_in')) {
			redirect('login');
		}
		// get schedules data
		$schedules = $this->m_schedules->get_schedules_with_status();
		$data['schedules'] = [];
		$data['rawSchedules'] = $schedules;
		foreach ($schedules as $key => $value) {
			$data['schedules'][] = [
				'id' => $value['id_schedule'],
				'title' => $value['description'],
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
	public function reschedule()
	{
		// check if is POST request
		// then insert data to database
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			//get json data the parse to array and save with add_multiple_schedules
			$id = $this->input->post('id');
			$date = $this->input->post('date');
			$result = $this->m_schedules->reschedule($id, $date);
			// return $result;
			// if success return success message else return error message and 500 status code
			if ($result) {
				return $this->output
					->set_status_header(200)
					->set_content_type('application/json')
					->set_output(json_encode(['message' => 'success reschedule', 'data' => $result]));
			} else {
				return $this->output
					->set_status_header(500)
					->set_content_type('application/json')
					->set_output(json_encode(['message' => 'error reschedule']));
			}
		}
	}
}
