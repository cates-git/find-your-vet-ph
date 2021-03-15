<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->load->model('vet/Search_model');
        is_logged_in();
    }
    
    public function index()
    {
        $keyword = $this->input->post('keyword');

        $vet_by_specialty = $this->Search_model->list_by_specialties($keyword);

        foreach ($vet_by_specialty as $key => $value) 
        {
            $vet_by_specialty[$key]->avatar = $value->avatar ? base_url('uploads/'.$value->avatar) : NULL;

            $this->load->model('vet/Rate_model');
            $where['vet_id'] = $value->vet_id;
            $ratings = $this->Rate_model->all($where);
            $vet_by_specialty[$key]->rating = $ratings ? array_sum(array_column($ratings, 'rating')) / count($ratings) : 0;
        }
        
        $data = [
            'list' => $vet_by_specialty
        ];
        
        json_response(TRUE, '', $data);
    }

}
?>