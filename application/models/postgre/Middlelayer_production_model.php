<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Middlelayer_production_model extends CI_Model
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
    public function get_programme_episodes($programme_id)
    {
		$this->r_db->select('programmes.id as programme_id, programme_details.language, programme_details.title, episodes.id as episodes_id');
		$this->r_db->join('programme_details', 'programme_details.programme_id = programmes.id', 'left');
		$this->r_db->join('episodes', 'episodes.programme_id = programmes.id', 'left');
		$this->r_db->where('programmes.id', $programme_id);
		$query = $this->r_db->get('programmes');
		//echo $this->r_db->last_query();
		return $query;
    }
}
