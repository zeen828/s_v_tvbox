<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Vidol_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('postgre_read', TRUE);
        $this->w_db = $this->load->database('postgre_write', TRUE);
    }
    
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }

    // 取得回應
    public function get_oauth_access_tokens_by_token($token)
    {
		$this->r_db->select('*, now() as now_date');
		$this->r_db->where('token', $token);
		$query = $this->r_db->get('oauth_access_tokens');
		//echo $this->r_db->last_query();
		return $query;
    }
}
