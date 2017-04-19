<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class User_binding_log_model extends CI_Model {
	private $table_name = 'User_binding_log_tbl';
	private $fields_pk = 'ubl_pk';
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
	public function insert_User_binding_log_for_data($data) {
		$this->w_db->insert ( $this->table_name, $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	public function update_User_binding_log_for_data($pk, $data) {
		$this->w_db->where ( $this->fields_pk, $pk );
		$this->w_db->set ( 'ub_time_update', 'NOW()', FALSE );
		$this->w_db->update ( $this->table_name, $data );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
	public function get_row_User_binding_log_by_pk($select, $pk) {
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
	public function insert_copy_User_binding($device_key, $status_code) {
		$this->r_db->select ( 'ub_user_no as ubl_user_no, ub_mongo_id as ubl_mongo_id, ub_member_id as ubl_member_id, ub_dealer as ubl_dealer, ub_device_id as ubl_device_id, ub_device_mac as ubl_device_mac, ub_device_key as ubl_device_key, ub_order_sn as ubl_order_sn' );
		$this->r_db->where ( 'ub_device_key', $device_key );
		$query = $this->r_db->get ( 'User_binding_tbl' );
		// echo $this->r_db->last_query();
		if ($query->num_rows () > 0) {
			$row = $query->row ();
			$row->ub_status_code = $status_code;
			$this->w_db->insert ( $this->table_name, $row );
			// echo $this->r_db->last_query();
			$id = $this->w_db->insert_id ();
			return $id;
		}
		return false;
	}
}
