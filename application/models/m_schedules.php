
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
		//check every item status [done, working, not started]
		//by check responds from table respose
		//if there is a response with submit status is done
		//if there is a response iwth draft status or no response but the date is same as current date schedule is working 
		//else not started
		foreach ($result as $key => $value) {
			$this->db->from('response');
			$this->db->where('id_checksheet', $value['id_checksheet']);
			$this->db->where('CAST(date as DATE)=', $value['date']);
			$this->db->where('note', '%schedule%');
			$this->db->where('status', 'submit');
			$query = $this->db->get();
			$done = $query->result_array();
			if (count($done) > 0) {
				$result[$key]['status'] = 'done';
			} else {
				$this->db->from('response');
				$this->db->where('id_checksheet', $value['id_checksheet']);
				$this->db->where('status', 'draft');
				$this->db->where('note', '%pmschedule%');
				$query = $this->db->get();
				$draft = $query->result_array();
				if (count($draft) > 0) {
					$result[$key]['status'] = 'working';
				} else {
					$this->db->from('response');
					//where id checksheet same and where $value['date'](date) same as  date(datetime) 
					$this->db->where('CAST(date as DATE)=', $value['date']);
					$this->db->where('id_checksheet', $value['id_checksheet']);
					$this->db->where('note', '%pmschedule%');
					$query = $this->db->get();
					$no_response = $query->result_array();
					if (count($no_response) > 0) {
						$result[$key]['status'] = 'working';
					} else {
						$result[$key]['status'] = 'not started';
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
