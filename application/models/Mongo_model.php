<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Mongo_model extends CI_Model
{

    private static $instanceof = NULL;
    
    private $mongo;
	
    private $db;
	
    private $mongo_config = array();
    
    private $activate;
    
    public function __construct ()
    {
        parent::__construct();
        // 共用DB資料
        $this->config->load('mongo_db');
        $this->mongo_config = $this->config->item('mongo_db');
        $this->activate = $this->mongo_config['active'];
        // 連線mongodb
        // $m = new MongoClient('mongodb://帳號:密碼@localhost');
        $dbhost = $this->mongo_config[$this->activate]['hostname'];
        $dbport = $this->mongo_config[$this->activate]['port'];
        $dbuser = $this->mongo_config[$this->activate]['username'];
        $dbpass = $this->mongo_config[$this->activate]['password'];
        $dbname = $this->mongo_config[$this->activate]['database'];
        $replica = $this->mongo_config[$this->activate]['replica'];
        $mongo_string = sprintf('mongodb://%s:%s/%s%s', $dbhost, $dbport, $dbname, $replica);
        // 連線
        $this->mongo = new MongoClient($mongo_string, [
                'username' => $dbuser,
                'password' => $dbpass,
                'db' => $dbname
        ]);
    }

    /**
     * 查詢mongo會員資料
     * 
     * @param unknown $like_string
     *            查詢資料(_id || email || member_id)
     */
    public function get_user_by_id_memberid_or_email ($like_string)
    {
        if (empty($like_string)) {
            return false;
        }
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        // $db->users->find(array('name' => new MongoRegex('/Joe/')));
        // $find = array('$or' => array(array('a' => 1), array('b' => 2))));
        $find = array(
                '$or' => array(
                        array(
                                '_id' => new MongoRegex($like_string)
                        ),
                        array(
                                'email' => new MongoRegex($like_string)
                        ),
                        array(
                                'member_id' => new MongoRegex($like_string)
                        )
                )
        );
        $result = $Users->find($find)->limit(20);
        return $result;
    }

    /**
     * 取得mongo會員資料(單筆)
     * 
     * @param unknown $member_id
     *            會員ID
     */
    public function get_user_by_memberid ($member_id)
    {
        if (empty($member_id)) {
            return false;
        }
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $find['member_id'] = $member_id;
        $result = $Users->findOne($find);
        return $result;
    }

    /**
     * 查詢mongo會員註建立時間
     * 
     * @param unknown $start_date
     *            起始時間
     * @param unknown $end_date
     *            結束時間
     */
    public function get_user_count_by_createdat ($start_date, $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            return false;
        }
        $find = array();
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $start = new MongoDate(strtotime($start_date));
        $end = new MongoDate(strtotime($end_date));
        $find['_created_at'] = array(
                '$gt' => $start,
                '$lte' => $end
        );
        $result = $Users->find($find)->count();
        return $result;
    }

    //一般註冊會員(沒用到)
    public function get_user_re_count_by_createdat ($start_date, $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            return false;
        }
        $find = array();
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $start = new MongoDate(strtotime($start_date));
        $end = new MongoDate(strtotime($end_date));
        $find['_created_at'] = array(
                '$gt' => $start,
                '$lte' => $end
        );
        $find['_auth_data_facebook'] = array(
                '$ne' => null
        );
        $result = $Users->find($find)->count();
        return $result;
    }

    /**
     * 查詢mongo會員註建立時間(FB有資料的)
     * 
     * @param unknown $start_date
     *            起始時間
     * @param unknown $end_date
     *            結束時間
     */
    public function get_user_fb_count_by_createdat ($start_date, $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            return false;
        }
        $find = array();
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $start = new MongoDate(strtotime($start_date));
        $end = new MongoDate(strtotime($end_date));
        $find['_created_at'] = array(
                '$gt' => $start,
                '$lte' => $end
        );
        $find['_auth_data_facebook'] = array(
                '$ne' => null
        );
        $result = $Users->find($find)->count();
        return $result;
    }

    /**
     * 查詢mongo會員FB總數
     */
    public function get_user_count_by_facebook ()
    {
        $find = array();
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_User';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $find['_auth_data_facebook'] = array(
                '$ne' => null
        );
        $result = $Users->find($find)->count();
        return $result;
    }
    
    public function get_user_session_by_p_user ($like_string)
    {
        if (empty($like_string)) {
            return false;
        }
        $dbname = $this->mongo_config[$this->activate]['database'];
        $user_table = '_Session';
        // 選擇資料庫
        $this->db = $this->mongo->$dbname;
        $Users = $this->db->$user_table;
        $find = array('_p_user' => new MongoRegex($like_string));
        $result = $Users->find($find)->sort(array('_created_at'=>-1))->limit(20);
        return $result;
    }
    
    //取的年紀會員數
    public function get_user_count_by_facebook_age ($start_date, $end_date)
    {
    	//db.getCollection('_User').find({birth_date:{"$gt":"1999-02-24", "$lte":"2000-02-24"}}).count();
    	if (empty($start_date) || empty($end_date)) {
    		return false;
    	}
    	$find = array();
    	$dbname = $this->mongo_config[$this->activate]['database'];
    	$user_table = '_User';
    	// 選擇資料庫
    	$this->db = $this->mongo->$dbname;
    	$Users = $this->db->$user_table;
    	$find['birth_date'] = array(
    			'$gt' => $start_date,
    			'$lte' => $end_date
    	);
    	$result = $Users->find($find)->count();
    	return $result;
    }
}
