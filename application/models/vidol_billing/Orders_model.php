<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

	class Orders_model extends CI_Model
	{
		private $table_name = 'Orders_tbl';
		private $fields_pk = 'o_pk';

		public function __construct ()
		{
			parent::__construct();
			// $this->load->config('set/databases_fiels', TRUE);
			$this->r_db = $this->load->database('vidol_billing_read', TRUE);
			$this->w_db = $this->load->database('vidol_billing_write', TRUE);
		}

		public function __destruct() {
			$this->r_db->close();
			unset($this->r_db);
			$this->w_db->close();
			unset($this->w_db);
			// parent::__destruct();
		}

		public function insert_Orders_for_data($data){
			$this->w_db->insert($this->table_name, $data);
			$id = $this->w_db->insert_id();
			//echo $this->w_db->last_query();
			return $id;
		}

		public function update_Orders_for_data($pk, $data){
			$this->w_db->where($this->fields_pk, $pk);
			$this->w_db->update($this->table_name, $data);
			$result = $this->w_db->affected_rows();
			//echo $this->w_db->last_query();
			return $result;
		}

		public function get_row_Orders_by_pk ($select, $pk)
		{
			if(!empty($select)){
				$this->r_db->select($select);
			}
			$this->r_db->where($this->fields_pk, $pk);
			$query = $this->r_db->get($this->table_name);
			//echo $this->r_db->last_query();
			if ($query->num_rows() > 0){
				return $query->row();
			}
			return false;
		}

		/**
		 * 更新訂單發票資料
		 * @param unknown $order_sn
		 * @param unknown $invoice
		 * @return unknown
		 */
		public function update_Orders_Invoice_by_order_sn ($order_sn, $invoice)
		{
			$this->w_db->where('o_order_sn', $order_sn);
			$this->w_db->set('o_invoice', $invoice);
			$this->w_db->update($this->table_name);
			$result = $this->w_db->affected_rows();
			// echo $this->w_db->last_query();
			return $result;
		}

		/**
		 * 取得訂單紀錄資料
		 * @param unknown $order_sn
		 * @return unknown
		 */
		public function get_Orders_by_order_sn ($order_sn)
		{
			$this->r_db->select('o_order_sn, o_member_id, o_package_no, o_package_title, o_package_type, o_package_unit, o_package_unit_value, o_payment_no, o_cost, o_price, o_coupon_no, o_coupon_sn, o_coupon_title, o_expenses, o_subtotal, o_rs, o_status, o_ip, o_note');
			$this->r_db->where('o_order_sn', $order_sn);
			$query = $this->r_db->get($this->table_name);
			// echo $this->r_db->last_query();
			return $query;
		}

		/**
		 * 取得使用者訂單筆數
		 * @param unknown $user_no
		 * @param unknown $mongo_id
		 * @param unknown $member_id
		 * @param unknown $status
		 * @return unknown
		 */
		public function get_Orders_count_by_user ($user_no, $mongo_id, $member_id,  $status)
		{
			$this->r_db->select('o_pk');
			if(!empty($user_no)){
				$this->r_db->where('o_user_no', $user_no);
			}
			if(!empty($mongo_id)){
				$this->r_db->where('o_mongo_id', $mongo_id);
			}
			if(!empty($member_id)){
				$this->r_db->where('o_member_id', $member_id);
			}
			if(is_numeric($status)){
				$this->r_db->where('o_status', $status);
			}
			$count = $this->r_db->count_all_results($this->table_name);
			// echo $this->r_db->last_query();
			return $count;
		}

		/**
		 * 取得使用者訂單
		 * @param unknown $user_no
		 * @param unknown $mongo_id
		 * @param unknown $member_id
		 * @param unknown $status
		 * @param number $sort
		 * @param number $start
		 * @param number $limit
		 * @return unknown
		 */
		public function get_Orders_by_user ($user_no, $mongo_id, $member_id, $status, $sort = 0, $start = 0, $limit = 10)
		{
			if(!empty($user_no)){
				$this->r_db->where('o_user_no', $user_no);
			}
			if(!empty($mongo_id)){
				$this->r_db->where('o_mongo_id', $mongo_id);
			}
			if(!empty($member_id)){
				$this->r_db->where('o_member_id', $member_id);
			}
			if(is_numeric($status)){
				$this->r_db->where('o_status', $status);
			}
			$this->r_db->limit($limit, $start);
			if($sort == 1){
				$this->r_db->order_by('o_time_creat', 'ASC');
			}else{
				$this->r_db->order_by('o_time_creat', 'DESC');
			}
			$query = $this->r_db->get($this->table_name);
			// echo $this->r_db->last_query();
			return $query;
		}
	}
