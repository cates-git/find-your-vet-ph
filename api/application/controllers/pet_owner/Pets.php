<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pets extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('pet_owner/Pets_model');
	}
	
	public function create()
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

		$data = $this->validate();
		
        $this->Pets_model->create($data);
        
		json_response(TRUE, 'Added successfully');
	}
	
	public function update()
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

		$data = $this->validate(TRUE);
		$pet_id = $this->input->post('id');
		
		$pet = $this->Pets_model->get($pet_id, 'file_name');

		$this->Pets_model->update($data, $pet_id);
		if (isset($data['file_name']) && $pet && file_exists('uploads/'.$pet->file_name))
		{
			unlink('uploads/' . $pet->file_name);
		}
        
		json_response(TRUE, 'Updated successfully');
	}

	private function validate($update = FALSE) 
	{
		$fields = ['name', 'description', 'type', 'breed'];

		$data = parse_data($_POST, $fields);

		$id = $update ? $this->input->post('id') : NULL;

		if($this->Pets_model->is_name_exists($data['name'], $id))
		{
			json_response(FALSE, 'Pet name already exists.');
		}

		if ($_FILES) {
			$data['file_name'] = upload_file();
		}

		return $data;
    }
    
    // public function list()
    // {
	// 	$user = $this->session->userdata('logged_in');
	// 	$page = set_page($this->input->post('page'));
	// 	$where = [];

	// 	$where['pet_owner_id'] = $user['type'] == PET_OWNER 
	// 		? $user['id'] 
	// 		: $this->input->post('pet_owner_id');

	// 	$list = $this->Pets_model->list($page, $where);
	// 	foreach ($list as $key => $value) 
	// 	{
	// 		$list[$key]->file_url = $value->file_name ? base_url('uploads/'.$value->file_name) : NULL;
	// 	}
        
    //     $data = [
    //         'list' => $list,
    //         'total' => $this->Pets_model->total($where)
    //     ];
        
    //     json_response(TRUE, '', $data);
	// }
	
	public function all()
	{
		$user = $this->session->userdata('logged_in');
		$where = [];

		$where['pet_owner_id'] = $user['type'] == PET_OWNER 
			? $user['id'] 
			: $this->input->post('pet_owner_id');
        
		$list = $this->Pets_model->all($where);
		foreach ($list as $key => $value) 
		{
			$list[$key]->file_url = $value->file_name ? base_url('uploads/'.$value->file_name) : NULL;
		}
        
        json_response(TRUE, '', $list);
	}

}
?>