<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialties extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('vet/Specialties_model');
	}
	
	public function create()
	{
        $data = $this->validate();

        $this->Specialties_model->create($data);
        
		json_response(TRUE, 'Added successfully');
	}

	public function update()
	{
        $data = $this->validate(TRUE);

        $this->Specialties_model->update($data, $this->input->post('id'));
        
		json_response(TRUE, 'Updated successfully');
	}

	public function delete($id)
	{
        $this->Specialties_model->delete($id);
        
		json_response(TRUE, 'Deleted successfully');
	}

	private function validate($update = FALSE) 
	{
		$fields = ['name', 'description'];

		$data = parse_data($_POST, $fields);

		$id = $update ? $this->input->post('id') : NULL;

		if($this->Specialties_model->is_name_exists($data['name'], $id))
		{
			json_response(FALSE, 'Name already exists.');
		}

		return $data;
    }
    
    public function list()
    {
		$user = get_user_data();
		$where['vet_id'] = (int) $user['type'] === VETERINARIAN 
						? $user['id']
						: $this->input->post('vet_id');
        $data = [
            'list' => $this->Specialties_model->list($where)
        ];
        
        json_response(TRUE, '', $data);
    }

}
?>