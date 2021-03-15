<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rate extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('vet/Rate_model');
	}
	
	public function create()
	{
        $data = $this->validate();

        $this->Rate_model->create($data);
        
		json_response(TRUE, 'Rated successfully');
	}

	private function validate($update = FALSE) 
	{
		$fields = ['vet_id', 'appointment_id'];

		$data = parse_data($_POST, $fields);

		$data['rating']  = $this->input->post('rating');
		$data['comment'] = $this->input->post('comment');

		$id = $update ? $this->input->post('id') : NULL;

		if($this->Rate_model->is_rated($data['appointment_id'], $id))
		{
			json_response(FALSE, 'Appointment already rated.');
		}

		return $data;
    }
    
    public function get($appointment_id)
    {
        $data = $this->Rate_model->get($appointment_id);
        json_response(TRUE, '', $data);
	}
	
    public function all()
    {
		$where['vet_id'] = get_user_type() == VETERINARIAN 
				? get_user_data()['id'] 
				: $this->input->post('vet_id');

		$data = $this->Rate_model->all($where);
		
        json_response(TRUE, '', $data);
    }

}
?>