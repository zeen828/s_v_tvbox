<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Vidol_production_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('postgre_production_read', TRUE);
        $this->w_db = $this->load->database('postgre_production_write', TRUE);
    }
    
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }

    public function get_votes_count ($category_no)
    {
    	$this->r_db->select('SUM(ticket) as tickets');
    	$this->r_db->where('category_no', $category_no);
        $query = $this->r_db->get('votes');
        if($query->num_rows() > 0){
        	$row = $query->row();
        	return $row->tickets;
        }else{
        	return 0;
        }
    }
    
    // 取得回應
    public function get_votes ($category_no)
    {
    	$this->r_db->select('video_id_no, SUM(ticket) as tickets');
        $this->r_db->where('category_no', $category_no);
        $this->r_db->group_by('video_id_no');
        $this->r_db->order_by('video_id_no', 'ASC');
        $query = $this->r_db->get('votes');
        return $query;
    }
}
