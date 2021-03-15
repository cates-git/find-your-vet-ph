<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('pet_owner/Appointments_model');
	}
	
	public function create()
	{
        $data = $this->validate();

        $this->Appointments_model->create($data);
        
		json_response(TRUE, 'Booked successfully');
	}
	
	public function update()
	{
		$fields = ['status', 'remarks'];

		$data = parse_data($_POST, $fields);

        $this->Appointments_model->update($data, $this->input->post('id'));
        
		json_response(TRUE, 'Updated successfully');
	}

	private function validate($update = FALSE) 
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
        }
        
		$fields = ['date', 'time', 'reason', 'vet_id', 'pet_id'];

		$data = parse_data($_POST, $fields);

		$id = $update ? $this->input->post('id') : NULL;

		return $data;
    }
    
    public function list()
    {
        $user = get_user_data();
        $where = [];

        if ($user['type'] === VETERINARIAN) 
        {
            $where['a.vet_id'] = $user['id'];
        }
        else if ($user['type'] === PET_OWNER)
        {
            $where['p.pet_owner_id'] = $user['id'];
		}
        
        json_response(TRUE, '', $this->Appointments_model->list($where));
	}
	
	public function calendar ()
	{
        $user = get_user_data();
        $where = [];

        if ($user['type'] === VETERINARIAN) 
        {
            $where['a.vet_id'] = $user['id'];
        }
        else if ($user['type'] === PET_OWNER)
        {
            $where['p.pet_owner_id'] = $user['id'];
		}
		
		$dates = [];
		$list = $this->Appointments_model->list($where);
		$new_list = [];
		foreach ($list as $appointment) 
		{
			$dates[] = $appointment->date;
			$new_list[$appointment->date][] = $appointment;
		}

		$data = [
			'dates' => $dates,
			'list' => $new_list
		];
        json_response(TRUE, '', $data);
	}

}
?>