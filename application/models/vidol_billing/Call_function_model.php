<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

class Call_function_model extends CI_Model
{
	private $table_name = '';
	private $fields_pk = '';
	
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
	
	/**
	 * 建立訂單資料
	 * @param unknown $user_creat		訂單建立者(0:system)對應後台帳號pk
	 * @param unknown $user_no			購買會員(user_pk等會員整合後使用)
	 * @param unknown $mongo_id			會員mongo_id
	 * @param unknown $member_id		會員ID
	 * @param unknown $package_no		產品包編號
	 * @param unknown $payment_no		付款方式
	 * @param unknown $coupon_sn		優惠卷
	 * @param unknown $invoice_type		發票類型(1:電子發票用vidol載具,2:捐贈發票,3:索取三連發票)
	 * @param unknown $ip				購買者IP
	 * @return unknown[]|boolean		[狀態,訂單號碼,須付價錢]
	 */
	public function add_order ($user_creat, $user_no, $mongo_id, $member_id, $package_no, $payment_no, $coupon_sn, $invoice_type, $ip)
	{
		$sql = sprintf("call add_order(%d,%d,'%s','%s',%d,%d,'%s','%s','%s',@result);", $user_creat, $user_no, $mongo_id, $member_id, $package_no, $payment_no, $coupon_sn, $invoice_type, $ip);
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_order', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = explode(',', $row->result);
			$result = array(
					'status_code'=>$result['0'],
					'order_sn'=>$result['1'],
					'price'=>$result['2'],
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_order', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 序號兌換
	 * @param unknown $user_creat	訂單建立者(0:system)對應後台帳號pk
	 * @param unknown $user_no		購買會員(user_pk等會員整合後使用)
	 * @param unknown $mongo_id		會員mongo_id
	 * @param unknown $member_id	會員ID
	 * @param unknown $coupon_sn	優惠卷
	 * @param unknown $ip			購買者IP
	 * @return unknown[]|boolean
	 */
	public function exchange_SN($user_creat, $user_no, $mongo_id, $member_id, $coupon_sn, $ip)
	{
		$sql = sprintf("call exchange_SN(%d,%d,'%s','%s','%s','%s',@result);", $user_creat, $user_no, $mongo_id, $member_id, $coupon_sn, $ip);
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'exchange_SN', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = explode(',', $row->result);
			$result = array(
					'status_code'=>$result['0'],
					'order_sn'=>$result['1'],
					'price'=>$result['2'],
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'exchange_SN', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 建立發票
	 * @param unknown $order_sn			訂單序號
	 * @param unknown $TransNum			支付交易序號
	 * @param unknown $invoice_type		發票類型(1:電子發票用vidol載具,2:捐贈發票,3:索取三連發票)
	 * @param unknown $BuyerName		買受人名稱
	 * @param unknown $BuyerUBN			買受人統一編號
	 * @param unknown $BuyerPhone		買受人電話
	 * @param unknown $BuyerAddress		買受人地址
	 * @param unknown $BuyerEmail		買受人電子信箱
	 * @param unknown $LoveCode			愛心碼
	 * @param unknown $ItemName			商品名稱
	 * @param unknown $ItemPrice		商品單價(開立發票價錢)
	 * @param unknown $Comment			備註
	 * @return unknown[]|boolean		[狀態]
	 */
	public function add_invoice ($order_sn, $TransNum, $invoice_type, $BuyerName, $BuyerUBN, $BuyerPhone, $BuyerAddress, $BuyerEmail, $LoveCode, $ItemName, $ItemPrice, $Comment)
	{
		$sql = sprintf("call add_invoice('%s','%s',%d,'%s','%s','%s','%s','%s','%s','%s',%d,'%s',@result);", $order_sn, $TransNum, $invoice_type, $BuyerName, $BuyerUBN, $BuyerPhone, $BuyerAddress, $BuyerEmail, $LoveCode, $ItemName, $ItemPrice, $Comment);
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_invoice', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = array(
					'status_code'=>$row->result
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_invoice', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 訂單成立
	 * @param unknown $order_sn			訂單序號
	 * @param unknown $trade_no			支付交易序號
	 * @param unknown $rs				續扣(自動扣)
	 * @param unknown $TokenUseStatus	約定信用卡付款授權狀態
	 * @param unknown $TokenValue		約定信用卡付款授權Token
	 * @param unknown $TokenLife		約定信用卡付款授權之有效日期(UTC+8)
	 * @return unknown[]|boolean		[狀態]
	 */
	public function add_to_cash($order_sn, $trade_no, $rs, $TokenUseStatus, $TokenValue, $TokenLife)
	{
		if(empty($TokenUseStatus)){
			//不續扣
			$sql = sprintf("call add_to_cash('%s','%s',%d,%d,null,null,@result);", $order_sn, $trade_no, $rs, $TokenUseStatus);
		}else{
			//自動續扣
			$sql = sprintf("call add_to_cash('%s','%s',%d,%d,'%s','%s',@result);", $order_sn, $trade_no, $rs, $TokenUseStatus, $TokenValue, $TokenLife);
		}
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_to_cash', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = array(
					'status_code'=>$row->result
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_to_cash', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 訂單(失敗||取消)處理
	 * @param unknown $order_sn		訂單序號
	 * @param unknown $status		狀態(-1:fail,2:cancel)
	 * @param unknown $refund		退款金額
	 * @param unknown $note			備註
	 * @return unknown[]|boolean
	 */
	public function add_to_cancel_cash($order_sn, $status, $refund, $note)
	{
		$sql = sprintf("call add_to_cancel_cash('%s',%d,%d,'%s',@result);", $order_sn, $status, $refund, $note);
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_to_cancel_cash', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = array(
					'status_code'=>$row->result
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'add_to_cancel_cash', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
	
	public function check_rights($user_no, $mongo_id, $member_id, $video_type, $video_no)
	{
		$sql = sprintf("call check_rights(%d,'%s','%s','%s',%d,@result);", $user_no, $mongo_id, $member_id, $video_type, $video_no);
		$query = $this->w_db->query($sql);
		//echo $this->w_db->last_query();
		$query = $this->w_db->query('select @result as result;');
		//echo $this->w_db->last_query();
		//$this->w_db->insert('Rest_logs_tbl', array('uri'=>'check_rights', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1'));
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$result = array(
					'status_code'=>$row->result
			);
			//log
			$this->w_db->insert('Rest_logs_tbl', array('uri'=>'check_rights', 'method'=>'call', 'params'=>$sql, 'api_key'=>'system', 'ip_address'=>'127.0.0.1', 'time'=>time(), 'authorized'=>'1', 'response_code'=>$result['status_code']));
			return $result;
		}else{
			return false;
		}
	}
}
