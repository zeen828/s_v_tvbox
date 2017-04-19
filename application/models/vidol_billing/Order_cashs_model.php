<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

	class Order_cashs_model extends CI_Model
	{
		private $table_name = 'Order_cashs_tbl';
		private $fields_pk = 'oc_pk';

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
			//parent::__destruct();
		}

		public function insert_Order_cashs_for_data($data){
			$this->w_db->insert($this->table_name, $data);
			$id = $this->w_db->insert_id();
			//echo $this->w_db->last_query();
			return $id;
		}

		public function update_Order_cashs_for_data($pk, $data){
			$this->w_db->where($this->fields_pk, $pk);
			$this->w_db->update($this->table_name, $data);
			$result = $this->w_db->affected_rows();
			//echo $this->w_db->last_query();
			return $result;
		}

		public function get_row_Order_cashs_by_pk ($select, $pk)
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
		 * 更新成功訂單發票資料
		 * @param unknown $order_sn
		 * @param unknown $invoice
		 * @return unknown
		 */
		public function update_Order_cashs_Invoice_by_order_sn ($order_sn, $invoice)
		{
			$this->w_db->where('oc_order_sn', $order_sn);
			$this->w_db->set('oc_invoice', $invoice);
			$this->w_db->update($this->table_name);
			$result = $this->w_db->affected_rows();
			// echo $this->w_db->last_query();
			return $result;
		}

		//自動扣款
		/**
		 * 取得需要自動扣款的訂單資料
		 * @param unknown $select		查詢欄位
		 * @param unknown $time_start	查詢開始時間
		 * @param unknown $time_end		查詢結束時間
		 * @param number $limit			查詢筆數
		 * @return unknown
		 */
		public function get_Order_cashs_by_rs ($select, $time_start, $time_end, $limit = 500)
		{
			if(!empty($select)){
				$this->r_db->select($select);
			}
			$this->r_db->where('oc_rs', '1');
			$this->r_db->where('oc_status', '1');
			$this->r_db->where('oc_TokenUseStatus', '1');
			$this->r_db->where('oc_TokenValue IS NOT NULL');
			$this->r_db->where('oc_time_deadline IS NOT NULL');
			$this->r_db->where(sprintf('oc_time_deadline BETWEEN "%s" AND "%s"', $time_start, $time_end));
			$this->r_db->limit($limit);
			$query = $this->r_db->get($this->table_name);
			//echo $this->r_db->last_query();
			return $query;
		}
	}
