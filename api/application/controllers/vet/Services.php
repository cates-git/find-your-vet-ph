<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('vet/Services_model');
	}
	
	public function create()
	{
        $data = $this->validate();

        $this->Services_model->create($data);
        
		json_response(TRUE, 'Added successfully');
	}

	public function update()
	{
        $data = $this->validate(TRUE);

        $service_id = $this->input->post('id');
        $service = $this->Services_model->get(['id' => $service_id], 'file_name');
        
        $this->Services_model->update($data, $service_id);

		if (isset($data['file_name']) && $service && $service->file_name)
		{
			unlink('uploads/' . $service->file_name);
		}
        
		json_response(TRUE, 'Updated successfully');
	}

	public function delete($id)
	{
        $service = $this->Services_model->get(['id' => $id], 'file_name');
        
        $this->Services_model->delete($id);
		if ($service && $service->file_name && file_exists('uploads/' . $service->file_name))
		{
			unlink('uploads/' . $service->file_name);
		}
        
		json_response(TRUE, 'Deleted successfully');
	}

	private function validate($update = FALSE) 
	{        
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
        }
        
        
		$fields = ['name', 'description', 'price', 'promo'];
        $data = parse_data($_POST, $fields);

        if ($_FILES) {
            $file_name = upload_file();
            $data['file_name'] = $file_name;
        }

		return $data;
    }
    
    public function all()
    {
        $where = [];

        $user = $this->session->userdata('logged_in');
        
        $where['vet_id'] = $user['type'] == VETERINARIAN ? $user['id'] : $this->input->post('vet_id');
        $where['promo'] = $this->input->post('promo') ? $this->input->post('promo') : 0;

        $list = $this->Services_model->all($where);
        foreach ($list as $key => $value) {
            $list[$key]->file_url = $value->file_name && file_exists('uploads/'.$value->file_name) ? base_url('uploads/'.$value->file_name) : NULL;
        }
        
        json_response(TRUE, '', $list);
    }
    
    // public function list()
    // {
    //     $page = set_page($this->input->post('page'));
    //     $where = [];

    //     $user = $this->session->userdata('logged_in');
        
    //     $where['vet_id'] = $user['type'] == VETERINARIAN ? $user['id'] : $this->input->post('vet_id');

    //     $list = $this->Services_model->list($page, $where);
    //     foreach ($list as $key => $value) {
    //         $list[$key]->file_url = $value->file_name ? base_url('uploads/'.$value->file_name) : NULL;
    //     }
        
    //     $data = [
    //         'list'    => $list,
    //         'total'   => $this->Services_model->total($where)
    //     ];
        
    //     json_response(TRUE, '', $data);
    // }

}
?>