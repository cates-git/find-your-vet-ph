<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }
    
    public function list_by_specialties($keyword)
    {
        $search = $this->set_like_by_specialty($keyword);

        $this->db->group_start();
        foreach ($search['like'] as $like) {
            $this->db->or_like($like);
        }
        $this->db->group_end();

        return $this->db->select('s.*, v.id, CONCAT(v.first_name, " ", v.last_name) AS vet_name, v.address, v.email, v.avatar')
            ->from(TBL_USERS . ' AS v')
            ->join(TBL_VET_SPECIALTIES . ' AS s', 's.vet_id = v.id', 'LEFT')
            ->join(TBL_VET_REGISTRATION . ' AS r', 'r.vet_id = v.id', 'LEFT')
            ->where('r.expiration_time >', 'NOW()')
            ->where('v.type', VETERINARIAN)
            ->order_by("(".implode('+', $search['order']).")", 'DESC')
            ->group_by('s.vet_id')
            ->get()
            ->result();
    }
    
    // public function total_by_specialties($keyword)
    // {
    //     $search = $this->set_like_by_specialty($keyword);

    //     $this->db->group_start();
    //     foreach ($search['like'] as $like) {
    //         $this->db->or_like($like);
    //     }
    //     $this->db->group_end();

    //     $query = $this->db->select('count(s.id) AS total')
    //         ->from(TBL_VET_SPECIALTIES . ' AS s')
    //         ->join(TBL_USERS . ' AS v', 's.vet_id = v.id', 'LEFT')
    //         ->order_by("(".implode('+', $search['order']).")", 'DESC')
    //         ->group_by('s.vet_id')
    //         ->get();
    //     return (int) $query->row()->total;
    // }

    private function set_like_by_specialty($keyword)
    {
        $like = [];
        $order_by = [];
        
        $words = explode(' ', $keyword);

        $search_fields = ['s.name', 's.description', 'v.first_name', 'v.last_name', 'v.address'];
        
        foreach ($search_fields as $field)
        {
           foreach ($words as $word)
            {
                $like[] = [$field => $word];
                $order_by[] = "(case when {$field} like '%{$word}%' then 1 else 0 end)";
            }
 
        }
        
        return [
            'like' => $like,
            'order'  => $order_by
        ];
    }

}
?>