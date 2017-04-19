<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Ovo_ad_model extends CI_Model {
	private $table_name = 'OVO_ad_tbl';
	private $fields_pk = 'oa_pk';
	public function __construct() {
		parent::__construct ();
		// $this->load->config('set/databases_fiels', TRUE);
		$this->r_db = $this->load->database ( 'vidol_dealer_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_dealer_write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	public function insert_OVO_ad_for_data($data) {
		$this->w_db->insert ( $this->table_name, $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	public function update_OVO_ad_for_data($pk, $data) {
		$this->w_db->where ( $this->fields_pk, $pk );
		$this->w_db->update ( $this->table_name, $data );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
	public function get_row_OVO_ad_by_pk($select, $pk) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		$this->r_db->where ( $this->fields_pk, $pk );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		if ($query->num_rows () > 0) {
			return $query->row ();
		}
		return false;
	}
	public function get_OVO_ad_by_status($select) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		$this->r_db->where ( 'oa_status', '1' );
		$this->r_db->order_by( 'oa_sort', 'ASC' );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		return $query;
	}
}
