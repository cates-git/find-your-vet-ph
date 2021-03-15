<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Contact_us_model');
	}
	
	public function send_message ()
	{
        $data = $this->validate();

        $this->Contact_us_model->create($data);
        
		json_response(TRUE, 'Sent successfully');
    }
    
	private function validate($update = FALSE) 
	{        
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
        }
        
        $data = [
            'email'   => $this->input->post('email'),
            'message' => $this->input->post('message')
        ];

		return $data;
    }
    
    public function list()
    {
        $page = set_page($this->input->post('page'));
        $where = [];

        $data = [
            'list'    => $this->Contact_us_model->list($page, $where),
            'total'   => $this->Contact_us_model->total($where)
        ];
        
        json_response(FALSE, '', $data);
    }

}
?>