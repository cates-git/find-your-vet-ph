<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('vet/Documents_model');
	}
	
	public function create()
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

        $data = $this->validate();

        $this->Documents_model->create($data);
        
		json_response(TRUE, 'Added successfully');
	}
	
	public function update()
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

        $data = $this->validate(TRUE);
        $doc_id = $this->input->post('id');
        $doc = $this->Documents_model->get(['id' => $doc_id], 'file_name');
        
        $this->Documents_model->update($data, $doc_id);

		if (isset($data['file_name']) && $doc && file_exists('uploads/' . $doc->file_name))
		{
			unlink('uploads/' . $doc->file_name);
		}
        
		json_response(TRUE, 'Updated successfully');
	}

	public function delete($id)
	{
        $doc = $this->Documents_model->get(['id' => $id], 'file_name');
        
        $this->Documents_model->delete($id);

		if ($doc && $doc->file_name && file_exists('uploads/' . $doc->file_name))
		{
			unlink('uploads/' . $doc->file_name);
		}
        
		json_response(TRUE, 'Deleted successfully');
    }
    
	private function validate($update = FALSE) 
	{
        if ($_FILES || !$update)
        {
            $data['file_name'] = upload_file('gif|jpg|jpeg|png|doc|docx|pdf');
        }
        
        $data['description'] = $this->input->post('description');

		return $data;
    }
    
    public function all()
    {
        $where = [];

        $user = get_user_data();

        $where['vet_id'] = $user['type'] == VETERINARIAN 
                            ? $user['id'] : 
                            $this->input->post('vet_id');

        $list = $this->Documents_model->all($where);
        foreach ($list as $key => $value) 
        {
            $list[$key]->file_url = base_url('uploads/'.$value->file_name);
        }
        
        json_response(TRUE, '', $list);
    }
    
    public function list()
    {
        $page = set_page($this->input->post('page'));
        $where = [];

        $user = $this->session->userdata('logged_in');

        $where['vet_id'] = $user['type'] == VETERINARIAN 
                            ? $user['id'] : 
                            $this->input->post('vet_id');

        $list = $this->Documents_model->list($page, $where);
        foreach ($list as $key => $value) 
        {
            $list[$key]->file_url = base_url('uploads/'.$value->file_name);
        }
        
        $data = [
            'list'    => $list,
            'total'   => $this->Documents_model->total($where)
        ];
        
        json_response(TRUE, '', $data);
    }

}
?>