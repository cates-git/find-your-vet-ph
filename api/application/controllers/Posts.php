<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('Posts_model');
        $this->user = get_user_data();
	}
	
	public function create()
	{
        $data = $this->validate();

        $data['user_group_id'] = $this->input->post('user_group_id')
                            ? $this->input->post('user_group_id')
                            : $this->user['id'];
                            
        $this->Posts_model->create($data);
        
		json_response(TRUE, 'Posted successfully');
	}
	
	// public function update()
	// {
    //     $data = $this->validate(TRUE);

    //     $this->Posts_model->update($data, $this->input->post('id'));
        
	// 	json_response(TRUE, 'Updated successfully');
	// }

	private function validate($update = FALSE) 
	{        
        $text = $this->input->post('text');

        if ( ! $_FILES && ! $text) 
        {  
            json_response(FALSE, 'Fill in the empty fields');
        }

        $file_name = $_FILES ? upload_file() : '';
        
        $data = [
            'file_name' => $file_name,
            'text'      => $text
        ];

		return $data;
    }
    
    public function all()
    {
        $where = [];
        
        $where['user_group_id'] = $this->input->post('user_group_id')
                            ? $this->input->post('user_group_id')
                            : $this->user['id'];

        $list = $this->Posts_model->all($where);
        
        foreach ($list as $key => $value) 
        {
            $list[$key]->comments = $this->Posts_model->all_comments($value->id);
            $list[$key]->showComments = FALSE;
            // $list[$key]->total_comments = $this->Posts_model->total_comments($value->id);
            $avatar = 'uploads/' . $value->avatar;
            $list[$key]->avatar = $value->avatar && file_exists($avatar) ? base_url($avatar) : NULL;
            $file = 'uploads/' . $value->file_name;
            $list[$key]->file_url = $value->file_name && file_exists($file) ? base_url($file) : NULL;
        }
        
        json_response(TRUE, '', $list);
    }

    
	public function comment()
	{
        $fields = ['post_id', 'comment'];

        $data = parse_data($_POST, $fields);
                            
        $this->Posts_model->create_comment($data);
        
		json_response(TRUE, 'Commented successfully');
	}

    // public function comments($post_id)
    // {
    //     $list = $this->Posts_model->all_comments($post_id);
        
    //     json_response(TRUE, '', $list);
    // }
    // public function list()
    // {
    //     $page = set_page($this->input->post('page'));
    //     $where = [];
        
    //     $where['vet_id'] = $this->user['type'] == VETERINARIAN 
    //                         ? $this->user['id']
    //                         : $this->input->post('vet_id');

    //     $list = $this->Posts_model->list($page, $where);
    //     foreach ($list as $key => $value) 
    //     {
    //         $list[$key]->file_url = $value->file_name ? base_url('uploads/' . $value->file_name) : NULL;
    //     }
        
    //     $data = [
    //         'list'  => $list,
    //         'total' => $this->Posts_model->total($where)
    //     ];
        
    //     json_response(TRUE, '', $data);
    // }

}
?>