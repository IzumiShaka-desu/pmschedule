
<?php
class M_schedules extends CI_Model
{
	private $tableName = 'schedule';
	public function __construct()
	{
		$this->load->database();
	}

	public function get_schedules()
	{
		$this->db->from($this->tableName);
		$this->db->order_by('id_schedule', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_checksheet()
	{
		$this->db->from('checksheet');
		$this->db->order_by('id_checksheet', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_schedules_with_status()
	{
		$this->db->from($this->tableName);
		$this->db->order_by('id_schedule', 'DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		//iterate every item and get response object from response table
		foreach ($result as $key => $value) {
			$this->db->from('response');
			$this->db->where('note', "from-schedule-" . $value['id_schedule']);
			$this->db->order_by('id_response', 'DESC');
			$this->db->limit(1);
			$query = $this->db->get();
			$response = $query->row_array();
			$currentDate = date('Y-m-d');
			$dueDate = $value['date'];

			//check status [scheduled, missing, working, done, done early, done late]
			// if there is no response, then check if the schedule date is currentDate, passed, or future
			// else check if the response is have status draft if yes then check if the response date is passed or not
			// else check if the response is have status done if yes then check if the response date is match,early or late
			if (empty($response)) {
				if ($currentDate == $dueDate) {
					$result[$key]['status'] = 'working';
				} else if ($currentDate > $dueDate) {
					$result[$key]['status'] = 'missing';
				} else {
					$result[$key]['status'] = 'scheduled';
				}
			} else {
				$result[$key]['id_response'] = $response['id_response'];
				$responseUpdateDate = $response['last_update'];

				if (strtolower($response['status']) == 'draft') {
					if ($responseUpdateDate > $dueDate) {
						$result[$key]['status'] = 'working (late)';
					} else {
						$result[$key]['status'] = 'working';
					}
				} else if (strtolower($response['status']) == 'submit') {
					if ($responseUpdateDate == $dueDate) {
						$result[$key]['status'] = 'done';
					} else if ($responseUpdateDate < $dueDate) {
						$result[$key]['status'] = 'done early';
					} else {
						$result[$key]['status'] = 'done late';
					}
				}
			}
		}
		return $result;
	}

	public function getScheduleById($id)
	{
		$this->db->from($this->tableName);
		$this->db->where('id_schedule', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function set_schedules($id, $data)
	{
		//set ,nama_alat,pabrik,kapasistas,lokasi,no_seri,no_perijinan,expired_date
		//by id_schedule
		$this->db->where('id_schedule', $id);
		return $this->db->update($this->tableName, $data);
	}

	public function add_multiple_schedules($batchData)
	{
		//insert multiple data to database using json string

		$this->db->insert_batch($this->tableName, $batchData);

		// $this->db->insert_batch($this->tableName, $data);
		// $this->db->set($data);
		// $this->db->insert_batch($this->tableName, $data);
	}

	public function add_schedules($data)
	{
		//set ,nama_alat,pabrik,kapasistas,lokasi,no_seri,no_perijinan,expired_date
		//by id_schedule
		return $this->db->insert($this->tableName, $data);
	}

	public function delete_schedules($id)
	{
		//set ,nama_alat,pabrik,kapasistas,lokasi,no_seri,no_perijinan,expired_date
		//by id_schedule
		$this->db->where('id_schedule', $id);
		return $this->db->delete($this->tableName);
	}
	// public function flip_status($id)
	// {
	// 	//flip status if active then processing and vice versa
	// 	$this->db->where('id_schedule', $id);
	// 	$this->db->select('status');
	// 	$query = $this->db->get($this->tableName);
	// 	$result = $query->result_array();
	// 	$status = $result[0]['status'];
	// 	if ($status == 'active') {
	// 		$status = 'processing';
	// 	} else {
	// 		$status = 'active';
	// 	}
	// 	$this->db->where('id_schedule', $id);
	// 	$this->db->set('status', $status);
	// 	return $this->db->update($this->tableName);
	// }
	// public function get_data_for_exports()
	// {
	// 	$this->db->select('nama_alat as `Nama Alat`, pabrik_pembuat as `Pabrik Pembuat`, kapasitas as `Kapasitas`, lokasi as `Lokasi`, no_seri as `No Seri`, no_perijinan as `No Perijinan`, expired_date as Expired Date');
	// 	$this->db->from($this->tableName);
	// 	$this->db->order_by('id_schedule', 'DESC');
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }
	// public function setFilenameBy($id, $filename)
	// {
	// 	$this->db->where('id_schedule', $id);
	// 	$this->db->set('filename', $filename);
	// 	return $this->db->update($this->tableName);
	// }
	// public function produceExpiredScheduleSample()
	// {
	// 	//insert sample data with expired_date is 90 days from now
	// 	$expired_date = date('Y-m-d', strtotime('+90 days'));

	// 	$data = array(
	// 		'nama_alat' => 'Sample Alat',
	// 		'pabrik_pembuat' => 'Sample Pabrik',
	// 		'kapasitas' => 'Sample Kapasistas',
	// 		'lokasi' => 'Sample Lokasi',
	// 		'no_seri' => 'Sample No Seri',
	// 		'no_perijinan' => 'Sample No Perijinan',
	// 		'expired_date' => $expired_date,
	// 		'status' => 'active',
	// 		'filename' => 'sample.pdf'
	// 	);
	// 	return $this->db->insert($this->tableName, $data);
	// }
	// public function getSchedulesForReminders()
	// {
	// 	$expired_date_90d = date('Y-m-d', strtotime('+90 days'));
	// 	$expired_date_60d = date('Y-m-d', strtotime('+60 days'));
	// 	$expired_date_30d = date('Y-m-d', strtotime('+30 days'));
	// 	//select all ative schedules with expired_date is equal to 90 or equal to 60 or equal to 30 days from now
	// 	$this->db->from($this->tableName);
	// 	$this->db->where('status', 'active'); //only active schedules
	// 	$this->db->where('expired_date', $expired_date_90d); //expired_date is equal to 90 days from now
	// 	$this->db->or_where('expired_date', $expired_date_60d); //expired_date is equal to 60 days from now
	// 	$this->db->or_where('expired_date', $expired_date_30d); //expired_date is equal to 30 days from now		
	// 	$this->db->order_by('id_schedule', 'DESC');
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }
}
