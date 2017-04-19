<?php
defined ( "BASEPATH" ) or exit ( "No direct script access allowed" );
class SwaggerDoc extends CI_Controller {
	private $data_view;
	function __construct() {
		parent::__construct ();
	}
	public function index() {
		if (isset ( $_GET [''] )) {
		}
		// $api_host = (ENVIRONMENT == 'production') ? "plugin-billing.vidol.tv" : "cplugin-billing.vidol.tv";
		$api_host = $_SERVER ['HTTP_HOST'];
		$doc_array = array (
				"swagger" => "2.0",
				"info" => array (
						"title" => "RESTful API Documentation",
						"description" => "RESTful api control panel of technical documents.",
						"termsOfService" => "#",
						"contact" => array (
								"email" => "zeren828@gmail.com" 
						),
						"license" => array (
								"name" => "Apache 2.0",
								"url" => "#" 
						),
						"version" => "V 1.0" 
				),
				"host" => $api_host,
				"basePath" => "/v1",
				"tags" => array (
						array (
								"name" => "1.dealers",
								"description" => "經銷商" 
						),
						array (
								"name" => "2.users",
								"description" => "使用者" 
						),
						array (
								"name" => "5.ovo",
								"description" => "OVO" 
						),
						array (
								"name" => "9.system",
								"description" => "系統用" 
						) 
				),
				"schemes" => array (
						"http" 
				),
				"paths" => array (
						"/tvboxs/dealers/dealer" => array (
								"get" => array (
										"tags" => array (
												"1.dealers" 
										),
										"summary" => "取得經銷商",
										"description" => "取得經銷商基本資訊",
										"parameters" => array (
												array (
														"name" => "device_key",
														"description" => "裝置KEY(設備資訊加密)",
														"in" => "query",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "debug",
														"description" => "除錯用多列印出取得資料變數",
														"in" => "query",
														"type" => "string",
														"enum" => array (
																'debug' 
														) 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"result" => $this->__get_responses_data ( "dealer info" ),
																		"code" => array (
																				"title" => "資訊碼",
																				"description" => "資訊碼" 
																		),
																		"message" => array (
																				"title" => "訊息",
																				"description" => "訊息" 
																		),
																		"time" => array (
																				"title" => "程式時間",
																				"description" => "程式時間" 
																		) 
																) 
														) 
												),
												"400" => array (
														"description" => "錯誤警告" 
												),
												"416" => array (
														"description" => "傳遞資料錯誤" 
												) 
										) 
								) 
						),
						"/tvboxs/users/binding" => array (
								"post" => array (
										"tags" => array (
												"2.users" 
										),
										"summary" => "使用者綁定電視盒",
										"description" => "使用者綁定電視盒一台機器綁一次,廠商可以多台綁多次以機器算(條件USER+廠商+keyword)",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "token",
														"description" => "Middle Layer token[無法傳送header時備用]",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "user_no",
														"description" => "購買會員(user_pk等會員整合後使用)",
														"in" => "formData",
														"type" => "integer" 
												),
												array (
														"name" => "mongo_id",
														"description" => "會員mongo_id",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "member_id",
														"description" => "會員ID",
														"in" => "formData",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "device_key",
														"description" => "裝置KEY",
														"in" => "formData",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "ip",
														"description" => "IP",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "debug",
														"description" => "除錯用多列印出取得資料變數",
														"in" => "formData",
														"type" => "string",
														"enum" => array (
																'debug' 
														) 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"result" => array (
																				"title" => "回傳資料",
																				"description" => "回傳資料" 
																		),
																		"code" => array (
																				"title" => "資訊碼",
																				"description" => "資訊碼" 
																		),
																		"message" => array (
																				"title" => "訊息",
																				"description" => "訊息" 
																		),
																		"time" => array (
																				"title" => "程式時間",
																				"description" => "程式時間" 
																		) 
																) 
														) 
												),
												"400" => array (
														"description" => "錯誤警告" 
												),
												"403" => array (
														"description" => "token未授權" 
												),
												"416" => array (
														"description" => "傳遞資料錯誤" 
												) 
										) 
								) 
						),
						"/tvboxs/ovo/ad" => array (
								"get" => array (
										"tags" => array (
												"5.ovo" 
										),
										"summary" => "OVO動態牆",
										"description" => "提供OVO取得資料擺放動態牆",
										"parameters" => array (
												array (
														"name" => "debug",
														"description" => "除錯用多列印出取得資料變數",
														"in" => "query",
														"type" => "string",
														"enum" => array (
																'debug' 
														) 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"status" => array (
																				"type" => "string",
																				"title" => "狀態",
																				"description" => "狀態" 
																		),
																		"timestamp" => array (
																				"type" => "string",
																				"title" => "時間",
																				"description" => "時間" 
																		),
																		"data" => $this->__get_responses_data ( "ovo ad info" ),
																		"code" => array (
																				"type" => "string",
																				"title" => "資訊碼",
																				"description" => "資訊碼" 
																		),
																		"message" => array (
																				"type" => "string",
																				"title" => "訊息",
																				"description" => "訊息" 
																		),
																		"time" => array (
																				"type" => "integer",
																				"title" => "程式時間",
																				"description" => "程式時間" 
																		) 
																) 
														) 
												),
												"400" => array (
														"description" => "錯誤警告" 
												),
												"416" => array (
														"description" => "傳遞資料錯誤" 
												) 
										) 
								) 
						),
						"/tvboxs/systems/version" => array (
								"get" => array (
										"tags" => array (
												"9.system" 
										),
										"summary" => "公版版本資訊",
										"description" => "取得取得公版版本資訊",
										"parameters" => array (
												array (
														"name" => "debug",
														"description" => "除錯用多列印出取得資料變數",
														"in" => "query",
														"type" => "string",
														"enum" => array (
																'debug' 
														) 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"result" => $this->__get_responses_data ( "system version info" ),
																		"code" => array (
																				"title" => "資訊碼",
																				"description" => "資訊碼" 
																		),
																		"message" => array (
																				"title" => "訊息",
																				"description" => "訊息" 
																		),
																		"time" => array (
																				"title" => "程式時間",
																				"description" => "程式時間" 
																		) 
																) 
														) 
												),
												"400" => array (
														"description" => "錯誤警告" 
												),
												"416" => array (
														"description" => "傳遞資料錯誤" 
												) 
										) 
								) 
						) 
				) 
		);
		$this->output->set_content_type ( "application/json" );
		$this->output->set_output ( json_encode ( $doc_array ) );
	}
	
	/**
	 * 回傳的資料整理
	 *
	 * @param unknown $type        	
	 * @return string[]
	 */
	function __get_responses_data($type) {
		$responses = array ();
		switch ($type) {
			case "dealer info" :
				$responses = array (
						"title" => "dealer info",
						"type" => "object",
						"description" => "經銷商資訊",
						"properties" => array (
								"no" => array (
										"type" => "integer",
										"description" => "編號" 
								),
								"title" => array (
										"type" => "string",
										"description" => "名稱" 
								),
								"description" => array (
										"type" => "string",
										"description" => "描述" 
								),
								"logo_url" => array (
										"type" => "string",
										"description" => "圖案位置" 
								),
								"version" => array (
										"type" => "string",
										"description" => "現行版本" 
								),
								"update" => array (
										"type" => "integer",
										"description" => "強制更新" 
								),
								"update_url" => array (
										"type" => "string",
										"description" => "更新位置" 
								),
								"status" => array (
										"type" => "integer",
										"description" => "狀態" 
								) 
						) 
				);
				break;
			case "ovo ad info" :
				$responses = array (
						"title" => "dealer info",
						"type" => "object",
						"description" => "動態牆資訊",
						"properties" => array (
								"id" => array (
										"type" => "integer",
										"description" => "編號" 
								),
								"title" => array (
										"type" => "string",
										"description" => "標題" 
								),
								"thumbnail" => array (
										"type" => "string",
										"description" => "圖片的URL" 
								),
								"appUri" => array (
										"type" => "string",
										"description" => "跳轉到app所需的uri" 
								),
								"lastUpdateTime" => array (
										"type" => "integer",
										"description" => "最後更新時間" 
								) 
						) 
				);
				break;
			case "system version info" :
				$responses = array (
						"title" => "system version info",
						"type" => "object",
						"description" => "公版版本資訊",
						"properties" => array (
								"description" => array (
										"type" => "string",
										"description" => "描述" 
								),
								"version" => array (
										"type" => "string",
										"description" => "現行版本" 
								),
								"update" => array (
										"type" => "integer",
										"description" => "強制更新" 
								),
								"update_url" => array (
										"type" => "string",
										"description" => "更新位置" 
								) 
						) 
				);
				break;
			default :
				$responses = array (
						"description" => "OK" 
				);
				break;
		}
		return $responses;
	}
}

/* End of file swaggerDoc.php */
/* Location: ./application/controllers/v1/swaggerDoc.php */
